#!/bin/bash

set -e

SOURCE_DIR=/var/www/html/denteasy

# Função para executar as migrações
run_migrations() {
    echo "Running database migrations..."
    php $SOURCE_DIR/spark migrate
}

# Função para executar o seeding
run_seeding() {
    echo "Checking if seeding is necessary..."
    # Verifica se o seeding já foi executado
    if [ ! -f "$SOURCE_DIR/.seeding_completed" ]; then
        echo "Running database seeding..."
        php $SOURCE_DIR/spark db:seed MainSeeder
        # Cria um arquivo para marcar que o seeding foi concluído
        touch "$SOURCE_DIR/.seeding_completed"
    else
        echo "Seeding has already been performed. Skipping..."
    fi
}

# Executa as migrações
run_migrations

# Executa o seeding (apenas uma vez)
run_seeding

# Inicia o comando passado para o script (geralmente o Apache)
exec "$@"
