#!/bin/bash
touch /var/www/html/storage/logs/laravel.log
chown nginx: /var/www/html/storage/logs/laravel.log
cd /var/www/html
php artisan migrate --force
