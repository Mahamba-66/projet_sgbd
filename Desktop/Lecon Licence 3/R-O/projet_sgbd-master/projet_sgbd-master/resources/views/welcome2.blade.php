@extends('layouts.app')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(45deg, rgba(0, 132, 61, 0.9), rgba(0, 132, 61, 0.7)), url('https://www.senenews.com/wp-content/uploads/2024/02/Palais-de-la-Republique-Senegal.jpg');
        background-size: cover;
        background-position: center;
        min-height: 80vh;
        display: flex;
        align-items: center;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 2rem;
    }

    .process-step {
        position: relative;
        padding-left: 3rem;
    }

    .process-step::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 2px;
        height: 100%;
        background: #00843D;
    }

    .process-step::after {
        content: '';
        position: absolute;
        left: -8px;
        top: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #00843D;
    }
</style>
@endsection

@section('content')
<div class="hero-section">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl">
            <div class="glass-card text-white">
                <h1 class="text-6xl font-bold mb-6">Parrainage Électoral</h1>
                <p class="text-xl mb-8 opacity-90">Une plateforme moderne pour un processus électoral transparent et efficace</p>
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Commencer
                    </a>
                    <a href="#process" class="border border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-600 transition">
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="process" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl font-bold mb-12 text-center">Processus de Parrainage</h2>
            
            <div class="space-y-12">
                <div class="process-step">
                    <h3 class="text-2xl font-semibold mb-3">Inscription et Vérification</h3>
                    <p class="text-gray-600">Créez votre compte avec vos informations d'électeur. Notre système vérifie automatiquement votre éligibilité.</p>
                </div>

                <div class="process-step">
                    <h3 class="text-2xl font-semibold mb-3">Choix du Candidat</h3>
                    <p class="text-gray-600">Consultez la liste des candidats et leurs programmes. Faites un choix éclairé pour l'avenir du Sénégal.</p>
                </div>

                <div class="process-step">
                    <h3 class="text-2xl font-semibold mb-3">Validation du Parrainage</h3>
                    <p class="text-gray-600">Confirmez votre parrainage de manière sécurisée. Recevez une confirmation instantanée.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-green-50 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Statistiques en Temps Réel</h2>
            <p class="text-gray-600">Suivez l'évolution du processus de parrainage</p>
        </div>

        <div class="grid md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">14</div>
                <div class="text-gray-600">Régions</div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">44,231</div>
                <div class="text-gray-600">Signatures Requises</div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">12</div>
                <div class="text-gray-600">Candidats</div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">7</div>
                <div class="text-gray-600">Jours Restants</div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-semibold mb-4">À Propos</h3>
                <p class="text-gray-400">Plateforme officielle de parrainage électoral de la République du Sénégal</p>
            </div>
            <div>
                <h3 class="text-xl font-semibold mb-4">Contact</h3>
                <p class="text-gray-400">Email: contact@parrainage.gouv.sn</p>
                <p class="text-gray-400">Tél: +221 XX XXX XX XX</p>
            </div>
            <div>
                <h3 class="text-xl font-semibold mb-4">Liens Utiles</h3>
                <ul class="text-gray-400">
                    <li><a href="#" class="hover:text-white">FAQ</a></li>
                    <li><a href="#" class="hover:text-white">Conditions d'utilisation</a></li>
                    <li><a href="#" class="hover:text-white">Politique de confidentialité</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Système de Parrainage Électoral du Sénégal. Tous droits réservés.</p>
        </div>
    </div>
</footer>
@endsection
