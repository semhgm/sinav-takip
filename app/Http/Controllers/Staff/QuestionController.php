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
            // 1. Doğrulama (points eklendi)
            $validatedData = $request->validate([
                'category_id'    => 'required|exists:question_categories,id',
                'type'           => 'required|in:multiple_choice,open_ended',
                'text'           => 'required|string',
                'options'        => 'nullable|array',
                'correct_option' => 'required|string',
                'points'         => 'required|numeric|min:0|max:100',
            ]);

            // 2. Çoktan Seçmeli İşlemleri
            if ($validatedData['type'] === 'multiple_choice') {
                if (!isset($validatedData['options']) || count($validatedData['options']) < 2) {
                    throw ValidationException::withMessages([
                        'options' => ['Çoktan Seçmeli soru tipi için en az 2 seçenek girilmelidir.'],
                    ]);
                }

                $validatedData['options'] = json_encode($validatedData['options']);

                $validKeys = array_keys(json_decode($validatedData['options'], true));
                if (!in_array($validatedData['correct_option'], $validKeys)) {
                    throw ValidationException::withMessages([
                        'correct_option' => ['Doğru Cevap anahtarı girilen seçeneklerden biri olmalıdır.'],
                    ]);
                }
            } elseif ($validatedData['type'] === 'open_ended') {
                $validatedData['options'] = null;
            }

            // 3. Veritabanına Kaydetme
            // Not: Question modelinde $fillable içine 'points' eklediğinden emin ol!
            Question::create($validatedData);

            return redirect()
                ->route('staff.questions.index')
                ->with('success', 'Soru (' . $validatedData['points'] . ' Puan) başarıyla havuzuna eklendi.');

        } catch (ValidationException $e) {
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
