<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\QuestionCategory;
use Illuminate\Http\Request;
use App\Models\Question; // Question Modelini kullanmak için dahil ettik
use Illuminate\Validation\ValidationException;
class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Kısıtlama yoksa tüm soruları çekebiliriz.
        // İleride, 'created_by' veya belirli derslere göre filtreleme eklenebilir.
        $questions = Question::with('category') // İlişkisel kategoriyi çekiyoruz
        ->latest()
            ->get();

        return view('backend.pages.staff.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 1. Kategorileri çekme
        $categories = QuestionCategory::all();

        // 2. Soru tiplerini tanımlama (formda kullanmak için)
        $questionTypes = [
            'multiple_choice' => 'Çoktan Seçmeli',
            'open_ended'      => 'Açık Uçlu',
        ];

        // 3. View'e gönderme
        return view('backend.pages.staff.questions.create', compact('categories', 'questionTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 1. Doğrulama (Validation)
            $validatedData = $request->validate([
                'category_id' => 'required|exists:question_categories,id', // Kategori var mı?
                'type'        => 'required|in:multiple_choice,open_ended', // Tanımlı tiplerden biri mi?
                'text'        => 'required|string', // Soru metni
                'options'     => 'nullable|array', // Seçenekler (Çoktan seçmeli için zorunlu olacak)
                'correct_option'  => 'required|string',
                ]);

            // 2. Çoktan Seçmeli İçin Ek Kontrol ve Veri İşleme
            if ($validatedData['type'] === 'multiple_choice') {

                // Seçeneklerin varlığını ve formatını kontrol et
                if (!isset($validatedData['options']) || count($validatedData['options']) < 2) {
                    throw ValidationException::withMessages([
                        'options' => ['Çoktan Seçmeli soru tipi için en az 2 seçenek girilmelidir.'],
                    ]);
                }

                // options alanını JSON olarak kaydetmek için hazırlayalım
                $validatedData['options'] = json_encode($validatedData['options']);

                // Cevap anahtarının seçenekler arasında olup olmadığını kontrol edebiliriz (A, B, C, D gibi)
                $validKeys = array_keys(json_decode($validatedData['options'], true));
                if (!in_array($validatedData['correct_option'], $validKeys)) {
                    throw ValidationException::withMessages([
                        'correct_option' => ['Doğru Cevap anahtarı girilen seçeneklerden biri olmalıdır.'],
                    ]);
                }

            } elseif ($validatedData['type'] === 'open_ended') {
                // Açık uçlu sorularda seçenek alanı boş olmalı
                $validatedData['options'] = null;
            }


            // 3. Veritabanına Kaydetme
            Question::create($validatedData);

            // 4. Başarıyla Yönlendirme
            return redirect()
                ->route('staff.questions.index') // Soruların listelendiği sayfaya yönlendir
                ->with('success', 'Soru başarıyla oluşturuldu ve havuza eklendi.');

        } catch (ValidationException $e) {
            // Hata durumunda formu geri döndür
            return back()->withErrors($e->errors())->withInput();
        }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
