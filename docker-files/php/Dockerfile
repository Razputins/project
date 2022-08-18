FROM php:7.4-fpm

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    supervisor \
 && docker-php-ext-install sockets pdo_mysql

COPY supervisord.conf /etc/supervisor
COPY supervisor/* /etc/supervisor/conf.d

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]