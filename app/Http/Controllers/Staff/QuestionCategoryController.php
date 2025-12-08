<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\QuestionCategory;
use Illuminate\Http\Request;

class QuestionCategoryController extends Controller
{
    /**
     * Kategorileri listeleme.
     */
    public function index()
    {
        $categories = QuestionCategory::latest()->get();
        return view('backend.pages.staff.categories.index', compact('categories'));
    }

    /**
     * Kategori oluşturma formunu gösterir.
     */
    public function create()
    {
        return view('backend.pages.staff.categories.create');
    }

    /**
     * Yeni kategoriyi kaydeder.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:question_categories,name',
            // 'slug' alanı migration'da yoksa name yeterli.
        ]);

        QuestionCategory::create($validatedData);

        return redirect()
            ->route('staff.categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    // Edit, Update ve Destroy metotları da benzer şekilde tamamlanmalıdır.
    // Şimdilik sadece index ve store ile devam edelim, böylece sorulara kategori atayabiliriz.

    public function edit(QuestionCategory $category)
    {
        return view('backend.pages.staff.categories.edit', compact('category'));
    }

    public function update(Request $request, QuestionCategory $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:question_categories,name,' . $category->id,
        ]);

        $category->update($validatedData);

        return redirect()
            ->route('staff.categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy(QuestionCategory $category)
    {
        $category->delete();
        return redirect()->route('staff.categories.index')->with('success', 'Kategori silindi.');
    }
}
