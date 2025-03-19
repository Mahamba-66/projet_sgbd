<?php
// Script de déploiement simple
echo "Début du déploiement...\n";

// 1. Mise à jour du code
echo "1. Mise à jour du code...\n";
shell_exec('git pull');

// 2. Installation des dépendances
echo "2. Installation des dépendances...\n";
shell_exec('composer install --no-dev --optimize-autoloader');
shell_exec('npm install');
shell_exec('npm run build');

// 3. Configuration de Laravel
echo "3. Configuration de Laravel...\n";
shell_exec('php artisan config:cache');
shell_exec('php artisan route:cache');
shell_exec('php artisan view:cache');

// 4. Migration de la base de données
echo "4. Migration de la base de données...\n";
shell_exec('php artisan migrate --force');

echo "Déploiement terminé avec succès!\n";
