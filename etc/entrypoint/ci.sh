#!/bin/sh
set -e

if [[ $((10#$(php -r 'echo PHP_VERSION_ID;'))) -ge 80200 ]]; then
    export PHP_CS_FIXER_IGNORE_ENV=true
fi
./vendor/bin/php-cs-fixer fix --dry-run -v --using-cache=no
./vendor/bin/phpstan analyse -c ./phpstan.neon
./vendor/bin/phpunit -d memory_limit=512M --do-not-cache-result
