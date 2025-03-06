#!/bin/bash
set -e

echo "Setting up Laravel environment..."

if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env file created"
fi

php artisan config:clear

php artisan key:generate
echo "APP_KEY generated"

php artisan migrate:fresh --force --seed
echo "Database migration and seeding completed"

php artisan config:clear
php artisan cache:clear
php artisan config:cache

echo "Laravel API is ready!"
