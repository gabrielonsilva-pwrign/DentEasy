#!/bin/bash

set -e

SOURCE_DIR=/var/www/html

php denteasy/spark migrate
php denteasy/spark db:seed MainSeeder

exec "$@"