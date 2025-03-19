#!/bin/bash

# Installation des dépendances
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Configuration de Laravel
cp .env.example .env
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configuration des permissions
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs
chmod -R 777 storage/framework

# Configuration du .htaccess
cp public/.htaccess.pythonanywhere public/.htaccess

echo "Configuration terminée !"
