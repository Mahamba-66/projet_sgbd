<?php
// Script de déploiement automatique pour Hostinger

$ftp_server = "ftp.votredomaine.com";
$ftp_username = "votre_username";
$ftp_password = "votre_password";

// Connexion FTP
$conn_id = ftp_connect($ftp_server);
$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);

if (!$login_result) {
    die("Échec de la connexion FTP");
}

// Dossiers à exclure du déploiement
$exclude = array(
    '.git',
    'node_modules',
    'tests',
    'storage/logs',
    'storage/framework/cache'
);

// Fonction pour uploader un dossier
function upload_directory($conn_id, $local_dir, $remote_dir, $exclude) {
    $files = scandir($local_dir);
    
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && !in_array($file, $exclude)) {
            $local_file = $local_dir . '/' . $file;
            $remote_file = $remote_dir . '/' . $file;
            
            if (is_dir($local_file)) {
                ftp_mkdir($conn_id, $remote_file);
                upload_directory($conn_id, $local_file, $remote_file, $exclude);
            } else {
                ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY);
            }
        }
    }
}

// Déploiement
echo "Début du déploiement...\n";
upload_directory($conn_id, __DIR__, '/public_html', $exclude);

// Configuration de Laravel
echo "Configuration de Laravel...\n";
$commands = array(
    'composer install --no-dev --optimize-autoloader',
    'php artisan key:generate',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
);

foreach ($commands as $command) {
    echo "Exécution de : $command\n";
    exec($command);
}

echo "Déploiement terminé avec succès!\n";
ftp_close($conn_id);
