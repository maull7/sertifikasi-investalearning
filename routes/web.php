<?php

use App\Http\Controllers\Admin\ApprovePackageController;
use App\Http\Controllers\Admin\BankQuestionController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailActivation;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\MappingPackageController;
use App\Http\Controllers\Admin\MappingQuestionController;
use App\Http\Controllers\Admin\MasterMaterialController;
use App\Http\Controllers\Admin\MasterPackegeController;
use App\Http\Controllers\Admin\MasterTypesController;
use App\Http\Controllers\Admin\MasterUserController;
use App\Http\Controllers\Admin\ParticipantMonitorController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\ShowGradeController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CertificateControlller;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ExamController as UserExamController;
use App\Http\Controllers\User\HistoryExamController;
use App\Http\Controllers\User\MyPackageController;
use App\Http\Controllers\User\PackageController as UserPackageController;
use App\Http\Controllers\User\QuizController as UserQuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('home')->middleware('check-login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// Public certificate verification
Route::get('certificates/{certificate}/verify', [CertificateController::class, 'verify'])
    ->name('certificates.verify');

Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('user.dashboard');
Route::get('/user/dashboard/chart-data', [UserDashboardController::class, 'getChartData'])
    ->middleware(['auth', 'verified'])
    ->name('user.dashboard.chart-data');
// Chart Data API
Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

// routes user
Route::middleware('auth', 'akun-active')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/complete', [ProfileCompletionController::class, 'show'])->name('profile.complete');
    Route::patch('/profile/complete', [ProfileCompletionController::class, 'update'])->name('profile.complete.update');

    // landing
    Route::get('/user/landing', [UserPackageController::class, 'landing'])->name('user.landing');

    // User Package routes
    Route::get('user/packages', [UserPackageController::class, 'index'])->name('user.packages.index');
    Route::get('user/packages/{package}', [UserPackageController::class, 'show'])->name('user.packages.show');
    Route::post('user/packages/{package}/join', [UserPackageController::class, 'join'])->name('user.packages.join');

    // My Packages routes
    Route::get('user/my-packages', [MyPackageController::class, 'index'])->name('user.my-packages.index');
    Route::get('user/my-packages/{package}', [MyPackageController::class, 'show'])->name('user.my-packages.show');
    Route::post('user/my-packages/{material}/mark-as-read', [MyPackageController::class, 'markAsRead'])->name('user.mark-as-read');

    // User Exam routes
    Route::get('user/packages/{package}/exams/{exam}', [UserExamController::class, 'show'])->name('user.exams.show');
    Route::get('user/packages/{package}/exams/{exam}/questions', [UserExamController::class, 'getQuestions'])->name('user.exams.questions');
    Route::post('user/packages/{package}/exams/{exam}/submit', [UserExamController::class, 'submit'])->name('user.exams.submit');
    Route::get('user/packages/{package}/exams/{exam}/attempts', [UserExamController::class, 'attempts'])->name('user.exams.attempts');
    Route::get('user/packages/{package}/exams/{exam}/result/{trans}', [UserExamController::class, 'result'])->name('user.exams.result');
    Route::get('user/packages/{package}/exams/{exam}/review/{trans}', [UserExamController::class, 'review'])->name('user.exams.review');

    // User Quiz routes
    Route::get('user/packages/{package}/quizzes/{quiz}/subjects/{subject}', [UserQuizController::class, 'show'])->name('user.quizzes.show');
    Route::get('user/packages/{package}/quizzes/{quiz}/questions', [UserQuizController::class, 'getQuestionsWithSubject'])->name('user.quizzes.questions');
    Route::post('user/packages/{package}/quizzes/{quiz}/submit', [UserQuizController::class, 'submit'])->name('user.quizzes.submit');
    Route::get('user/packages/{package}/quizzes/{quiz}/review/{transQuiz}', [UserQuizController::class, 'review'])->name('user.quizzes.review');
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

    // Certificate User
    Route::get('user/my-certificate', [CertificateControlller::class, 'index'])->name('user.certificate.index');
    Route::get('user/my-certificate/{certificate}', [CertificateControlller::class, 'detail'])->name('user.certificate.show');
    Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])
        ->name('certificates.download');
});

// routes admin (Admin + Petugas)
Route::middleware(['auth', 'admin'])->group(function () {
    // Master User (hanya Admin)
    Route::middleware('admin-only')->group(function () {
        Route::get('master-user', [MasterUserController::class, 'index'])->name('master-user.index');
        Route::get('master-user/create', [MasterUserController::class, 'create'])->name('master-user.create');
        Route::post('master-user', [MasterUserController::class, 'store'])->name('master-user.store');
        Route::get('master-user/{user}/edit', [MasterUserController::class, 'edit'])->name('master-user.edit');
        Route::put('master-user/{user}', [MasterUserController::class, 'update'])->name('master-user.update');
        Route::delete('master-user/{user}', [MasterUserController::class, 'destroy'])->name('master-user.destroy');
    });

    // Master & Pelatihan (hanya Admin)
    Route::middleware('admin-only')->group(function () {
        Route::post('master-types/import', [MasterTypesController::class, 'ImportTemplate'])->name('master-types.import');
        Route::get('master-types/export-template', [MasterTypesController::class, 'ExportTemplate'])->name('master-types.export-template');
        Route::resource('master-types', MasterTypesController::class);

        Route::post('master-packages/import', [MasterPackegeController::class, 'importPackage'])
            ->name('master-packages.import');
        Route::get('master-packages/download-template', [MasterPackegeController::class, 'DownloadTemplate'])
            ->name('master-packages.download-template');
        Route::patch('master-packages/{package}/toggle-active', [MasterPackegeController::class, 'toggleActive'])
            ->name('master-packages.toggle-active');
        Route::resource('master-packages', MasterPackegeController::class);
        Route::get('mapping-package', [MappingPackageController::class, 'index'])->name('mapping-package.index');
        Route::get('mapping-package/create', [MappingPackageController::class, 'create'])->name('mapping-package.create');
        Route::get('master-packages/{package}/mapping-package', [MappingPackageController::class, 'manage'])
            ->name('mapping-package.manage');
        Route::post('master-packages/{package}/mapping-package', [MappingPackageController::class, 'store'])
            ->name('mapping-package.store');
        Route::delete('master-packages/{package}/mapping-package/{subject}', [MappingPackageController::class, 'destroy'])
            ->name('mapping-package.destroy');

        Route::resource('master-materials', MasterMaterialController::class);

        Route::post('subjects/import', [SubjectController::class, 'ImportExcel'])
            ->name('subjects.import');
        Route::get('subjects/template-export', [SubjectController::class, 'TemplateExport'])
            ->name('subjects.template-export');
        Route::resource('subjects', SubjectController::class);

        Route::get('exams/subjects-by-package/{package}', [ExamController::class, 'subjectsByPackage'])
            ->name('exams.subjects-by-package');
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

        Route::get('bank-questions/download-template', [BankQuestionController::class, 'downloadTemplate'])
            ->name('bank-questions.download-template');
        Route::post('bank-questions/import', [BankQuestionController::class, 'import'])
            ->name('bank-questions.import');
        Route::resource('bank-questions', BankQuestionController::class);
    });

    // Show Grade routes
    Route::get('show-grades', [ShowGradeController::class, 'index'])
        ->name('show-grades.index');
    Route::get('show-grades/{id}/detail', [ShowGradeController::class, 'detail'])
        ->name('show-grades.detail');

    // USER AKTIVASI
    Route::get('user-not-activation', [EmailActivation::class, 'index'])->name('user.not.active');
    Route::get('admin/unverified-users-count', [EmailActivation::class, 'unverifiedCount'])->name('admin.unverified-count');
    Route::patch('users/{user}/activate', [EmailActivation::class, 'activate'])
        ->name('user.activate');

    // Teacher, Quizzes, Books (hanya Admin)
    Route::middleware('admin-only')->group(function () {
        Route::resource('teacher', TeacherController::class);
        Route::resource('quizzes', QuizController::class);
        Route::resource('books', BookController::class);
    });

    // Certificates
    Route::get('/get-package/{type}', [CertificateController::class, 'getPackage'])->name('get-package.type');

    Route::resource('certificates', CertificateController::class)->only(['index', 'create', 'store', 'show']);

    Route::get('admin/pending-packages-count', [ApprovePackageController::class, 'pendingCount'])
        ->name('admin.pending-packages-count');
    Route::get('approve-packages', [ApprovePackageController::class, 'index'])
        ->name('approve-packages.index');
    Route::get('approve-packages/packages/{package}', [ApprovePackageController::class, 'showPackage'])
        ->name('approve-packages.package.show');
    Route::patch('approve-packages/{userJoin}/approve', [ApprovePackageController::class, 'approve'])
        ->name('approve-packages.approve');
    Route::patch('approve-packages/{userJoin}/reject', [ApprovePackageController::class, 'reject'])
        ->name('approve-packages.reject');

    // Monitor Peserta
    Route::get('monitor-participants', [ParticipantMonitorController::class, 'index'])
        ->name('monitor-participants.index');
    Route::get('monitor-participants/package/{package}', [ParticipantMonitorController::class, 'participants'])
        ->name('monitor-participants.package');
    Route::get('monitor-participants/package/{package}/export', [ParticipantMonitorController::class, 'exportPackage'])
        ->name('monitor-participants.package.export');
    Route::get('monitor-participants/{userJoin}/tryout/{transQuestion}', [ParticipantMonitorController::class, 'tryoutDetail'])
        ->name('monitor-participants.tryout-detail');
    Route::get('monitor-participants/{userJoin}/export', [ParticipantMonitorController::class, 'exportParticipant'])
        ->name('monitor-participants.export');
    Route::get('monitor-participants/{userJoin}', [ParticipantMonitorController::class, 'show'])
        ->name('monitor-participants.show');
});

require __DIR__.'/auth.php';
