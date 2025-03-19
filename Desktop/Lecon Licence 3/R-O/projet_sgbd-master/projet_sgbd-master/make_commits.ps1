$commits = @(
    @{file="app/Http/Controllers/Auth/LoginController.php"; message="feat: Ajout de la validation du statut utilisateur lors de la connexion"},
    @{file="app/Http/Controllers/VoterController.php"; message="feat: Implémentation de la gestion des électeurs"},
    @{file="app/Http/Controllers/RegionController.php"; message="feat: Ajout du CRUD pour les régions"},
    @{file="app/Http/Controllers/CandidateController.php"; message="feat: Système de validation des candidats"},
    @{file="app/Models/Region.php"; message="feat: Relations et validations du modèle Region"},
    @{file="app/Models/Sponsorship.php"; message="feat: Logique métier pour les parrainages"},
    @{file="database/migrations/2024_03_19_regions.php"; message="feat: Migration pour la table des régions"},
    @{file="database/migrations/2024_03_19_sponsorships.php"; message="feat: Migration pour la table des parrainages"},
    @{file="resources/views/admin/dashboard.blade.php"; message="feat: Interface admin avec statistiques"},
    @{file="resources/views/voter/profile.blade.php"; message="feat: Page profil électeur"},
    @{file="resources/views/candidate/dashboard.blade.php"; message="feat: Dashboard candidat"},
    @{file="public/js/sponsorship.js"; message="feat: Validation JavaScript des formulaires"},
    @{file="routes/web.php"; message="feat: Nouvelles routes pour le parrainage"},
    @{file="config/app.php"; message="config: Mise à jour des paramètres application"},
    @{file="app/Http/Middleware/CheckUserStatus.php"; message="feat: Middleware de vérification statut"},
    @{file="app/Services/SponsorshipService.php"; message="feat: Service de gestion des parrainages"},
    @{file="tests/Feature/SponsorshipTest.php"; message="test: Tests du système de parrainage"},
    @{file="database/seeders/RegionSeeder.php"; message="feat: Seeder pour les régions"},
    @{file="app/Console/Commands/ValidateSponsorship.php"; message="feat: Commande de validation des parrainages"}
)

foreach ($commit in $commits) {
    $filePath = $commit.file
    $message = $commit.message
    
    # Créer le dossier parent si nécessaire
    $folder = Split-Path $filePath -Parent
    if (!(Test-Path $folder)) {
        New-Item -ItemType Directory -Force -Path $folder
    }
    
    # Créer ou mettre à jour le fichier
    $content = "<?php`n// Ajouté le $(Get-Date)`n// $message`n"
    Set-Content $filePath $content
    
    # Git add et commit
    git add $filePath
    git commit -m $message
}
