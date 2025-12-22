<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\Violation;
use App\Models\MongoCorrelation;
use App\Models\SessionHeartbeat;
use Illuminate\Http\Request;
use MongoDB\Client as MongoClient;
use Illuminate\Support\Str;

class ProctorController extends Controller
{
    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'token'       => 'required|string',
            'source'      => 'required|in:camera,microphone,browser,system',
            'event_type'  => 'required|string|max:100',
            'level'       => 'required|in:info,warning,critical',
            'metrics'     => 'nullable|array',
        ]);

        $session = ExamSession::where('proctor_token', $validated['token'])->firstOrFail();

        /** -----------------------------
         * Mongo Correlation (1 kere oluşturulur)
         * ----------------------------- */
        $correlation = MongoCorrelation::firstOrCreate(
            ['session_id' => $session->id],
            ['mongo_log_key' => (string) Str::uuid()]
        );

        /** -----------------------------
         * MongoDB’ye event yaz
         * ----------------------------- */
        $mongo = new MongoClient();
        $mongo->proctoring
            ->{$correlation->mongo_log_key}
            ->insertOne([
                'session_id' => $session->id,
                'ts'         => now()->toISOString(),
                'source'     => $validated['source'],
                'event_type' => $validated['event_type'],
                'level'      => $validated['level'],
                'metrics'    => $validated['metrics'] ?? [],
            ]);

        /** -----------------------------
         * Kritik olay → MySQL violations
         * ----------------------------- */
        if ($validated['level'] === 'critical') {
            Violation::create([
                'session_id'  => $session->id,
                'type'        => $validated['event_type'],
                'occurred_at' => now(),
                'source'      => $validated['source'],
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function heartbeat(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
        ]);

        $session = ExamSession::where('proctor_token', $validated['token'])->firstOrFail();

        SessionHeartbeat::create([
            'session_id' => $session->id,
            'beat_at'    => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}
