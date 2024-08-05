FROM jkaninda/laravel-php-fpm:8.3


RUN apt update && apt install -y netcat-traditional && which nc


ENTRYPOINT ["/bin/bash","entrypoint.sh"]
