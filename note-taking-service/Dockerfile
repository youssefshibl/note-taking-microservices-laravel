FROM jkaninda/laravel-php-fpm:8.3


RUN apt update && apt install -y netcat-traditional && which nc


# add extension=sockets to php.ini
RUN echo "extension=sockets" >> /usr/local/etc/php/php.ini

RUN docker-php-ext-install sockets

ENTRYPOINT ["/bin/bash","entrypoint.sh"]
