#!/bin/bash
# Railway Post-Deploy Script
echo "Running migrations..."
php artisan migrate --force

echo "Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "Deployment complete!"
