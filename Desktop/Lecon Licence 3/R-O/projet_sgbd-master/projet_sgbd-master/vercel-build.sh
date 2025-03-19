 #!/bin/bash

composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan config:cache
