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
use App\Http\Controllers\Admin\StageController;
use App\Http\Controllers\Admin\SponsorshipPeriodController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\VoterMiddleware;
use App\Http\Middleware\CandidateMiddleware;

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

Auth::routes();

// Route de déconnexion personnalisée
Route::post('/logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Routes protégées
Route::middleware(['web', 'auth'])->group(function () {
    // Routes pour les électeurs
    Route::middleware([VoterMiddleware::class])->prefix('voter')->group(function () {
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
    Route::middleware([CandidateMiddleware::class])->prefix('candidate')->group(function () {
        Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('candidate.dashboard');
        Route::get('/sponsorships', [CandidateDashboardController::class, 'sponsorships'])->name('candidate.sponsorships');
        Route::get('/profile', [CandidateDashboardController::class, 'profile'])->name('candidate.profile');
        Route::put('/profile', [CandidateDashboardController::class, 'updateProfile'])->name('candidate.profile.update');
        Route::get('/register', [CandidateRegistrationController::class, 'showRegistrationForm'])->name('candidate.register');
        Route::post('/register', [CandidateRegistrationController::class, 'register']);
        Route::get('/report/download', [CandidateDashboardController::class, 'downloadReport'])->name('candidate.report.download');
    });

    // Routes pour l'administration
    Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth', \App\Http\Middleware\AdminMiddleware::class]], function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Gestion des périodes de stage
        Route::resource('stages', \App\Http\Controllers\Admin\StageController::class, ['as' => 'admin']);

        // Gestion des périodes de parrainage
        Route::resource('sponsorship-periods', SponsorshipPeriodController::class)->names([
            'index' => 'admin.sponsorship-periods.index',
            'create' => 'admin.sponsorship-periods.create',
            'store' => 'admin.sponsorship-periods.store',
            'edit' => 'admin.sponsorship-periods.edit',
            'update' => 'admin.sponsorship-periods.update',
            'destroy' => 'admin.sponsorship-periods.destroy',
        ]);
        Route::post('sponsorship-periods/{sponsorshipPeriod}/toggle-status', 
            [SponsorshipPeriodController::class, 'toggleStatus'])
            ->name('admin.sponsorship-periods.toggle-status');

        // Gestion des candidats
        Route::get('/candidates', [AdminCandidateController::class, 'index'])->name('admin.candidates.index');
        Route::get('/candidates/create', [AdminCandidateController::class, 'create'])->name('admin.candidates.create');
        Route::post('/candidates', [AdminCandidateController::class, 'store'])->name('admin.candidates.store');
        Route::get('/candidates/{candidate}', [AdminCandidateController::class, 'show'])->name('admin.candidates.show');
        Route::get('/candidates/{candidate}/edit', [AdminCandidateController::class, 'edit'])->name('admin.candidates.edit');
        Route::put('/candidates/{candidate}', [AdminCandidateController::class, 'update'])->name('admin.candidates.update');
        Route::delete('/candidates/{candidate}', [AdminCandidateController::class, 'destroy'])->name('admin.candidates.destroy');
        Route::post('/candidates/{candidate}/validate', [AdminCandidateController::class, 'validateCandidate'])->name('admin.candidates.validate');

        // Gestion des électeurs
        Route::get('/voters/import', [VoterImportController::class, 'showImportForm'])->name('admin.voters.import');
        Route::post('/voters/import', [VoterImportController::class, 'import'])->name('admin.voters.import.process');
        
        Route::resource('voters', VoterController::class, [
            'names' => [
                'index' => 'admin.voters.index',
                'create' => 'admin.voters.create',
                'store' => 'admin.voters.store',
                'show' => 'admin.voters.show',
                'edit' => 'admin.voters.edit',
                'update' => 'admin.voters.update',
                'destroy' => 'admin.voters.destroy',
            ],
            'as' => 'admin'
        ]);

        // Gestion des parrainages
        Route::prefix('sponsorships')->group(function () {
            Route::get('/', [AdminSponsorshipController::class, 'index'])->name('admin.sponsorships.index');
            Route::get('/{id}', [AdminSponsorshipController::class, 'show'])->name('admin.sponsorships.show');
            Route::post('/{id}/validate', [AdminSponsorshipController::class, 'validateSponsorship'])->name('admin.sponsorships.validate');
            Route::post('/{id}/reject', [AdminSponsorshipController::class, 'reject'])->name('admin.sponsorships.reject');
        });

        // Statistiques
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('admin.statistics');

        // Paramètres
        Route::group(['middleware' => [\App\Http\Middleware\SuperAdminMiddleware::class]], function () {
            Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
            Route::post('/settings/update-password', [SettingController::class, 'updatePassword'])->name('admin.settings.update-password');
        });
    });
});
