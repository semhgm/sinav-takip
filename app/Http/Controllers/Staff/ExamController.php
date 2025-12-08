<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::where('created_by', auth()->id())
            ->latest() // En son oluşturulanları en üste getir
            ->get();        return view('backend.pages.staff.exams.index', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gözetmenlik (Proctoring) Ayarları ve Diğer Kurallar
        $examRules = [
            'shuffle_questions'  => 'Soruları Karıştır',
            'shuffle_options'    => 'Seçenekleri Karıştır',
            'proctoring_enabled' => 'Gözetmenlik (Kamera Takibi) Aktif',
            'allow_back'         => 'Geri Dönüşe İzin Ver',
        ];

        return view('backend.pages.staff.exams.create', compact('examRules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'            => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            // 'description' alanı migration'da yok, bu yüzden kaldırıldı veya modele eklenmeli.
            'settings'         => 'nullable|array', // Yeni alan adı
        ]);

        // 2. Ayarları JSON formatına dönüştürme
        $defaultSettings = [
            'shuffle_questions'  => false,
            'shuffle_options'    => false,
            'proctoring_enabled' => false,
            'allow_back'         => false,
        ];

        $finalSettings = array_merge($defaultSettings, $validatedData['settings'] ?? []);
        $validatedData['settings'] = json_encode($finalSettings);
        $validatedData['created_by'] = Auth::user()->id;
        Exam::create($validatedData);

        // 4. Başarıyla Yönlendirme
        return redirect()
            ->route('staff.exams.index')
            ->with('success', 'Sınav başarıyla tanımlandı. Şimdi bu sınava soru ekleyebilirsiniz.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        // 1. Yetki Kontrolü (Opsiyonel ama Önemli): Staff sadece kendi sınavını düzenleyebilmeli.
        if ($exam->created_by !== auth()->id()) {
            abort(403, 'Bu sınavı düzenleme yetkiniz yok.');
        }

        // 2. Soru Havuzunu Çekme (Staff'ın erişebildiği tüm sorular)
        // Eğer soruların da kısıtlaması varsa ona göre filtreleme yapılmalı.
        $availableQuestions = Question::latest()->get();

        // 3. Mevcut sınavdaki soruların ID'lerini alalım (Pivot tablosu verisi)
        $attachedQuestionIds = $exam->questions->pluck('id')->toArray();

        // Gözetmenlik (Proctoring) Ayarları ve Diğer Kurallar (create metodundan kopyalanabilir)
        $examRules = [
            'shuffle_questions'  => 'Soruları Karıştır',
            'shuffle_options'    => 'Seçenekleri Karıştır',
            'proctoring_enabled' => 'Gözetmenlik (Kamera Takibi) Aktif',
            'allow_back'         => 'Geri Dönüşe İzin Ver',
        ];

        return view('backend.pages.staff.exams.edit', compact(
            'exam',
            'availableQuestions',
            'attachedQuestionIds',
            'examRules'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        // Yetki Kontrolü
        if ($exam->created_by !== auth()->id()) {
            abort(403, 'Bu sınavı güncelleme yetkiniz yok.');
        }

        // --- Soru Atama İşlemi ---
        if ($request->has('action_type') && $request->action_type === 'questions_update') {

            $questionsToSync = [];
            $attached = $request->input('questions_to_attach', []);

            foreach ($attached as $questionId => $data) {
                // Eğer checkbox işaretliyse (attach: 1), pivot verisini hazırlayalım
                if (isset($data['attach']) && $data['attach'] == 1) {
                    $questionsToSync[$questionId] = [
                        'order' => (int)$data['order'] ?? 0 // Sıra değerini kaydet
                    ];
                }
            }

            // Sync metodu: Sadece listedeki ID'leri pivot tablosunda tutar, olmayanları siler.
            $exam->questions()->sync($questionsToSync);

            return redirect()->back()->with('success', 'Sorular sınava başarıyla eklendi ve sıralandı.');
        }

        // --- Temel Ayarların Güncellenmesi ---
        else {
            $validatedData = $request->validate([
                'title'            => 'required|string|max:255',
                'duration_minutes' => 'required|integer|min:1',
                'settings'         => 'nullable|array',
            ]);

            // Ayarları JSON formatına dönüştürme (store metodundaki mantık)
            $defaultSettings = [
                'shuffle_questions'  => false,
                'shuffle_options'    => false,
                'proctoring_enabled' => false,
                'allow_back'         => false,
            ];
            $finalSettings = array_merge($defaultSettings, $validatedData['settings'] ?? []);
            $validatedData['settings'] = json_encode($finalSettings);

            // created_by alanını güncellememize gerek yok, sadece temel alanları güncelle
            $exam->update($validatedData);

            return redirect()->back()->with('success', 'Sınav ayarları başarıyla güncellendi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function assign(Exam $exam)
    {
        // Yetki Kontrolü
        if ($exam->created_by !== auth()->id()) {
            abort(403, 'Bu sınavı atama yetkiniz yok.');
        }

        // Sadece Öğrenci rolündeki kullanıcıları çekme
        // Varsayım: User modelinizde 'role_id' veya benzeri bir alan var.
        // Eğer rolleriniz ayrı bir tabloda yönetiliyorsa buna göre filtreleme yapmalısınız.
        // Şimdilik sadece örnek bir filtreleme yapalım:
        $students = User::where('role', 'student')->latest()->get(); // 3: Student rolü olsun

        // Halihazırda atanmış öğrenci ID'lerini çekme (Eğer bir `exam_assignments` ilişkiniz varsa)
        // Eğer `assignments()` ilişkisi Exam modelinizde tanımlıysa:
        $assignedStudentIds = $exam->assignedUsers->pluck('user_id')->toArray();

        return view('backend.pages.staff.exams.assign', compact('exam', 'students', 'assignedStudentIds'));
    }
    public function performAssignment(Request $request, Exam $exam)
    {
        // Yetki Kontrolü
        if ($exam->created_by !== auth()->id()) {
            abort(403, 'Bu sınavı atama yetkiniz yok.');
        }

        $validated = $request->validate([
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        $studentIds = $validated['student_ids'] ?? [];

        // Exam modelinizdeki `assignments()` ilişkisini kullanarak atamaları yönetelim.
        // Modelde bu ilişkiyi tanımladığınızdan emin olun: `hasMany(ExamAssignment::class)`

        // Eğer ExamAssignment bir pivot tablosu gibi davranıyorsa:
        // Exam modelinize belongsToMany(User::class, 'exam_assignments', 'exam_id', 'user_id') ekleyin.
        // Daha sonra sync kullanabilirsiniz:

        // Varsayım: Exam modelinde `assignedUsers()` ilişkisi tanımlı.
        $exam->assignedUsers()->sync($studentIds);
        return redirect()
            ->route('staff.exams.index')
            ->with('success', 'Sınav, seçilen öğrencilere başarıyla atandı.');
    }
}
