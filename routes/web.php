<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\SponsorshipController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SponsorshipPeriodController;
use Illuminate\Support\Facades\Auth;

Route::middleware(['web'])->group(function () {
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

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Redirection par défaut selon le type d'utilisateur
        Route::get('/dashboard', function () {
            if (auth()->check()) {
                $role = auth()->user()->role;
                
                switch ($role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'candidate':
                        return redirect()->route('candidate.dashboard');
                    case 'voter':
                        return redirect()->route('voter.dashboard');
                    default:
                        return redirect('/');
                }
            }
            
            return redirect('/');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Routes pour les électeurs
        Route::middleware(['auth', 'voter'])->group(function () {
            Route::get('/voter/dashboard', [VoterController::class, 'dashboard'])->name('voter.dashboard');
            Route::get('/voter/profile', [VoterController::class, 'profile'])->name('voter.profile');
            Route::put('/voter/profile', [VoterController::class, 'updateProfile'])->name('voter.update-profile');
            Route::post('/voter/sponsor', [VoterController::class, 'sponsor'])->name('voter.sponsor');
            Route::delete('/voter/sponsorship/{id}', [VoterController::class, 'cancelSponsorship'])->name('voter.cancel-sponsorship');
        });

        // Routes pour les candidats
        Route::middleware(['candidate'])->group(function () {
            Route::get('/candidate/dashboard', [CandidateController::class, 'dashboard'])->name('candidate.dashboard');
            Route::get('/candidate/profile', [CandidateController::class, 'profile'])->name('candidate.profile');
            Route::post('/candidate/profile/update', [CandidateController::class, 'updateProfile'])->name('candidate.profile.update');
            Route::get('/candidate/sponsorships', [CandidateController::class, 'sponsorships'])->name('candidate.sponsorships');
            Route::get('/candidate/statistics', [CandidateController::class, 'statistics'])->name('candidate.statistics');
        });

        // Routes pour les parrainages
        Route::middleware(['auth'])->group(function () {
            Route::get('/sponsorship/upload', [SponsorshipController::class, 'showUploadForm'])->name('sponsorship.upload');
            Route::post('/sponsorship/import', [SponsorshipController::class, 'import'])->name('sponsorship.import');
            Route::get('/sponsorship/template', [SponsorshipController::class, 'downloadTemplate'])->name('sponsorship.template');
            Route::get('/sponsorship', [SponsorshipController::class, 'index'])->name('sponsorship.index');
            
            // Validation des parrainages (admin seulement)
            Route::middleware(['admin'])->group(function () {
                Route::post('/sponsorship/{sponsorship}/validate', [SponsorshipController::class, 'validateSponsorship'])->name('admin.sponsorships.validate');
                Route::post('/sponsorship/{sponsorship}/reject', [SponsorshipController::class, 'rejectSponsorship'])->name('admin.sponsorships.reject');
            });
        });

        // Routes d'administration
        Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function () {
            // Tableau de bord
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            
            // Gestion du fichier électoral
            Route::get('/electoral-file', [AdminController::class, 'electoralFile'])->name('electoral-file');
            Route::post('/electoral-file/upload', [AdminController::class, 'uploadElectoralFile'])->name('electoral-file.upload');
            
            // Gestion des utilisateurs
            Route::resource('users', UserController::class);
            
            // Gestion des périodes de parrainage
            Route::resource('sponsorship-periods', SponsorshipPeriodController::class);
            
            // Gestion des parrainages
            Route::resource('sponsorships', SponsorshipController::class);
        });

        // Routes pour les candidats
        Route::prefix('candidate')->middleware(['candidate'])->name('candidate.')->group(function () {
            Route::get('/dashboard', function () {
                return view('candidate.dashboard');
            })->name('dashboard');
        });

        // Routes pour les électeurs
        Route::prefix('voter')->middleware(['voter'])->name('voter.')->group(function () {
            Route::get('/dashboard', function () {
                return view('voter.dashboard');
            })->name('dashboard');
        });
    });
});

require __DIR__.'/auth.php';
