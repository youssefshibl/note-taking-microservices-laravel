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
cp .env.example .env
php artisan key:generate


# Start PHP-FPM
# php-fpm
php artisan serve --host=0.0.0.0 --port=8000


