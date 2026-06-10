#!/usr/bin/env bash
# Exit immediately on error
set -o errexit

# Install PHP dependencies (production only)
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
npm ci
npm run build

# Laravel optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Create symbolic link for storage
php artisan storage:link
