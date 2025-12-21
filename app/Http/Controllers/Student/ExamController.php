<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Öğrenciye atanmış sınavları listeler.
     */
    public function index()
    {
        $studentId = Auth::id();

        // 1. Öğrenciye atanmış sınav ID'lerini çekme (exam_assignments tablosu üzerinden)
        // Exam modelindeki "assignedUsers" ilişkisinin tersini kullanıyoruz.
        $assignedExams = Exam::whereHas('assignedUsers', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })
            ->withCount(['questions']) // Soru sayısını doğrudan çekelim
            ->get();

        return view('backend.pages.student.exams.index', compact('assignedExams'));
    }

    /**
     * Sınavın detaylarını ve başlatma ekranını gösterir.
     */
    public function show(Exam $exam)
    {
        $studentId = Auth::id(); // Varsayılan Guard'dan ID'yi alıyoruz

        // Yetki Kontrolü: Öğrenci bu sınava atanmış mı?
        if (!$exam->assignedUsers()->where('user_id', $studentId)->exists()) {
            abort(403, 'Bu sınava erişim yetkiniz yok.'); // Hatanın geldiği yer burası
        }

        // Öğrencinin aktif veya tamamlanmış oturumu var mı kontrol et
        $activeSession = $exam->sessions()->where('user_id', Auth::id())->first();

        // Eğer tamamlanmış bir oturum varsa, sonuç sayfasına yönlendirilebilir.
        if ($activeSession && $activeSession->is_completed) {
            return redirect()->route('student.exams.results', $exam->id);
        }

        return view('backend.pages.student.exams.show', compact('exam', 'activeSession'));
    }
    public function startSession(Request $request, Exam $exam)
    {
        $userId = Auth::id();

        // Yetki Kontrolü
        if (!$exam->assignedUsers()->where('user_id', $userId)->exists()) {
            return redirect()->back()->with('error', 'Bu sınava başlama yetkiniz yok.');
        }

        // Zaten aktif veya tamamlanmış bir oturum var mı?
        $existingSession = $exam->sessions()->where('user_id', $userId)->first();

        if ($existingSession && !$existingSession->is_completed) {
            // Aktif oturum varsa, direkt sınav sayfasına yönlendir.
            return redirect()->route('student.exam-live', $existingSession->id);
        } elseif ($existingSession && $existingSession->is_completed) {
            return redirect()->route('student.exams.results', $exam->id);
        }

        // Yeni Oturum Başlatma
        $session = $exam->sessions()->create([
            'user_id' => $userId,
            'started_at' => now(), // Sınav başlangıç zamanı
            'ended_at' => now()->addMinutes($exam->duration_minutes), // Bitiş zamanını hesapla
            'status' => 'started',
            'is_completed' => false,
            // session_token alanı da burada oluşturulabilir (güvenlik için)
        ]);

        // Sınavın asıl yapılacağı canlı sayfaya yönlendirme
        return redirect()->route('student.exam-live', $session->id);
    }
    public function results(Exam $exam)
    {
        $userId = auth()->id();

        $session = $exam->sessions()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->latest('ended_at') // daha doğru: en son biten oturum
            ->first();

        if (!$session) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Tamamlanmış sınav bulunamadı.');
        }

        $totalQuestions = $exam->questions()->count();

        // Cevapları al
        $studentAnswers = $session->studentAnswers;

        // ✅ Cevaplanan soru sayısı (boş string/boşluk sayma)
        $answeredCount = $studentAnswers
            ->filter(fn($a) => trim((string)($a->answer_text ?? '')) !== '')
            ->count();

        // Doğru / yanlış / bekleyen
        $correctCount = $studentAnswers->where('is_correct', true)->count();
        $wrongCount   = $studentAnswers->where('is_correct', false)->whereNotNull('is_correct')->count();
        $pendingCount = $studentAnswers->whereNull('is_correct')->count(); // açık uçlular vs.

        return view('backend.pages.student.exams.results', compact(
            'exam',
            'session',
            'totalQuestions',
            'answeredCount',   // ✅ bunu ekledik
            'correctCount',
            'wrongCount',
            'pendingCount'
        ));
    }

}
