@extends('layouts.app')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(to right, rgba(0, 0, 0, 0.8), rgba(0, 132, 61, 0.8)), url('https://www.senenews.com/wp-content/uploads/2024/02/Palais-de-la-Republique-Senegal.jpg');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .animated-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, #00843D, #006B31);
        opacity: 0.1;
        animation: pulse 3s infinite;
    }

    @keyframes pulse {
        0% { opacity: 0.1; }
        50% { opacity: 0.2; }
        100% { opacity: 0.1; }
    }

    .feature-card {
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
        background: linear-gradient(135deg, #00843D, #006B31);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .progress-bar {
        height: 6px;
        background: #E5E7EB;
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(to right, #00843D, #006B31);
        border-radius: 3px;
        transition: width 1s ease;
    }
</style>
@endsection

@section('content')
<div class="hero-section flex items-center">
    <div class="animated-bg"></div>
    <div class="container mx-auto px-4 hero-content">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <h1 class="text-6xl font-bold mb-6">Parrainage Électoral du Sénégal</h1>
                <p class="text-xl mb-8">Votre voix compte. Participez au processus démocratique de manière transparente et sécurisée.</p>
                <div class="flex space-x-4">
                    <a href="{{ route('register') }}" class="bg-white text-green-600 px-8 py-4 rounded-full font-bold hover:bg-gray-100 transition transform hover:scale-105">
                        Commencer Maintenant
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-4 rounded-full font-bold hover:bg-white hover:text-green-600 transition transform hover:scale-105">
                        Se Connecter
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <img src="https://www.gouv.sn/assets/images/symbols/emblem.png" alt="Emblème du Sénégal" class="w-64 mx-auto">
            </div>
        </div>
    </div>
</div>

<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Processus Simplifié</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Notre plateforme rend le processus de parrainage simple, sécurisé et accessible à tous les citoyens.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="feature-card">
                <div class="p-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-user-plus text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Création de Compte</h3>
                    <p class="text-gray-600">Inscrivez-vous avec vos informations d'électeur pour commencer.</p>
                </div>
                <div class="bg-green-50 p-4">
                    <div class="progress-bar">
                        <div class="progress-bar-fill" style="width: 33%"></div>
                    </div>
                    <p class="text-sm text-green-600 mt-2">Étape 1/3</p>
                </div>
            </div>

            <div class="feature-card">
                <div class="p-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-id-card text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Vérification</h3>
                    <p class="text-gray-600">Validation automatique de votre identité et éligibilité.</p>
                </div>
                <div class="bg-green-50 p-4">
                    <div class="progress-bar">
                        <div class="progress-bar-fill" style="width: 66%"></div>
                    </div>
                    <p class="text-sm text-green-600 mt-2">Étape 2/3</p>
                </div>
            </div>

            <div class="feature-card">
                <div class="p-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Parrainage</h3>
                    <p class="text-gray-600">Choisissez votre candidat et confirmez votre parrainage.</p>
                </div>
                <div class="bg-green-50 p-4">
                    <div class="progress-bar">
                        <div class="progress-bar-fill" style="width: 100%"></div>
                    </div>
                    <p class="text-sm text-green-600 mt-2">Étape 3/3</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold mb-6">Statistiques en Direct</h2>
                <p class="text-gray-600 mb-8">Suivez l'évolution du processus de parrainage en temps réel</p>
                
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Parrainages Collectés</span>
                            <span class="stat-number font-bold">32,450</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" style="width: 73%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Régions Actives</span>
                            <span class="stat-number font-bold">14/14</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" style="width: 100%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Jours Restants</span>
                            <span class="stat-number font-bold">7</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-green-50 p-6 rounded-2xl text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">12</div>
                    <div class="text-gray-600">Candidats</div>
                </div>
                
                <div class="bg-green-50 p-6 rounded-2xl text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">44,231</div>
                    <div class="text-gray-600">Signatures Requises</div>
                </div>
                
                <div class="bg-green-50 p-6 rounded-2xl text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">67%</div>
                    <div class="text-gray-600">Taux de Validation</div>
                </div>
                
                <div class="bg-green-50 p-6 rounded-2xl text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">24/7</div>
                    <div class="text-gray-600">Support Disponible</div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">À Propos</h3>
                <p class="text-gray-400">Plateforme officielle de parrainage électoral de la République du Sénégal</p>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-4">Contact</h3>
                <p class="text-gray-400">Email: contact@parrainage.gouv.sn</p>
                <p class="text-gray-400">Tél: +221 XX XXX XX XX</p>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-4">Liens Rapides</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Accueil</a></li>
                    <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition">Support</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-4">Légal</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Conditions d'utilisation</a></li>
                    <li><a href="#" class="hover:text-white transition">Politique de confidentialité</a></li>
                    <li><a href="#" class="hover:text-white transition">Mentions légales</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Système de Parrainage Électoral du Sénégal. Tous droits réservés.</p>
        </div>
    </div>
</footer>
@endsection
