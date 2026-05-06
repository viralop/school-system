<?php

use App\Http\Controllers\Api\SectionApiController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SecureLinkController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\Supervisor\ContentManagementController;
use App\Http\Controllers\Supervisor\GradeController;
use App\Http\Controllers\Supervisor\MonthlyExamController;
use App\Http\Controllers\Supervisor\SectionManagementController;
use App\Http\Controllers\Supervisor\StudentManagementController;
use App\Http\Controllers\Supervisor\SubjectManagementController;
use App\Http\Controllers\Supervisor\TeacherManagementController;
use App\Http\Controllers\Supervisor\TermManagementController;
use App\Http\Controllers\SupervisorSetupController;
use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\TeacherGradeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

Route::get('/setup', [SupervisorSetupController::class, 'show'])->name('setup');
Route::post('/setup', [SupervisorSetupController::class, 'store'])->name('setup.store');

Route::get('/', function () {
    return view('visitor.home');
})->name('home');

Route::get('/login', function () {
    if (! \App\Models\User::where('role', 'supervisor')->exists()) {
        return redirect()->route('setup');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $user = \App\Models\User::where('email', $request->email)
        ->whereIn('role', ['supervisor', 'teacher', 'visitor'])
        ->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => 'Invalid credentials.',
        ]);
    }

    if ($user->isFrozen()) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => 'Your account has been frozen. Contact the supervisor.',
        ]);
    }

    auth()->login($user);

    if ($user->isSupervisor()) {
        return redirect()->route('supervisor.dashboard');
    }

    if ($user->isTeacher()) {
        return redirect()->route('teacher.dashboard');
    }

    return redirect()->route('home');
})->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'visitor',
        'status' => 'active',
    ]);

    auth()->login($user);

    return redirect()->route('home');
})->name('register.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::get('/about', function () {
    return view('visitor.about');
})->name('about');

Route::get('/achievements', function () {
    return view('visitor.achievements');
})->name('achievements');

Route::get('/api/sections', [SectionApiController::class, 'index']);

Route::middleware('secure.link')->group(function () {
    Route::get('/secure/{link}/{token}', [SecureLinkController::class, 'show'])->name('secure-link.show');
    Route::post('/secure/{link}/{token}', [SecureLinkController::class, 'register'])->name('secure-link.register');
});

Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/signup', [TeacherAuthController::class, 'showSignupForm'])->name('signup');
    Route::post('/signup', [TeacherAuthController::class, 'signupStep1'])->name('signup.step1');
    Route::get('/signup/password', [TeacherAuthController::class, 'showSetPasswordForm'])->name('signup.password');
    Route::post('/signup/password', [TeacherAuthController::class, 'setPassword'])->name('signup.password.store');
    Route::get('/login', [TeacherAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TeacherAuthController::class, 'loginStep1'])->name('login.step1');
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('logout');
});

Route::get('/forgot-password', [PasswordResetController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp'])->name('password.email');
Route::get('/forgot-password/otp', [PasswordResetController::class, 'showOtpForm'])->name('password.reset.otp');
Route::post('/forgot-password/otp', [PasswordResetController::class, 'verifyOtp'])->name('password.reset.verify');
Route::get('/forgot-password/new', [PasswordResetController::class, 'showNewPasswordForm'])->name('password.reset.new');
Route::post('/forgot-password/new', [PasswordResetController::class, 'setNewPassword'])->name('password.reset.store');
Route::post('/password-resend-otp', [PasswordResetController::class, 'resendOtp'])->name('password.resend');

Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
});

Route::middleware(['student.auth'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});

Route::middleware(['auth', 'not.frozen', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');

    Route::get('/teacher/grades', [TeacherGradeController::class, 'index'])->name('teacher.grades');
    Route::get('/teacher/grades/entry', [TeacherGradeController::class, 'showEntryForm'])->name('teacher.grades.entry');
    Route::post('/teacher/grades', [TeacherGradeController::class, 'store'])->name('teacher.grades.store');
});

Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/supervisor/dashboard', function () {
        return view('supervisor.dashboard');
    })->name('supervisor.dashboard');

    Route::prefix('supervisor/teachers')->name('supervisor.teachers.')->group(function () {
        Route::get('/', [TeacherManagementController::class, 'index'])->name('index');
        Route::post('/invite', [TeacherManagementController::class, 'invite'])->name('invite');
        Route::delete('/invite/{invite}', [TeacherManagementController::class, 'removeInvite'])->name('remove-invite');
        Route::patch('/{teacher}/freeze', [TeacherManagementController::class, 'freeze'])->name('freeze');
        Route::patch('/{teacher}/unfreeze', [TeacherManagementController::class, 'unfreeze'])->name('unfreeze');
        Route::delete('/{teacher}', [TeacherManagementController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('supervisor/students')->name('supervisor.students.')->group(function () {
        Route::get('/', [StudentManagementController::class, 'index'])->name('index');
        Route::post('/', [StudentManagementController::class, 'store'])->name('store');
        Route::put('/{student}', [StudentManagementController::class, 'update'])->name('update');
        Route::patch('/{student}/freeze', [StudentManagementController::class, 'freeze'])->name('freeze');
        Route::patch('/{student}/unfreeze', [StudentManagementController::class, 'unfreeze'])->name('unfreeze');
        Route::delete('/{student}', [StudentManagementController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('supervisor/sections')->name('supervisor.sections.')->group(function () {
        Route::get('/', [SectionManagementController::class, 'index'])->name('index');
        Route::post('/', [SectionManagementController::class, 'store'])->name('store');
        Route::delete('/{section}', [SectionManagementController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('supervisor/subjects')->name('supervisor.subjects.')->group(function () {
        Route::get('/', [SubjectManagementController::class, 'index'])->name('index');
        Route::post('/', [SubjectManagementController::class, 'store'])->name('store');
        Route::post('/import', [SubjectManagementController::class, 'import'])->name('import');
        Route::get('/{subject}/grades', [SubjectManagementController::class, 'grades'])->name('grades');
        Route::put('/{subject}', [SubjectManagementController::class, 'update'])->name('update');
        Route::delete('/{subject}', [SubjectManagementController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('supervisor/terms')->name('supervisor.terms.')->group(function () {
        Route::get('/', [TermManagementController::class, 'index'])->name('index');
        Route::post('/', [TermManagementController::class, 'store'])->name('store');
        Route::put('/{term}', [TermManagementController::class, 'update'])->name('update');
        Route::patch('/{term}/close', [TermManagementController::class, 'close'])->name('close');
        Route::patch('/{term}/open', [TermManagementController::class, 'open'])->name('open');
        Route::delete('/{term}', [TermManagementController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('supervisor/grades')->name('supervisor.grades.')->group(function () {
        Route::get('/pending', [GradeController::class, 'pending'])->name('pending');
        Route::post('/import', [GradeController::class, 'import'])->name('import');
        Route::patch('/{grade}/approve', [GradeController::class, 'approve'])->name('approve');
        Route::patch('/{grade}/reject', [GradeController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [GradeController::class, 'bulkApprove'])->name('bulk-approve');
    });

    Route::get('/supervisor/results', [GradeController::class, 'results'])->name('supervisor.results');

    Route::prefix('supervisor/monthly-exams')->name('supervisor.monthly-exams.')->group(function () {
        Route::get('/', [MonthlyExamController::class, 'index'])->name('index');
        Route::post('/', [MonthlyExamController::class, 'store'])->name('store');
        Route::put('/{monthlyExam}', [MonthlyExamController::class, 'update'])->name('update');
        Route::delete('/{monthlyExam}', [MonthlyExamController::class, 'destroy'])->name('destroy');
        Route::patch('/{monthlyExam}/close', [MonthlyExamController::class, 'close'])->name('close');
        Route::patch('/{monthlyExam}/open', [MonthlyExamController::class, 'open'])->name('open');
        Route::get('/{monthlyExam}/results', [MonthlyExamController::class, 'results'])->name('results');
        Route::get('/{monthlyExam}/review', [MonthlyExamController::class, 'review'])->name('review');
        Route::post('/{monthlyExam}/bulk-approve', [MonthlyExamController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/{monthlyExam}/bulk-reject', [MonthlyExamController::class, 'bulkReject'])->name('bulk-reject');
        Route::patch('/{monthlyExam}/approve/{grade}', [MonthlyExamController::class, 'approveGrade'])->name('approve-grade');
        Route::patch('/{monthlyExam}/reject/{grade}', [MonthlyExamController::class, 'rejectGrade'])->name('reject-grade');
    });

    Route::prefix('supervisor/content')->name('supervisor.content.')->group(function () {
        Route::get('/', [ContentManagementController::class, 'edit'])->name('edit');
        Route::put('/settings', [ContentManagementController::class, 'updateSettings'])->name('settings');
        Route::post('/achievements', [ContentManagementController::class, 'storeAchievement'])->name('achievements.store');
        Route::put('/achievements/{achievement}', [ContentManagementController::class, 'updateAchievement'])->name('achievements.update');
        Route::delete('/achievements/{achievement}', [ContentManagementController::class, 'destroyAchievement'])->name('achievements.destroy');
    });
});
