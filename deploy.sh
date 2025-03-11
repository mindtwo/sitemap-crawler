#! usr/bin/bash

USR=$(stat -c '%U' ./composer.json)
runuser -u $USR -- /bin/php8.2 /usr/local/sbin/composer install --no-dev
php8.2 artisan config:clear
php8.2 artisan route:clear
php8.2 artisan view:clear
php8.2 artisan event:clear
php8.2 artisan clear-compiled
rm -rf resources/views/modules/*
php8.2 artisan vendor:publish --tag="pricing-module-views" --force
php8.2 artisan vendor:publish --tag="service-module-views" --force
php8.2 artisan view:cache
php8.2 artisan config:cache
php8.2 artisan route:cache
php8.2 artisan icons:cache
