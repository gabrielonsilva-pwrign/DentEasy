#!/bin/bash

set -e

SOURCE_DIR=/var/www/html/denteasy

/usr/local/bin/wait-for-it.sh -h "${database_default_hostname}" -p 3306 -t 300

php spark migrate
php spark db:seed MainSeeder

exec "$@"