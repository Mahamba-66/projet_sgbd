<?php
// Ajouté le 03/19/2025 04:27:32
// feat: Nouvelles routes pour le parrainage

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\SponsorshipController;
use App\Http\Controllers\RegionController;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes protégées par authentification
Route::middleware(['auth', 'status'])->group(function () {
    // Vérification email
    Route::prefix('email')->group(function () {
        Route::get('/verify', [AuthController::class, 'verificationNotice'])->name('verification.notice');
        Route::post('/verify', [AuthController::class, 'verifyEmail'])->name('verification.verify');
        Route::post('/resend', [AuthController::class, 'resendVerificationCode'])->name('verification.resend');
    });

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes pour les administrateurs
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/sponsorships', [AdminController::class, 'sponsorships'])->name('sponsorships');
        Route::get('/stats', [AdminController::class, 'stats'])->name('stats');
        
        // Gestion des régions
        Route::resource('regions', RegionController::class);
        
        // Validation des candidats
        Route::patch('/candidates/{user}/validate', [AdminController::class, 'validateCandidate'])->name('candidates.validate');
        Route::patch('/candidates/{user}/reject', [AdminController::class, 'rejectCandidate'])->name('candidates.reject');
    });

    // Routes pour les électeurs
    Route::middleware('voter')->prefix('voter')->name('voter.')->group(function () {
        Route::get('/dashboard', [VoterController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [VoterController::class, 'profile'])->name('profile');
        Route::patch('/profile', [VoterController::class, 'updateProfile'])->name('profile.update');
        
        // Parrainages
        Route::get('/sponsorships', [SponsorshipController::class, 'index'])->name('sponsorships.index');
        Route::get('/sponsorships/create', [SponsorshipController::class, 'create'])->name('sponsorships.create');
        Route::post('/sponsorships', [SponsorshipController::class, 'store'])->name('sponsorships.store');
        Route::get('/sponsorships/{sponsorship}', [SponsorshipController::class, 'show'])->name('sponsorships.show');
    });

    // Routes pour les candidats
    Route::middleware('candidate')->prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CandidateController::class, 'profile'])->name('profile');
        Route::patch('/profile', [CandidateController::class, 'updateProfile'])->name('profile.update');
        Route::get('/sponsorships', [CandidateController::class, 'sponsorships'])->name('sponsorships');
        Route::get('/stats', [CandidateController::class, 'stats'])->name('stats');
    });
});
