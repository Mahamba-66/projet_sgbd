# Chemin vers le fichier php.ini
$phpIniPath = "C:\xampp\php\php.ini"

# Lire le contenu du fichier
$content = Get-Content $phpIniPath -Raw

# Vérifier si l'extension est déjà activée
if ($content -match ";extension=mbstring") {
    # Décommenter l'extension
    $content = $content -replace ";extension=mbstring", "extension=mbstring"
    
    # Sauvegarder les modifications
    $content | Set-Content $phpIniPath -Force
    
    Write-Host "Extension mbstring activée avec succès!"
    Write-Host "Veuillez redémarrer votre serveur web pour appliquer les modifications."
} else {
    Write-Host "L'extension mbstring est déjà activée ou la ligne n'a pas été trouvée."
}
