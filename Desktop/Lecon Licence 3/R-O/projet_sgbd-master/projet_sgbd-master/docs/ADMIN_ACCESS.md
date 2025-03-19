# Guide d'accès administrateur

## Rôles et permissions

### Rôles supportés
- `admin` : Administrateur standard
- `superadmin` : Super administrateur avec tous les droits
- `voter` : Électeur standard
- `candidate` : Candidat

### Accès aux sections administratives

1. **Routes protégées**
   - Toutes les routes commençant par `/admin/*` sont protégées
   - Seuls les utilisateurs avec le rôle `admin` ou `superadmin` peuvent y accéder
   - Les autres utilisateurs seront redirigés vers la page de connexion

2. **Pages administratives principales**
   - `/admin/dashboard` : Tableau de bord administrateur
   - `/admin/voters` : Gestion des électeurs
   - `/admin/candidates` : Gestion des candidats
     - `POST /admin/candidates/{candidate}/validate` : Valider un candidat
     - `POST /admin/candidates/{candidate}/reject` : Rejeter un candidat (nécessite une raison)
   - `/admin/sponsorships` : Gestion des parrainages

## Configuration du middleware

Le middleware `AdminMiddleware` est responsable de la protection des routes administratives.

### Fonctionnement
1. Vérifie si l'utilisateur est connecté
2. Vérifie si l'utilisateur a le rôle `admin` ou `superadmin`
3. Redirige vers la page de connexion si les conditions ne sont pas remplies

### Exemple d'utilisation dans les routes
```php
// Protection d'une route unique
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', AdminMiddleware::class]);

// Protection d'un groupe de routes
Route::prefix('admin')
    ->middleware(['auth', AdminMiddleware::class])
    ->group(function () {
        // Routes protégées ici
    });
```

## Gestion des erreurs

1. **Utilisateur non connecté**
   - Message : "Vous devez être connecté pour accéder à cette page."
   - Redirection vers : `/login`

2. **Utilisateur sans droits administratifs**
   - Message : "Accès non autorisé. Vous devez être administrateur."
   - Action : Déconnexion automatique
   - Redirection vers : `/login`

## Gestion des candidats

### Routes disponibles
- `GET /admin/candidates` : Liste des candidats
- `POST /admin/candidates/{candidate}/validate` : Valider un candidat
  - Met à jour le statut du candidat à "validated"
  - Enregistre la date de validation
- `POST /admin/candidates/{candidate}/reject` : Rejeter un candidat
  - Nécessite une raison de rejet (champ "reason")
  - Met à jour le statut du candidat à "rejected"
  - Enregistre la raison et la date du rejet
- `GET /admin/candidates/{id}/report` : Télécharger le rapport d'un candidat

### Statuts des candidats
- `pending` : En attente de validation
- `validated` : Candidature validée
- `rejected` : Candidature rejetée

## Bonnes pratiques

1. **Sécurité**
   - Toujours utiliser le middleware sur les routes administratives
   - Ne jamais désactiver la vérification des rôles
   - Vérifier les permissions même dans les contrôleurs

2. **Maintenance**
   - Mettre à jour régulièrement les permissions
   - Vérifier les logs d'accès
   - Auditer régulièrement les comptes administrateurs

3. **Développement**
   - Tester les routes protégées avec différents rôles
   - Documenter les nouvelles routes administratives
   - Maintenir une liste des permissions à jour
