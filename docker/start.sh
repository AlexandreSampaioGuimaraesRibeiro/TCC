#!/usr/bin/env bash
set -e

echo "Limpando caches antigos..."
php artisan config:clear
php artisan cache:clear

echo "Cacheando config e rotas..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Rodando migrations..."
php artisan migrate --force

echo "Rodando seeds (Categoria, Admin, Profissionais de exemplo)..."
php artisan db:seed --force

echo "Subindo servidor na porta ${PORT:-10000}..."
php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
