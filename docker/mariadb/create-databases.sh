#!/bin/bash
#script adapted from: https://donlalicon.dev/blog/mysql-docker-container-with-multiple-databases/

set -eo pipefail

_create_custom_database() {
  docker_process_sql --database=mysql <<-EOSQL
    CREATE DATABASE \`$1\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES ON \`$1\`.* TO '$MYSQL_USER'@'%';
EOSQL
}

mysql_note "Creating custom databases"
for DATABASE_NAME in ${MYSQL_DATABASES_TO_CREATE//;/ }; do
  mysql_note "Creating ${DATABASE_NAME}"
  _create_custom_database $DATABASE_NAME
done
