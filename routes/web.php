<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\SponsorshipController;
use App\Http\Controllers\CandidateRegistrationController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\RegisterTypeController;
use App\Http\Controllers\VoterDashboardController;
use App\Http\Controllers\CandidateDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCandidateController;
use App\Http\Controllers\Admin\AdminSponsorshipController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\VoterImportController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;

// Page d'accueil simple sans Vite
Route::get('/', function () {
    return view('welcome');
});

// Routes pour voir les différents designs
Route::get('/welcome1', function () {
    return view('welcome');
});

Route::get('/welcome2', function () {
    return view('welcome2');
});

Route::get('/welcome3', function () {
    return view('welcome3');
});

Route::get('/welcome-new', function () {
    return view('welcome_new');
});

Route::get('/welcome-simple', function () {
    return view('welcome_simple');
});

// Routes temporaires pour voir les différents designs
Route::get('/design1', function () {
    return view('welcome');
});

Route::get('/design2', function () {
    return view('welcome2');
});

Route::get('/design3', function () {
    return view('welcome3');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomLoginController::class, 'login']);
    
    // Registration type selection
    Route::get('/register', [RegisterTypeController::class, 'showTypeSelection'])->name('register');
    
    // Voter registration
    Route::get('/register/voter', [RegisterTypeController::class, 'showVoterForm'])->name('register.voter');
    Route::post('/register/voter', [RegisterTypeController::class, 'registerVoter'])->name('register.voter.submit');
    
    // Candidate registration
    Route::get('/register/candidate', [RegisterTypeController::class, 'showCandidateForm'])->name('register.candidate');
    Route::post('/register/candidate', [RegisterTypeController::class, 'registerCandidate'])->name('register.candidate.submit');
});

Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout')->middleware('auth');

// Route de redirection après connexion
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes protégées
Route::middleware(['web', 'auth'])->group(function () {
    // Routes pour les électeurs
    Route::group([
        'prefix' => 'voter',
        'middleware' => [\App\Http\Middleware\VoterMiddleware::class]
    ], function () {
        Route::get('/dashboard', [VoterDashboardController::class, 'index'])->name('voter.dashboard');
        Route::get('/profile', [VoterDashboardController::class, 'profile'])->name('voter.profile');
        Route::put('/profile', [VoterDashboardController::class, 'updateProfile'])->name('voter.profile.update');
        Route::get('/candidates', [VoterDashboardController::class, 'candidates'])->name('voter.candidates.index');
        Route::get('/candidates/{id}', [AdminCandidateController::class, 'voterShow'])->name('voter.candidates.show');
        
        // Routes pour les parrainages
        Route::get('/sponsorships/create/{candidate}', [SponsorshipController::class, 'create'])->name('voter.sponsorships.create');
        Route::post('/sponsorships/{candidate}', [SponsorshipController::class, 'store'])->name('voter.sponsorships.store');
        Route::get('/sponsorships', [SponsorshipController::class, 'index'])->name('voter.sponsorships.index');
        Route::get('/sponsorships/{id}', [SponsorshipController::class, 'show'])->name('voter.sponsorships.show');
        Route::post('/sponsor/{candidate}', [SponsorshipController::class, 'sponsor'])->name('voter.sponsor');
        
        // Gestion des parrainages
        Route::prefix('sponsorships')->name('sponsorships.')->group(function () {
            Route::get('/create/{candidate_id}', [SponsorshipController::class, 'create'])->name('create');
            Route::post('/store', [SponsorshipController::class, 'store'])->name('store');
        });
    });

    // Routes pour les candidats
    Route::group([
        'prefix' => 'candidate',
        'middleware' => [\App\Http\Middleware\CandidateMiddleware::class]
    ], function () {
        Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('candidate.dashboard');
        Route::get('/sponsorships', [CandidateDashboardController::class, 'sponsorships'])->name('candidate.sponsorships');
        Route::get('/profile', [CandidateDashboardController::class, 'profile'])->name('candidate.profile');
        Route::put('/profile', [CandidateDashboardController::class, 'updateProfile'])->name('candidate.profile.update');
        Route::get('/register', [CandidateRegistrationController::class, 'showRegistrationForm'])->name('candidate.register');
        Route::post('/register', [CandidateRegistrationController::class, 'register']);
        Route::get('/report/download', [CandidateDashboardController::class, 'downloadReport'])->name('candidate.report.download');
    });

    // Routes pour les administrateurs
    Route::middleware(['auth', 'admin'])->group(function () {
        // Redirection de /admin vers /admin/dashboard
        Route::get('/admin', function () {
            return redirect()->route('admin.dashboard');
        });
        
        Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Routes pour les candidats
        Route::get('/admin/candidates', [\App\Http\Controllers\Admin\AdminCandidateController::class, 'index'])->name('admin.candidates.index');
        Route::get('/admin/candidates/{candidate}', [\App\Http\Controllers\Admin\AdminCandidateController::class, 'show'])->name('admin.candidates.show');
        Route::post('/admin/candidates/{candidate}/validate', [\App\Http\Controllers\Admin\AdminCandidateController::class, 'validate'])->name('admin.candidates.validate');
        Route::post('/admin/candidates/{candidate}/reject', [\App\Http\Controllers\Admin\AdminCandidateController::class, 'reject'])->name('admin.candidates.reject');
        
        // Routes pour les parrainages
        Route::get('/admin/sponsorships', [\App\Http\Controllers\Admin\AdminSponsorshipController::class, 'index'])->name('admin.sponsorships.index');
        Route::post('/admin/sponsorships/{sponsorship}/validate', [\App\Http\Controllers\Admin\AdminSponsorshipController::class, 'validate'])->name('admin.sponsorships.validate');
        Route::post('/admin/sponsorships/{sponsorship}/reject', [\App\Http\Controllers\Admin\AdminSponsorshipController::class, 'reject'])->name('admin.sponsorships.reject');
    });
});
