<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Staff\ExamController;
use App\Http\Controllers\Staff\QuestionCategoryController;
use App\Http\Controllers\Staff\QuestionController;
use App\Http\Controllers\Student\LiveExamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('',[\App\Http\Controllers\Controller::class,'home'])->name('home')->middleware(['auth', 'verified']);
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Çıkış İşlemi (Giriş Yapanlar için)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ROL BAZLI PANEL GRUPLARI (Erişim Kontrollü)
// Eğer tüm rotalarınız bu grupların içindeyse, başka bir şeye gerek yok.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.pages.admin.dashboard');
    })->name('dashboard');
});
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.pages.staff.dashboard');
    })->name('dashboard');
    Route::resource('exams', ExamController::class);
    Route::resource('questions', QuestionController::class);
    Route::resource('categories', QuestionCategoryController::class);
    Route::get('exams/{exam}/assign', [ExamController::class, 'assign'])->name('exams.assign');
    Route::post('exams/{exam}/assign', [ExamController::class, 'performAssignment'])->name('exams.perform-assignment');
});
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.pages.student.dashboard');
    })->name('dashboard');
    Route::get('exams', [\App\Http\Controllers\Student\ExamController::class, 'index'])->name('exams.index');
    Route::get('exams/show/{exam}', [\App\Http\Controllers\Student\ExamController::class, 'show'])->name('exams.show');
    Route::post('exams/{exam}/start', [\App\Http\Controllers\Student\ExamController::class, 'startSession'])->name('exams.start-session');
    Route::get('exam-live/{session}', [LiveExamController::class, 'show'])->name('exam-live');
    Route::post('exam-live/{session}/save-answer', [LiveExamController::class, 'saveAnswer'])->name('exam-save-answer');
    Route::post('exam-live/{session}/finish', [LiveExamController::class, 'finishExam'])->name('exams.finish');
    Route::get('exams/{exam}/results', [\App\Http\Controllers\Student\ExamController::class, 'results'])->name('exams.results');
});
