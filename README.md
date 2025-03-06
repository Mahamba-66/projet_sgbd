# Système de Gestion des Parrainages

Ce projet est un système de gestion des parrainages pour les élections, développé avec Laravel.

## Fonctionnalités

- Gestion des candidats
- Gestion des parrainages
- Validation des signatures
- Interface d'administration
- Tableau de bord des statistiques

## Installation

1. Cloner le projet :
```bash
git clone https://github.com/Mahamba-66/projet_sgbd.git
```

2. Installer les dépendances :
```bash
composer install
```

3. Copier le fichier .env.example :
```bash
cp .env.example .env
```

4. Générer la clé d'application :
```bash
php artisan key:generate
```

5. Configurer la base de données dans .env

6. Lancer les migrations :
```bash
php artisan migrate
```

7. Démarrer le serveur :
```bash
php artisan serve
```

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou un pull request.
