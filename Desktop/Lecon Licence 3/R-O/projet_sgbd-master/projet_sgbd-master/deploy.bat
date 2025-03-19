@echo off
echo Deploying to Vercel...

REM Create api directory if it doesn't exist
mkdir api 2>nul

REM Move index.php to api directory
copy public\index.php api\index.php >nul

REM Configure environment
echo Setting up environment...
vercel env add APP_KEY "base64:$(php artisan key:generate --show)"
vercel env add APP_NAME "Parrainage App"
vercel env add APP_ENV production
vercel env add APP_DEBUG false

REM Deploy to production
echo y | vercel --prod

echo Deployment complete!
