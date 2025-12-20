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
        $userId = Auth::id();

        // 1. Sınav Oturumunu Çekme
        $session = $exam->sessions()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->latest()// Sadece tamamlanmış oturumu göster
            ->first();

        // Oturum bulunamadıysa veya tamamlanmamışsa
        if (!$session) {
            return redirect()->route('student.exams.index')->with('error', 'Bu sınav için tamamlanmış bir oturum bulunamadı.');
        }

        // 2. Cevapları ve Puanları Çekme
        // Puanlama Modülü (Modül 8) tamamlanana kadar buradaki değerler null olacaktır.
        $totalQuestions = $exam->questions()->count();
        $studentAnswers = $session->studentAnswers;

        // Hesaplamalar (Puanlama yapılana kadar geçicidir)
        $answeredCount = $studentAnswers->count();
        // Aşağıdaki iki değer, puanlama yapıldıktan sonra geçerli olacaktır.
        $correctCount = $studentAnswers->where('is_correct', true)->count();
        $totalScore = $studentAnswers->sum('score');

        // Oturum tamamlanmamışsa, süre dolmuştur uyarısı verilebilir.

        return view('backend.pages.student.exams.results', compact(
            'exam',
            'session',
            'totalQuestions',
            'answeredCount',
            'correctCount',
            'totalScore'
        ));
    }
}
