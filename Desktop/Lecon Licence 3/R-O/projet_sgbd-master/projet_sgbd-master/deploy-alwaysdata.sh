#!/bin/bash

# Installation rapide des dépendances
composer install --no-dev --optimize-autoloader --no-interaction

# Optimisation Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migration de la base de données
php artisan migrate --force

# Nettoyage du cache
php artisan cache:clear
php artisan config:clear
