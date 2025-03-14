#!/bin/bash

# Define your container name or ID
CONTAINER_NAME="taskvel_app"

docker compose up --build -d
wait-for-it localhost:8000 --timeout=30 --strict -- echo "Servidor Laravel está pronto!"

# Run php artisan test inside the container
docker exec -it $CONTAINER_NAME php artisan test

# Check if the command was successful
if [ $? -eq 0 ]; then
    echo "Tests ran successfully!"
else
    echo "Tests failed!"
    exit 1
fi

docker compose down -v --remove-orphans
