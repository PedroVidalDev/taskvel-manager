#!/bin/bash

echo "Starting Docker container..."
docker compose up -d --build

echo "Waiting for the database to be ready..."
./wait-for-it.sh localhost:5432 --timeout=100 -- echo "Database is ready!"

sleep 2

echo "Running migrations..."
php artisan migrate

echo "Starting the PHPUnit tests..."
php artisan test

# Capture the exit status of the tests
TEST_EXIT_STATUS=$?

echo "Stopping and removing Docker containers..."
docker compose down

#Exit with the status of the test command
exit $TEST_EXIT_STATUS
