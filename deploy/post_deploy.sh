#!/bin/bash -l

echo "Running post-build tasks"

# Exécuter les migrations
echo "Running migrations..."
php artisan migrate --force

echo "Post-build tasks completed!"
