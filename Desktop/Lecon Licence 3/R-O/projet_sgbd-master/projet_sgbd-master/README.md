# Système de Gestion Électorale

## Structure du Projet

### 1. Routes Principales

#### Routes Publiques
- `/` : Page d'accueil
- `/login` : Page de connexion
- `/register` : Sélection du type d'inscription (électeur ou candidat)
- `/register/voter` : Inscription électeur
- `/register/candidate` : Inscription candidat

#### Routes Administrateur (`/admin/`)
- `/admin/dashboard` : Tableau de bord administrateur
- `/admin/candidates` : Gestion des candidats
- `/admin/sponsorships` : Gestion des parrainages
- `/admin/voters` : Gestion des électeurs
- `/admin/statistics` : Statistiques
- `/admin/reports` : Rapports
- `/admin/audit-log` : Journal d'audit
- `/admin/users` : Gestion des utilisateurs
- `/admin/settings` : Paramètres

### 2. Contrôleurs

#### Contrôleurs d'Administration
1. `AdminDashboardController`
   - `index()` : Affiche le tableau de bord

2. `AdminCandidateController`
   - `index()` : Liste des candidats
   - `show($id)` : Détails d'un candidat
   - `validateCandidate($id)` : Valider un candidat
   - `rejectCandidate($id)` : Rejeter un candidat
   - `generateReport($id)` : Générer un rapport

3. `AdminSponsorshipController`
   - `index()` : Liste des parrainages
   - `show($id)` : Détails d'un parrainage
   - `validateSponsorship($id)` : Valider un parrainage
   - `rejectSponsorship($id)` : Rejeter un parrainage

4. `VoterImportController`
   - `showImportForm()` : Formulaire d'import
   - `import()` : Traiter l'import
   - `showValidationErrors()` : Afficher les erreurs

### 3. Vues

#### Layout Principal
- `resources/views/layouts/admin.blade.php` : Template principal admin

#### Vues Admin
1. Candidats
   - `admin/candidates/index.blade.php` : Liste des candidats
   - `admin/candidates/show.blade.php` : Détails candidat

2. Parrainages
   - `admin/sponsorships/index.blade.php` : Liste des parrainages
   - `admin/sponsorships/show.blade.php` : Détails parrainage

3. Électeurs
   - `admin/voters/import.blade.php` : Import des électeurs
   - `admin/voters/validation-errors.blade.php` : Erreurs d'import

### 4. Base de Données

#### Tables Principales
1. `users`
   - id, name, email, password, role, status
   - Rôles : admin, voter, candidate
   - Statuts : active, pending, rejected

2. `sponsorships`
   - id, candidate_id, voter_id, status
   - Statuts : pending, validated, rejected

3. `voter_import_history`
   - id, file_name, total_records, valid_records, invalid_records
   - created_by, created_at, updated_at

### 5. Fonctionnalités Clés

#### Gestion des Candidats
- Liste des candidats avec statuts
- Validation/Rejet des candidatures
- Génération de rapports

#### Gestion des Parrainages
- Vue d'ensemble des parrainages
- Validation/Rejet des parrainages
- Statistiques par région

#### Import des Électeurs
- Import via fichier CSV
- Validation des données
- Historique des imports

### 6. Sécurité

#### Middleware
- `AdminMiddleware` : Vérifie les permissions admin
- `AuthMiddleware` : Vérifie l'authentification
- `StatusMiddleware` : Vérifie le statut utilisateur

#### Protection CSRF
- Tous les formulaires incluent `@csrf`
- Protection contre les attaques CSRF

### 7. Interface Utilisateur

#### Composants Bootstrap
- Tableaux responsifs
- Cartes pour les statistiques
- Formulaires stylisés
- Boutons d'action

#### Icônes
- Utilisation de Font Awesome
- Classes : fas fa-*

### 8. Messages et Notifications

#### Types de Messages
- Succès : `alert-success`
- Erreur : `alert-danger`
- Info : `alert-info`
- Avertissement : `alert-warning`

### 9. Navigation

#### Menu Admin
- Tableau de bord
- Gestion des candidats
- Gestion des parrainages
- Import des électeurs
- Statistiques et rapports
- Paramètres

#### Actions Rapides
- Bouton de déconnexion (rouge)
- Validation/Rejet rapide
- Accès aux détails

## Vérification par Email

### Configuration Gmail
Pour utiliser la vérification par email avec Gmail, configurez les paramètres suivants dans votre fichier `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=diaoseydina62@gmail.com
MAIL_PASSWORD="lgoo pbck agao sewq"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=diaoseydina62@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Processus de Vérification
1. Lors de l'inscription d'un candidat :
   - Un code de vérification à 6 chiffres est généré
   - Le code est envoyé à l'adresse email du candidat
   - Le candidat est redirigé vers la page de vérification

2. Sur la page de vérification :
   - Le candidat doit entrer le code reçu par email
   - Si le code est correct :
     - Le compte est marqué comme vérifié
     - Le candidat est redirigé vers son tableau de bord
   - Si le code est incorrect :
     - Un message d'erreur est affiché
     - Le candidat peut demander un nouveau code

3. Fonctionnalités de sécurité :
   - Le code expire après 60 minutes
   - Le candidat peut demander un nouveau code si nécessaire
   - L'accès au tableau de bord est restreint jusqu'à la vérification

### Routes de Vérification
```php
/verify              // Affiche le formulaire de vérification
/verify (POST)       // Vérifie le code soumis
/verify/resend       // Renvoie un nouveau code
```

### Middleware de Vérification
Le middleware `verified` protège les routes qui nécessitent une vérification d'email :
```php
Route::middleware(['auth', 'verified'])->group(function () {
    // Routes protégées
});
```

### Base de Données
La table `users` inclut les colonnes suivantes pour la vérification :
- `verification_code` : Code de vérification temporaire
- `email_verified_at` : Timestamp de la vérification

## Maintenance et Dépannage

### Erreurs Courantes
1. **Erreur 404** : Vérifier les routes dans `web.php`
2. **Erreur 500** : Vérifier les logs dans `storage/logs`
3. **Erreur CSRF** : Vérifier la présence de `@csrf` dans les formulaires

### Points de Vérification
1. Permissions des fichiers
2. Configuration de la base de données
3. Variables d'environnement
4. Sessions et cache

## Développement Futur

### Améliorations Prévues
1. Export des données en PDF/Excel
2. Tableau de bord en temps réel
3. Notifications par email
4. API pour applications mobiles

### Notes Importantes
- Toujours sauvegarder avant les modifications
- Tester sur un environnement de développement
- Suivre les conventions de nommage Laravel
- Documenter les changements
