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
        // 1. Yetki ve Durum Kontrolü
        if ($session->user_id !== Auth::id() || $session->is_completed) {
            return response()->json(['success' => false, 'message' => 'Yetkisiz erişim veya sınav tamamlanmıştır.'], 403);
        }

        // 2. Doğrulama (Validation)
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_text' => 'nullable|string', // Cevap metni (çoktan seçmeli seçenek anahtarı veya açık uçlu metin)
        ]);

        // 3. Veri Hazırlama
        $questionId = $validated['question_id'];
        $answerText = $validated['answer_text'] ?? null;

        // Opsiyonel: Sınav süresi dolduysa kaydı engelle
        if (now()->greaterThan($session->ended_at)) {
            return response()->json(['success' => false, 'message' => 'Sınav süresi dolmuştur. Cevap kaydedilemez.'], 403);
        }

        // 4. Cevabı Kaydet veya Güncelle (Upsert Mantığı)
        // updateOrCreate metodu, mevcut cevabı günceller veya yeni bir tane oluşturur.
        $answer = StudentAnswer::updateOrCreate(
            [
                'session_id' => $session->id,
                'question_id' => $questionId,
            ],
            [
                'answer_text' => $answerText,
                // Diğer alanlar (is_correct, score) daha sonra puanlama modülünde doldurulacaktır.
                // Şimdilik sadece cevabı kaydediyoruz.
                'is_correct' => null,
                'score' => null,
            ]
        );

        // 5. Başarılı Yanıt Döndürme
        return response()->json([
            'success' => true,
            'message' => 'Cevap başarıyla kaydedildi.',
            'answer_id' => $answer->id,
            'question_id' => $questionId // JS'in hangi soruya ait olduğunu bilmesi için
        ]);
    }
    public function finishExam(ExamSession $session)
    {
        // Yetki kontrolü
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        if ($session->is_completed) {
            return redirect()->route('student.exams.results', $session->exam_id);
        }

        // Oturumu tamamlandı olarak işaretle
        $session->update([
            'is_completed' => true,
            'status' => 'completed',
            'actual_end_time' => now(),
        ]);

        // Puanlama Modülü (Modül 8) burada tetiklenecek.

        return redirect()->route('student.exams.results', $session->exam_id)
            ->with('success', 'Sınav başarıyla tamamlandı.');
    }



}
