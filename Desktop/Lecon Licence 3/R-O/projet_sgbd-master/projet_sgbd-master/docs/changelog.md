# Journal des Modifications

## [2025-03-18] - Administration des Parrainages

### Ajouté
- Contrôleur AdminSponsorshipController avec fonctionnalités CRUD
- Vues pour la gestion des parrainages (index et show)
- Bouton de déconnexion rouge dans le layout admin

### Modifié
- Layout admin.blade.php pour inclure le bouton de déconnexion
- Routes web.php pour inclure les nouvelles routes de parrainage
- README.md avec documentation complète

### Fichiers Concernés
1. Contrôleurs
   - `app/Http/Controllers/Admin/AdminSponsorshipController.php`
   - `app/Http/Controllers/Admin/VoterImportController.php`

2. Vues
   - `resources/views/layouts/admin.blade.php`
   - `resources/views/admin/sponsorships/index.blade.php`
   - `resources/views/admin/sponsorships/show.blade.php`
   - `resources/views/admin/voters/import.blade.php`
   - `resources/views/admin/voters/validation-errors.blade.php`

3. Routes
   - `routes/web.php`

4. Documentation
   - `README.md`
   - `docs/MAINTENANCE.md`
   - `docs/changelog.md`

### Base de Données
- Nouvelle table `voter_import_history`
- Mise à jour de la table `sponsorships`

### Tests Effectués
- [x] Import des électeurs
- [x] Validation des parrainages
- [x] Rejet des parrainages
- [x] Affichage des statistiques
- [x] Déconnexion

### Notes de Version
- Version minimale de PHP : 8.2.12
- Version de Laravel : 12.1.0
- Dépendances : Bootstrap 5.3.0, Font Awesome

### Procédure de Rollback
1. Restaurer les fichiers depuis la sauvegarde
2. Exécuter la migration rollback
```bash
php artisan migrate:rollback --step=1
```
3. Vider le cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## [2025-03-18] - Ajout de la Vérification par Email

### Ajouté
- Système de vérification par email pour les candidats
- Configuration Gmail pour l'envoi d'emails
- Middleware de vérification d'email
- Pages de vérification avec interface utilisateur moderne

### Modifié
- Table `users` : ajout de la colonne `verification_code`
- Processus d'inscription des candidats
- Documentation mise à jour avec les informations de vérification par email

### Fichiers Concernés
1. Contrôleurs
   - `app/Http/Controllers/Auth/EmailVerificationController.php`
   - `app/Http/Controllers/Auth/RegisterController.php`

2. Vues
   - `resources/views/auth/verify.blade.php`
   - `resources/views/emails/verification.blade.php`

3. Middleware
   - `app/Http/Middleware/EnsureEmailIsVerified.php`

4. Configuration
   - `.env` (mise à jour des paramètres mail)

5. Documentation
   - `README.md`
   - `docs/changelog.md`

### Tests Effectués
- [x] Envoi d'emails via Gmail
- [x] Génération et validation des codes
- [x] Protection des routes
- [x] Interface de vérification
- [x] Renvoi de code

### Notes de Version
- Nécessite la configuration Gmail dans `.env`
- Compatible avec Laravel 10+
- Utilise le service SMTP de Gmail

### Procédure de Rollback
1. Supprimer la colonne `verification_code` :
```sql
ALTER TABLE users DROP COLUMN verification_code;
```
2. Restaurer le fichier `.env` depuis la sauvegarde
3. Retirer le middleware `verified` des routes

## [2025-03-18] Configuration Email et Documentation
### Ajouté
- Configuration SMTP Gmail pour l'envoi d'emails
- Documentation de la configuration email (`docs/email-configuration.md`)
- Documentation des procédures de modification (`docs/procedures-modification.md`)

### Modifié
- Fichier `config/mail.php` : changement du driver par défaut de 'log' à 'smtp'
- Fichier `.env` : ajout des configurations SMTP Gmail

### Procédure de Rollback
En cas de problème :
1. Restaurer la configuration email précédente dans `.env`
2. Restaurer `config/mail.php` à sa version précédente
3. Exécuter :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## [2025-03-18] - Correction des routes d'importation des électeurs

### Modifications
- Renommé la route d'importation des électeurs de `admin.voters.import.process` à `admin.voters.import.submit`
- Réorganisé les routes des électeurs pour plus de clarté

### Fichiers modifiés
- `routes/web.php`

### Procédure de rollback
1. Restaurer le fichier de sauvegarde : `backups/2025-03-18/web.php.bak`
2. Redémarrer le serveur Laravel

### Tests effectués
- Accès à la page d'importation des électeurs (/admin/import-voters)
- Sélection et envoi d'un fichier CSV
- Vérification du traitement du formulaire

### Dépendances
- VoterImportController
- Vue admin.voters.import

## [2025-03-17] - Import des Électeurs

### Ajouté
- Fonctionnalité d'import des électeurs
- Validation des fichiers CSV
- Historique des imports

### Modifié
- Interface d'administration
- Système de validation

### Notes
- Sauvegarde effectuée avant les modifications
- Tests effectués sur environnement local
- Documentation mise à jour
