<?php

use App\Http\Controllers\Admin\MasterMaterialController;
use App\Http\Controllers\Admin\MasterPackegeController;
use App\Http\Controllers\Admin\MappingQuestionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MasterTypesController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\BankQuestionController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('master-types', MasterTypesController::class);
    Route::resource('master-packages', MasterPackegeController::class);
    Route::get('master-materials/{id}/preview', [MasterMaterialController::class, 'serveFile'])
        ->name('master-materials.preview');
    Route::get('master-materials/{id}/download', [MasterMaterialController::class, 'downloadFile'])
        ->name('master-materials.download');
    Route::resource('master-materials', MasterMaterialController::class);

    // Exam routes
    Route::resource('exams', ExamController::class);
    Route::get('mapping-questions', [MappingQuestionController::class, 'indexMappingQuestion'])
        ->name('mapping-questions.index');
    Route::get('mapping-questions/create', [MappingQuestionController::class, 'create'])
        ->name('mapping-questions.create');
    Route::get('exams/{exam}/mapping-questions', [MappingQuestionController::class, 'index'])
        ->name('mapping-questions.manage');
    Route::post('exams/{exam}/mapping-questions', [MappingQuestionController::class, 'store'])
        ->name('mapping-questions.store');
    Route::post('exams/{exam}/mapping-questions/random', [MappingQuestionController::class, 'random'])
        ->name('mapping-questions.random');
    Route::get('exams/{exam}/mapping-questions/{mapping}', [MappingQuestionController::class, 'show'])
        ->name('mapping-questions.show');
    Route::delete('exams/{exam}/mapping-questions/{mapping}', [MappingQuestionController::class, 'destroy'])
        ->name('mapping-questions.destroy');

    // Bank Question routes
    Route::get('bank-questions/download-template', [BankQuestionController::class, 'downloadTemplate'])
        ->name('bank-questions.download-template');
    Route::post('bank-questions/import', [BankQuestionController::class, 'import'])
        ->name('bank-questions.import');
    Route::resource('bank-questions', BankQuestionController::class);
});

require __DIR__ . '/auth.php';
