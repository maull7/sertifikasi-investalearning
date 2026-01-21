<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\User\MyPackageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ShowGradeController;
use App\Http\Controllers\User\HistoryExamController;
use App\Http\Controllers\Admin\MasterTypesController;
use App\Http\Controllers\Admin\BankQuestionController;
use App\Http\Controllers\Admin\MasterPackegeController;
use App\Http\Controllers\Admin\MasterMaterialController;
use App\Http\Controllers\Admin\MappingQuestionController;
use App\Http\Controllers\User\ExamController as UserExamController;
use App\Http\Controllers\User\PackageController as UserPackageController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;


Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('user.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Package routes
    Route::get('user/packages', [UserPackageController::class, 'index'])->name('user.packages.index');
    Route::get('user/packages/{package}', [UserPackageController::class, 'show'])->name('user.packages.show');
    Route::post('user/packages/{package}/join', [UserPackageController::class, 'join'])->name('user.packages.join');

    // My Packages routes
    Route::get('user/my-packages', [MyPackageController::class, 'index'])->name('user.my-packages.index');
    Route::get('user/my-packages/{package}', [MyPackageController::class, 'show'])->name('user.my-packages.show');

    // User Exam routes
    Route::get('user/packages/{package}/exams/{exam}', [UserExamController::class, 'show'])->name('user.exams.show');
    Route::get('user/packages/{package}/exams/{exam}/questions', [UserExamController::class, 'getQuestions'])->name('user.exams.questions');
    Route::post('user/packages/{package}/exams/{exam}/submit', [UserExamController::class, 'submit'])->name('user.exams.submit');
    // Material preview & download (accessible for users who joined the package)
    Route::get('master-materials/{id}/preview', [MasterMaterialController::class, 'serveFile'])
        ->name('master-materials.preview');
    Route::get('master-materials/{id}/download', [MasterMaterialController::class, 'downloadFile'])
        ->name('master-materials.download');

    // History Exam routes
    Route::get('user/history-exams', [HistoryExamController::class, 'index'])
        ->name('user.history-exams.index');
    Route::get('user/history-exams/{id}/detail', [HistoryExamController::class, 'detail'])
        ->name('user.history-exams.detail');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('master-types/import', [MasterTypesController::class, 'ImportTemplate'])->name('master-types.import');
    Route::get('master-types/export-template', [MasterTypesController::class, 'ExportTemplate'])->name('master-types.export-template');
    Route::resource('master-types', MasterTypesController::class);

    Route::post('master-packages/import', [MasterPackegeController::class, 'importPackage'])
        ->name('master-packages.import');
    Route::get('master-packages/download-template', [MasterPackegeController::class, 'DownloadTemplate'])
        ->name('master-packages.download-template');
    Route::resource('master-packages', MasterPackegeController::class);

    Route::resource('master-materials', MasterMaterialController::class);

    Route::post('subjects/import', [SubjectController::class, 'ImportExcel'])
        ->name('subjects.import');
    Route::get('subjects/template-export', [SubjectController::class, 'TemplateExport'])
        ->name('subjects.template-export');
    Route::resource('subjects', SubjectController::class);

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


    // Show Grade routes
    Route::get('show-grades', [ShowGradeController::class, 'index'])
        ->name('show-grades.index');
    Route::get('show-grades/{id}/detail', [ShowGradeController::class, 'detail'])
        ->name('show-grades.detail');
});

require __DIR__ . '/auth.php';
