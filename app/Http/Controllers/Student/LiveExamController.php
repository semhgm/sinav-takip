<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveExamController extends Controller
{
    public function show(ExamSession $session)
    {
        // 1. Yetki ve Durum Kontrolü
        if ($session->user_id !== Auth::id() || $session->is_completed) {
            abort(403, 'Sınav oturumuna erişim yetkiniz yok veya sınav tamamlanmıştır.');
        }

        $exam = $session->exam;

        // 2. Soruları Sıralama
        // Exam modelindeki questions() ilişkisi zaten pivot tablosundan order bilgisini çekiyor.
        // Soruları order'a göre sırala
        $questions = $exam->questions()
            ->orderByPivot('order')
            ->get();

        // 3. Kaydedilmiş Cevapları Çekme
        // Öğrencinin bu oturumdaki mevcut cevaplarını çekelim.
        $studentAnswers = $session->studentAnswers->keyBy('question_id');

        // Gözetmenlik (Proctoring) durumunu Blade'e gönderelim
        $proctoringEnabled = $exam->settings['proctoring_enabled'] ?? false;

        return view('backend.pages.student.exams.live', compact(
            'session',
            'exam',
            'questions',
            'studentAnswers',
            'proctoringEnabled'
        ));
    }

    public function saveAnswer(Request $request, ExamSession $session)
    {
        // Rotalarınızda middleware olduğu için burada sadece session kontrolü yeterli
        if ($session->status !== 'started' || $session->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Oturum aktif değil.'], 403);
        }

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_text' => 'nullable|string',
        ]);

        try {
            // updateOrCreate kullanarak aynı soruya tekrar cevap verilirse üzerine yazarız
            $answer = \App\Models\StudentAnswer::updateOrCreate(
                [
                    'session_id'  => $session->id,
                    'question_id' => $validated['question_id'],
                ],
                [
                    'answer_text' => $validated['answer_text'] ?? '',
                    'is_correct'  => null,
                    'score'       => 0,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Kaydedildi',
                'db_id'   => $answer->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function finishExam(ExamSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        // Eğer zaten tamamlanmışsa doğrudan sonuçlara git
        if ($session->status === 'completed' || $session->status === 'graded') {
            return redirect()->route('student.exams.results', $session->id);
        }

        // Şemadaki 'status' enum yapısına göre güncelliyoruz
        $session->update([
            'status' => 'completed',
            'ended_at' => now(), // actual_end_time yerine şemadaki ended_at'i kullanın
        ]);


        // Rota ismine ve beklediği parametreye (exam) göre yönlendiriyoruz
        return redirect()->route('student.exams.results', $session->exam_id)
            ->with('success', 'Sınav başarıyla tamamlandı.');
    }


}
