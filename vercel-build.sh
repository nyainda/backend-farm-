#!/bin/bash

# Install PHP and Composer
yum install -y php composer

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Generate the application key
php artisan key:generate --force

# Generate JWT secret
php artisan jwt:secret --force

# Run migrations (if applicable)
php artisan migrate --force

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Optimize the application
php artisan optimize