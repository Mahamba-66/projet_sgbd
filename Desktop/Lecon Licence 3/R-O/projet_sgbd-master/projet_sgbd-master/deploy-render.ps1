$headers = @{
    "Content-Type" = "application/json"
}

$body = @{
    "type" = "web_service"
    "name" = "parrainage-app"
    "env" = "php"
    "region" = "frankfurt"
    "branch" = "main"
    "rootDir" = "."
    "buildCommand" = "composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev && npm ci && npm run build && php artisan config:cache"
    "startCommand" = "heroku-php-apache2 public/"
    "envVars" = @(
        @{
            "key" = "APP_NAME"
            "value" = "Parrainage App"
        },
        @{
            "key" = "APP_ENV"
            "value" = "production"
        },
        @{
            "key" = "APP_DEBUG"
            "value" = "false"
        }
    )
} | ConvertTo-Json

Write-Host "Déploiement de l'application sur Render..."

# Création du service
$response = Invoke-RestMethod -Uri "https://api.render.com/v1/services" -Method Post -Headers $headers -Body $body

Write-Host "Service créé avec succès!"
Write-Host "URL: $($response.service.url)"
