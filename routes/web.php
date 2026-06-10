<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
Route::get('/specialties/{specialty}', [SpecialtyController::class, 'show'])->name('specialties.show');
Route::get('/resources', [PageController::class, 'resources'])->name('page.resources');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Named routes for Auth middleware redirects
Route::get('/login', function () {
    return redirect('/#login');
})->name('login');

Route::get('/register', function () {
    return redirect('/#register');
})->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dev route to auto-login
Route::get('/login-dev', function () {
    $user = User::firstOrCreate(
        ['email' => 'test@example.com'],
        ['name' => 'Test User', 'password' => bcrypt('password'), 'phone' => '1234567890']
    );
    Auth::login($user);
    return redirect('/applications');
});

Route::get('/applications/{id}/verify', [ApplicationController::class, 'verify'])->name('applications.verify');
Route::get('/applications/{application}/certificate', [ApplicationController::class, 'downloadCertificate'])->name('applications.certificate');

Route::middleware('auth')->group(function () {
    Route::get('/applications/enrollment', [ApplicationController::class, 'enrollment'])->name('applications.enrollment');
    Route::post('/applications/recalculate-ratings', [ApplicationController::class, 'recalculateRatings'])->name('applications.recalculate-ratings');
    Route::resource('applications', ApplicationController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Specialties (Dashboard)
    Route::get('/', [AdminController::class, 'index'])->name('specialties.index');
    Route::post('/specialties', [AdminController::class, 'storeSpecialty'])->name('specialties.store');
    Route::put('/specialties/{specialty}', [AdminController::class, 'updateSpecialty'])->name('specialties.update');
    Route::delete('/specialties/{specialty}', [AdminController::class, 'destroySpecialty'])->name('specialties.destroy');

    // Applications
    Route::get('/applications', [AdminController::class, 'applications'])->name('applications.index');
    Route::patch('/applications/{application}/status', [AdminController::class, 'updateApplicationStatus'])->name('applications.update-status');
    Route::patch('/applications/{application}/scores', [AdminController::class, 'updateApplicationScores'])->name('applications.update-scores');

    // Statistics
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics.index');
    // Enrollment boards
    Route::get('/enrollment', [AdminController::class, 'enrollmentBoards'])->name('enrollment.index');
    Route::post('/recalculate-ratings', [AdminController::class, 'recalculateRatings'])->name('recalculate-ratings');
});
