# Procédures de Modification et Sauvegarde

## 1. Procédures de Sauvegarde

### Avant toute modification
- Sauvegarder la base de données :
  ```bash
  php artisan backup:run
  ```
- Créer une copie des fichiers à modifier
- Noter les modifications prévues dans le journal de modifications

### Journal des Modifications
Maintenir un fichier `changelog.md` avec :
- Date de la modification
- Description des changements
- Fichiers modifiés
- Procédure de rollback

## 2. Règles pour les Migrations de Base de Données

### Principes Fondamentaux
- Ne jamais modifier directement les tables existantes
- Toujours créer de nouvelles migrations pour les modifications
- Implémenter la méthode `down()` pour chaque migration

### Processus de Migration
1. Créer une nouvelle migration :
   ```bash
   php artisan make:migration nom_de_la_migration
   ```
2. Tester sur l'environnement local :
   ```bash
   php artisan migrate --env=testing
   ```
3. Vérifier la possibilité de rollback :
   ```bash
   php artisan migrate:rollback --step=1
   ```

## 3. Règles pour les Modifications de Code

### Principes de Base
- Modifier un seul composant à la fois
- Garder une copie de sauvegarde avant modification
- Ajouter des commentaires explicatifs
- Tester chaque modification individuellement

### Documentation du Code
- Documenter les modifications avec des commentaires clairs
- Noter les dépendances entre composants
- Maintenir la documentation à jour

## 4. Procédures de Test

### Environnement de Test
1. Configurer l'environnement :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
2. Exécuter les tests :
   ```bash
   php artisan test
   ```

### Vérifications
- Tester sur environnement local d'abord
- Vérifier la non-régression des fonctionnalités
- Documenter les tests effectués

## 5. Procédures de Rollback

### En cas de Problème
1. Restaurer la sauvegarde de la base de données
2. Restaurer les fichiers de sauvegarde
3. Nettoyer le cache :
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

### Documentation
- Noter les problèmes rencontrés
- Documenter la procédure de rollback utilisée
- Mettre à jour les procédures si nécessaire

## 6. Structure de l'Application

### Tables Principales
- `users` : gestion des utilisateurs
- `imported_voters` : importation des électeurs
- `regions` : gestion des régions
- `sponsorships` : gestion des parrainages

### Rôles et Statuts
- Rôles : admin, superadmin, voter, candidate
- Statuts : active, pending, rejected, blocked

### Routes Critiques
- `/login` : authentification
- `/admin/*` : administration
- `/voter/*` : espace électeur
- `/candidate/*` : espace candidat

## 7. Sécurité

### Middlewares
- `AdminMiddleware` : contrôle d'accès admin
- `AuthMiddleware` : authentification
- `StatusMiddleware` : vérification des statuts

### Bonnes Pratiques
- Toujours utiliser les middlewares appropriés
- Vérifier les permissions avant les actions
- Valider toutes les entrées utilisateur
