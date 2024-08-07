#!/bin/sh

# Run composer install if vendor directory does not exist

composer install --ignore-platform-reqs


# Run the Laravel queue worker
php /var/www/html/artisan queue:work
