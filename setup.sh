#!/bin/bash

set -e  # Para interromper a execução se houver erro

# Verifica se Docker e Docker Compose estão instalados
if ! command -v docker &> /dev/null || ! command -v docker-compose &> /dev/null; then
    echo "Docker e Docker Compose precisam estar instalados."
    exit 1
fi

# Configuração do ambiente API
echo "Configurando API..."
cd api
if [ ! -f ".env" ]; then
    cp .env.example .env
fi
cd ..

# Configuração do ambiente Frontend
echo "Configurando Frontend..."
cd frontend
if [ ! -f ".env" ]; then
    cp .env.example .env
fi
cd ..

echo "Iniciando os containers..."
docker-compose up --build -d

# Aguarda o container laravel_app ficar pronto
echo "Aguardando o container 'laravel_app' iniciar..."
until docker exec laravel_app php artisan --version &> /dev/null
do
    sleep 2
done

# Roda os comandos artisan
echo "Gerando chave da aplicação..."
docker exec laravel_app php artisan key:generate

echo "Gerando JWT secret..."
docker exec laravel_app php artisan jwt:secret --force

echo "Setup concluído!"
echo "Agora execute './migrate.sh' para rodar as migrações."
