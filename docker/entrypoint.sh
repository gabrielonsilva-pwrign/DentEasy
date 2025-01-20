#!/bin/bash

set -e

MAX_RETRIES=10
RETRY_INTERVAL=5

# Função para verificar a conexão com o banco de dados
check_database_connection() {
    php -r "
    \$host = getenv('database_default_hostname');
    \$db   = getenv('database_default_database');
    \$user = getenv('database_default_username');
    \$pass = getenv('database_default_password');
    
    for (\$i = 0; \$i < $MAX_RETRIES; \$i++) {
        try {
            new PDO(\"mysql:host=\$host;dbname=\$db\", \$user, \$pass);
            echo 'Database connection successful';
            exit(0);
        } catch (PDOException \$e) {
            echo \"Attempt \$i: Database connection failed: \" . \$e->getMessage() . \"\n\";
            sleep($RETRY_INTERVAL);
        }
    }
    echo 'Failed to connect to the database after multiple attempts';
    exit(1);
    "
}

# Função para executar as migrações
run_migrations() {
    echo "Running database migrations..."
    php spark migrate
    if [ $? -ne 0 ]; then
        echo "Migration failed. Check the database connection and try again."
        exit 1
    fi
}

# Função para executar o seeding
run_seeding() {
    echo "Checking if seeding is necessary..."
    if [ ! -f ".seeding_completed" ]; then
        echo "Running database seeding..."
        php spark db:seed MainSeeder
        if [ $? -eq 0 ]; then
            touch .seeding_completed
        else
            echo "Seeding failed. Check the logs for more information."
            exit 1
        fi
    else
        echo "Seeding has already been performed. Skipping..."
    fi
}

# Muda para o diretório da aplicação
cd /var/www/html/denteasy

# Verifica a conexão com o banco de dados
echo "Checking database connection..."
check_database_connection

# Executa as migrações
#run_migrations

# Executa o seeding (apenas uma vez)
#run_seeding

# Inicia o comando passado para o script (geralmente o Apache)
echo "Starting the main application..."
exec "$@"