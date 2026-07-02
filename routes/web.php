<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\StudentRegisterController;
use App\Http\Controllers\Auth\MentorRegisterController;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MentorVerificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseApprovalController;

use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\Mentor\CourseController as MentorCourseController;
use App\Http\Controllers\Mentor\ExerciseController;
use App\Http\Controllers\Mentor\LessonController;
use App\Http\Controllers\Mentor\FinalProjectController;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\Mentor\FinanceController as MentorFinanceController;
use App\Http\Controllers\Mentor\SessionController;
use App\Http\Controllers\Mentor\StudentController;

use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\ExerciseController as StudentExerciseController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;

Route::get('/', fn() => redirect()->route('home'));

Route::get('/landing', [LandingController::class, 'index'])->name('home');
Route::get('/course', [LandingController::class, 'course'])->name('course');
Route::get('/course/{slug}', [StudentCourseController::class, 'show'])->name('course.show');
Route::post('/course/{slug}/enroll', [StudentCourseController::class, 'enroll'])->name('public.course.enroll')->middleware(['auth', 'role:student']);
Route::get('/category', [LandingController::class, 'category'])->name('category');
Route::get('/mentor', [LandingController::class, 'mentor'])->name('mentor');
Route::get('/choose-role', fn() => view('auth.choose_role'))->name('choose_role');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register/student', [StudentRegisterController::class, 'showForm'])->name('register.student');
    Route::post('/register/student', [StudentRegisterController::class, 'register'])->name('register.student.post');

    Route::get('/register/mentor', [MentorRegisterController::class, 'showForm'])->name('register.mentor');
    Route::post('/register/mentor', [MentorRegisterController::class, 'register'])->name('register.mentor.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $role = auth()->user()->role ?? 'student';

        return redirect()->route($role . '.dashboard');
    })->name('dashboard');

    Route::get('/settings', [ProfileController::class, 'edit'])
        ->name('settings.edit');

    Route::patch('/settings/profile', [ProfileController::class, 'update'])
        ->name('settings.profile.update');

    Route::put('/settings/password', [ProfileController::class, 'updatePassword'])
        ->name('settings.password.update');

    Route::patch('/settings/bank', [ProfileController::class, 'updateBank'])
        ->name('settings.bank.update');

    Route::delete('/settings/profile', [ProfileController::class, 'destroy'])
        ->name('settings.profile.destroy');

    Route::middleware('role:student')
        ->prefix('student')
        ->name('student.')
        ->group(function () {
            Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

            Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses');
            Route::get('/courses', [StudentCourseController::class, 'index'])->name('course.index');
            Route::get('/courses/{slug}', [StudentCourseController::class, 'show'])->name('course.show');
            Route::post('/courses/{slug}/enroll', [StudentCourseController::class, 'enroll'])->name('course.enroll');
            Route::get('/courses/{slug}/lesson', [StudentCourseController::class, 'showLesson'])->name('course.lesson');
            Route::get('/certif', [StudentCourseController::class, 'certificates'])->name('certif');
            Route::get('/certificate/{id}/show', [StudentCourseController::class, 'showCertificates'])->name('certificate.show');
            Route::get('/certificate/{id}/download', [StudentCourseController::class, 'downloadCertificate'])->name('certificate.download');
            Route::get('/courses/progress', [StudentCourseController::class, 'progress'])->name('progress');

            Route::get('/exercise/{exerciseId}', [StudentExerciseController::class, 'show'])->name('exercise.show');
            Route::post('/exercise/{exerciseId}', [StudentExerciseController::class, 'submit'])->name('exercise.submit');

            Route::get('/history', [StudentPaymentController::class, 'history'])->name('history');
            Route::get('/checkout/{course_id}', [StudentPaymentController::class, 'checkout'])->name('checkout');
            Route::get('/payment/success', [StudentPaymentController::class, 'successCallback'])->name('payment.success');

            Route::get('/settings', fn() => view('settings.settings'))->name('settings');
        });

    Route::middleware('role:mentor')
        ->prefix('mentor')
        ->name('mentor.')
        ->group(function () {
            Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');

            Route::get('/courses', [MentorCourseController::class, 'index'])->name('courses.index');
            Route::get('/courses/create', [MentorCourseController::class, 'create'])->name('courses.create');
            Route::post('/courses', [MentorCourseController::class, 'store'])->name('courses.store');
            Route::get('/courses/{course}/edit', [MentorCourseController::class, 'edit'])->name('courses.edit');
            Route::put('/courses/{course}', [MentorCourseController::class, 'update'])->name('courses.update');
            Route::patch('/courses/{course}/submit', [MentorCourseController::class, 'submit'])->name('courses.submit');
            Route::get('/courses/{course}', [MentorCourseController::class, 'show'])->name('courses.show');
            Route::patch('/courses/{course}/submit-verification', [MentorCourseController::class, 'submitVerification'])->name('courses.submitVerification');
            Route::delete('/courses/{course}', [MentorCourseController::class, 'destroy'])->name('courses.destroy');
            Route::get('/courses/{course}/sessions/edit', [SessionController::class, 'editByCourse'])->name('courses.sessions.edit');
            Route::put('/courses/{course}/sessions', [SessionController::class, 'updateByCourse'])->name('courses.sessions.update');
            Route::post('/courses/{course}/sessions', [SessionController::class, 'store'])->name('courses.sessions.store');
            Route::get('/sessions/{session}/lessons/create', [LessonController::class, 'create'])->name('sessions.lessons.create');
            Route::post('/sessions/{session}/lessons', [LessonController::class, 'store'])->name('sessions.lessons.store');
            Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
            Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
            Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
            Route::post('/sessions/{session}/exercises/create-empty', [ExerciseController::class, 'storeEmpty'])->name('sessions.exercises.storeEmpty');
            Route::post('/sessions/{session}/exercises', [ExerciseController::class, 'store'])->name('sessions.exercises.store');
            Route::get('/exercises/{exercise}/edit', [ExerciseController::class, 'edit'])->name('sessions.exercises.edit');
            Route::put('/exercises/{exercise}', [ExerciseController::class, 'update'])->name('sessions.exercises.update');
            Route::delete('/exercises/{exercise}', [ExerciseController::class, 'destroy'])->name('sessions.exercises.destroy');
            Route::get('/sessions/{session}/projects/create', [FinalProjectController::class, 'create'])->name('sessions.projects.create');
            Route::post('/sessions/{session}/projects', [FinalProjectController::class, 'store'])->name('sessions.projects.store');
            Route::delete('/projects/{finalProject}', [FinalProjectController::class, 'destroy'])->name('projects.destroy');

            Route::get('/student', [StudentController::class, 'index'])->name('student.index');

            Route::get('/finance', [MentorFinanceController::class, 'index'])->name('finance.index');
            Route::post('/payout-request', [MentorController::class, 'requestPayout'])->name('payout.request');

            Route::get('/settings', fn() => view('settings.settings'))->name('settings');
        });

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/verify-mentors', [MentorVerificationController::class, 'index'])->name('verify.mentors');
            Route::post('/mentor/{id}/approve', [MentorVerificationController::class, 'approve'])->name('mentor.approve');
            Route::post('/mentor/{id}/reject', [MentorVerificationController::class, 'reject'])->name('mentor.reject');
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::resource('categories', CategoryController::class);
            Route::get('/courses', [CourseApprovalController::class, 'index'])->name('courses.index');
            Route::get('/courses/{course}/detail', [CourseApprovalController::class, 'show'])->name('courses.show');
            Route::patch('/courses/{course}/approve', [CourseApprovalController::class, 'approve'])->name('courses.approve');
            Route::patch('/courses/{course}/reject', [CourseApprovalController::class, 'reject'])->name('courses.reject');
            Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

            Route::get('/settings', fn() => view('settings.settings'))->name('settings');
        });
});
            // Removed duplicated checkout view without course_id
Route::post('/api/midtrans/webhook', [App\Http\Controllers\Student\PaymentController::class, 'webhook'])->name('midtrans.webhook');
require __DIR__ . '/auth.php';
