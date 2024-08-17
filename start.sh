#!/bin/sh

# check that docker is installed

if ! [ -x "$(command -v docker)" ]; then
  echo 'Error: docker is not installed.' >&2
  exit 1
fi

# check that docker-compose is installed

if ! [ -x "$(command -v docker compose)" ]; then
  echo 'Error: docker-compose is not installed.' >&2
  exit 1
fi

# check that the docker daemon is running

if ! docker info > /dev/null 2>&1; then
  echo 'Error: docker daemon is not running.' >&2
  exit 1
fi


docker compose up -d --build



# URL of the endpoint to check
URL="http://localhost:80/auth/api/create/"

TIMEOUT=300

INTERVAL=5

START_TIME=$(date +%s)

check_status() {
    local url=$1
    curl -s -o /dev/null -w "%{http_code}" "$url"
}

while true; do
    STATUS_CODE=$(check_status "$URL")
    
    if [ "$STATUS_CODE" -ne 502 ]; then
        echo "Endpoint is up. Status code: $STATUS_CODE"
        exit 0
    fi

    # Calculate elapsed time
    CURRENT_TIME=$(date +%s)
    ELAPSED_TIME=$((CURRENT_TIME - START_TIME))

    if [ "$ELAPSED_TIME" -ge "$TIMEOUT" ]; then
        echo "Timeout reached. Endpoint did not recover."
        exit 1
    fi

    echo "Endpoint returned 502. Waiting..."
    sleep "$INTERVAL"
done









