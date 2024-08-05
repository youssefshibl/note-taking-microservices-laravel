# entrypoint.sh
#!/bin/sh

composer install --ignore-platform-reqs


# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
while ! nc -z $DB_HOST 3306; do
  sleep 1
done

# Run Laravel migrations
php artisan migrate --force

# Start PHP-FPM
php-fpm
