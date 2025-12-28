echo "Executando migrações..."
docker exec laravel_app php artisan migrate --seed
