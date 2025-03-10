#!/bin/bash

# Start Docker container with the database
echo "Starting Docker container..."
docker compose up -d --build

# Wait for the database to be ready
echo "Waiting for the database to be ready..."
./wait-for-it.sh localhost:5432 --timeout=100 -- echo "Database is ready!"

sleep 2

# Run migrations
echo "Running migrations..."
php artisan migrate

# Start the PHP development server
echo "Starting PHP development server..."
php artisan serve
