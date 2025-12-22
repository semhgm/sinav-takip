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
use Illuminate\Support\Facades\Storage;

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
    public function storeSnapshot(Request $request)
    {

        $validated = $request->validate([
            'token' => 'required|string',
            'event_type' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $session = ExamSession::where('proctor_token', $validated['token'])->firstOrFail();

        // Dosya adı
        $filename = sprintf(
            'session_%d_%s_%s.jpg',
            $session->id,
            $validated['event_type'],
            now()->format('Ymd_His')
        );

        // Kaydet
        $path = $request->file('image')->storeAs(
            'proctor_snapshots',
            $filename,
            'public'
        );

        // Mongo correlation
        $correlation = MongoCorrelation::firstOrCreate(
            ['session_id' => $session->id],
            ['mongo_log_key' => (string) \Str::uuid()]
        );

        // MongoDB’ye snapshot kaydı
        $mongo = new \MongoDB\Client();
        $mongo->proctoring
            ->{$correlation->mongo_log_key}
            ->insertOne([
                'session_id' => $session->id,
                'ts' => now()->toISOString(),
                'source' => 'camera',
                'event_type' => $validated['event_type'],
                'snapshot_url' => Storage::url($path),
            ]);

        return response()->json([
            'ok' => true,
            'snapshot_url' => Storage::url($path),
        ]);
    }
}
