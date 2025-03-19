<?php
// Configuration de base
define('DB_HOST', 'localhost');
define('DB_NAME', 'id12345_parrainage');
define('DB_USER', 'id12345_admin');
define('DB_PASS', 'votre_mot_de_passe');

// Connexion à la base de données
try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données\n";
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Configuration de Laravel
shell_exec('php artisan config:cache');
shell_exec('php artisan route:cache');
shell_exec('php artisan view:cache');

echo "Configuration terminée !\n";
