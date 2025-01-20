#!/bin/bash

set -e

SOURCE_DIR=/var/www/html/denteasy
MAX_RETRIES=30
RETRY_INTERVAL=5

# Função para executar as migrações
run_migrations() {
    echo "Running database migrations..."
    php $SOURCE_DIR/spark migrate --force
    if [ $? -ne 0 ]; then
        echo "Migration failed. Check the database connection and try again."
        exit 1
    fi
}

# Função para executar o seeding
run_seeding() {
    echo "Checking if seeding is necessary..."
    if [ ! -f "$SOURCE_DIR/.seeding_completed" ]; then
        echo "Running database seeding..."
        php $SOURCE_DIR/spark db:seed MainSeeder
        if [ $? -eq 0 ]; then
            touch "$SOURCE_DIR/.seeding_completed"
        else
            echo "Seeding failed. Check the logs for more information."
            exit 1
        fi
    else
        echo "Seeding has already been performed. Skipping..."
    fi
}


# Executa as migrações
run_migrations

# Executa o seeding (apenas uma vez)
run_seeding

# Inicia o comando passado para o script (geralmente o Apache)
echo "Starting the main application..."
exec "$@"