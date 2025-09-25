<?php

use App\Http\Controllers\MembersConroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CoachesController;
use App\Http\Controllers\SessionsController;
use App\Models\User;
use App\Models\Coach;
use App\Models\TrainingSession;

Route::get('/', function () { return view('auth.login'); })->name('root');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected area
Route::middleware('auth.session')->group(function () {
    // Dashboard (live metrics)
    Route::get('/dashboard', function () {
        $membersCount = User::where('role', 'membre')->count();
        $sessionsToday = TrainingSession::whereDate('date_time', now()->toDateString())->count();
        $sessions30d = TrainingSession::where('date_time', '>=', now()->subDays(30))->count();
        $coachesCount = Coach::count();
        $upcomingSessions = TrainingSession::with(['coach.user','user'])
            ->where('date_time', '>=', now())
            ->orderBy('date_time')
            ->limit(5)
            ->get();
        return view('dashboard', compact('membersCount','sessionsToday','sessions30d','coachesCount','upcomingSessions'));
    })->name('dashboard');

    // Backoffice sections (views for now)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/create', [UsersController::class, 'create'])->name('create');
        Route::post('/', [UsersController::class, 'store'])->name('store');
        Route::get('/{user}', [UsersController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UsersController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UsersController::class, 'update'])->name('update');
        Route::delete('/{user}', [UsersController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('members')->name('members.')->group(function () {
         Route::get('/', [MembersConroller::class, 'index'])->name('index');
        Route::get('/create', [MembersConroller::class, 'create'])->name('create');
        Route::post('/', [MembersConroller::class, 'store'])->name('store');
        Route::get('/{member}', [MembersConroller::class, 'show'])->name('show');
        Route::get('/{member}/edit', [MembersConroller::class, 'edit'])->name('edit');
        Route::put('/{member}', [MembersConroller::class, 'update'])->name('update');
        Route::delete('/{member}', [MembersConroller::class, 'destroy'])->name('destroy');
    });

    Route::prefix('sessions')->name('sessions.')->group(function () {
        Route::get('/', [SessionsController::class, 'index'])->name('index');
        Route::get('/create', [SessionsController::class, 'create'])->name('create');
        Route::post('/', [SessionsController::class, 'store'])->name('store');
        Route::get('/{session}/edit', [SessionsController::class, 'edit'])->name('edit');
        Route::put('/{session}', [SessionsController::class, 'update'])->name('update');
        Route::get('/{session}', [SessionsController::class, 'show'])->name('show');
        Route::delete('/{session}', [SessionsController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', fn() => view('payments.index'))->name('index');
    });

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', fn() => view('subscriptions.index'))->name('index');
    });

    Route::prefix('coaches')->name('coaches.')->group(function () {
        Route::get('/', [CoachesController::class, 'index'])->name('index');
        Route::get('/create', [CoachesController::class, 'create'])->name('create');
        Route::post('/', [CoachesController::class, 'store'])->name('store');
        Route::get('/{coach}/edit', [CoachesController::class, 'edit'])->name('edit');
        Route::put('/{coach}', [CoachesController::class, 'update'])->name('update');
        Route::get('/{coach}', [CoachesController::class, 'show'])->name('show');
        Route::delete('/{coach}', [CoachesController::class, 'destroy'])->name('destroy');
    });
});
