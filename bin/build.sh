#!/bin/sh
set -e

if [ -z "$APP_ROLE" ]; then
    printf '%s\n' "[$(date +"%T")] [Error] Application role is not set"
    exit 1
fi

printf '%s\n' "[$(date +"%T")] [Building project for \"$APP_ROLE\" role"

cd "$(dirname "$0")/.."

# GitLab
if [ -n "$GITLAB_ACCESS_TOKEN" ]; then
    printf '%s\n' "[$(date +"%T")] Configuring GitLab token..." >&1
    /usr/local/bin/composer config -g gitlab-token.gitlab.accrela.io "$GITLAB_ACCESS_TOKEN"
fi

if [ "$APP_ROLE" = "dev" ]; then
    printf '%s\n' "[$(date +"%T")] Installing vendors"
    php -d memory_limit=-1 /usr/local/bin/composer install --no-scripts

    printf '%s\n' "[$(date +"%T")] Creating database"
    php bin/console doctrine:database:create

    printf '%s\n' "[$(date +"%T")] Setting up migrations"
    php bin/console doctrine:migrations:migrate -n

    printf '%s\n' "[$(date +"%T")] Loading fixtures"
    php bin/console doctrine:fixtures:load -n
fi

cd ->/dev/null
