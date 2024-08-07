#!/bin/sh

# Run composer install if vendor directory does not exist

composer install --ignore-platform-reqs

RABBITMQ_HOST="rabbitmq"
RABBITMQ_PORT=5672

echo "Checking if RabbitMQ is up and running on ${RABBITMQ_HOST}:${RABBITMQ_PORT}..."

# Function to check if RabbitMQ is running
check_rabbitmq() {
    nc -z $RABBITMQ_HOST $RABBITMQ_PORT
}

# Loop until RabbitMQ is up
while ! check_rabbitmq; do
    echo "RabbitMQ is not available. Waiting..."
    sleep 5  # Wait for 5 seconds before checking again
done

echo "RabbitMQ is up and running!"

# Run the Laravel queue worker
php /var/www/html/artisan queue:work --tries=3
