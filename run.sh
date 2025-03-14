#!/bin/sh

# Espera o PostgreSQL estar pronto
wait-for-it db:5432 --timeout=30 --strict -- echo "Banco de dados está pronto!"

# Rodar as migrações e o servidor Laravel
php artisan migrate
php artisan serve
