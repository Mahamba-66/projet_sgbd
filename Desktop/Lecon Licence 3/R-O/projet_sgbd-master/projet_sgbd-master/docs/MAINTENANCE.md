# Guide de Maintenance et Procédures de Sauvegarde

## 1. Procédures de Sauvegarde

### 1.1 Sauvegarde de la Base de Données
```bash
# 1. Créer un dump de la base de données
php artisan db:backup

# 2. Stocker le dump dans le dossier backups avec la date
mysqldump -u root -p electoral_db > backups/db_backup_$(date +%Y%m%d).sql
```

### 1.2 Sauvegarde des Fichiers
```bash
# 1. Créer une archive des fichiers modifiés
tar -czf backups/files_backup_$(date +%Y%m%d).tar.gz app/ config/ resources/ routes/

# 2. Sauvegarder les fichiers de configuration
cp .env backups/env_backup_$(date +%Y%m%d)
```

### 1.3 Journal des Modifications
- Créer une entrée dans `docs/changelog.md` pour chaque modification
- Inclure la date, l'auteur et la description des changements
- Documenter les fichiers modifiés

## 2. Migrations de Base de Données

### 2.1 Règles pour les Migrations
1. Ne jamais modifier directement les tables existantes
2. Créer de nouvelles migrations pour les modifications
3. Toujours implémenter la méthode down()
4. Tester sur un environnement de test avant la production

### 2.2 Exemple de Migration Sécurisée
```php
public function up()
{
    // 1. Créer une nouvelle table temporaire
    Schema::create('new_table', function (Blueprint $table) {
        // ...
    });

    // 2. Copier les données
    DB::statement('INSERT INTO new_table SELECT * FROM old_table');

    // 3. Renommer les tables
    Schema::rename('old_table', 'old_table_backup');
    Schema::rename('new_table', 'old_table');
}

public function down()
{
    // Restauration possible
    Schema::rename('old_table', 'new_table');
    Schema::rename('old_table_backup', 'old_table');
    Schema::drop('new_table');
}
```

## 3. Modifications de Code

### 3.1 Procédure de Modification
1. Créer une branche pour la modification
2. Sauvegarder le fichier original
3. Effectuer les modifications
4. Tester les changements
5. Documenter les modifications
6. Fusionner la branche

### 3.2 Documentation des Modifications
```markdown
## Modification - [Date]
- **Fichier**: chemin/vers/fichier
- **Type**: [Correction|Amélioration|Nouvelle fonctionnalité]
- **Description**: Description détaillée
- **Testeur**: Nom du testeur
- **Validé par**: Nom du validateur
```

## 4. Procédures de Test

### 4.1 Tests Locaux
1. Configurer l'environnement de test
```bash
cp .env .env.testing
php artisan config:clear
```

2. Exécuter les tests
```bash
php artisan test
```

3. Vérifier la couverture
```bash
vendor/bin/phpunit --coverage-html tests/coverage
```

### 4.2 Liste de Vérification
- [ ] Tests unitaires passent
- [ ] Tests d'intégration passent
- [ ] Pas d'erreurs dans les logs
- [ ] Fonctionnalités existantes non affectées
- [ ] Documentation mise à jour

## 5. Procédures de Rollback

### 5.1 Rollback de Base de Données
```bash
# Annuler la dernière migration
php artisan migrate:rollback

# Restaurer depuis une sauvegarde
mysql -u root -p electoral_db < backups/db_backup_[DATE].sql
```

### 5.2 Rollback de Code
```bash
# Restaurer les fichiers sauvegardés
cp backups/[fichier]_backup_[DATE] [chemin/vers/fichier]

# Redémarrer les services
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 6. Documentation

### 6.1 Structure de la Documentation
```
docs/
├── MAINTENANCE.md (ce fichier)
├── changelog.md
├── backups/
│   ├── db_backups/
│   └── file_backups/
└── procedures/
    ├── backup.md
    ├── restore.md
    └── test.md
```

### 6.2 Maintenance de la Documentation
- Mettre à jour après chaque modification
- Inclure les nouvelles procédures
- Documenter les problèmes rencontrés
- Maintenir une FAQ

## 7. Contacts d'Urgence

### 7.1 Équipe Technique
- **Admin Système**: [Nom] - [Contact]
- **DBA**: [Nom] - [Contact]
- **Lead Dev**: [Nom] - [Contact]

### 7.2 Procédure d'Escalade
1. Développeur en charge
2. Lead Developer
3. Admin Système
4. DBA (pour les problèmes de base de données)
5. Direction Technique
