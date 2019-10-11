FROM ubuntu:18.04
LABEL maintainer="Muhammad Surya Ihsanuddin<surya.kejawen@gmail.com>"

ENV DEBIAN_FRONTEND noninteractive

RUN sed -i 's/http:\/\/archive.ubuntu.com/http:\/\/kartolo.sby.datautama.net.id/g' /etc/apt/sources.list

# Install Software
RUN apt-get update && apt-get upgrade -y
RUN apt-get install nginx-full supervisor vim -y
RUN apt-get install software-properties-common build-essential -y
RUN apt-get install curl ca-certificates -y
RUN apt-get update
RUN apt-get install php7.2 php7.2-cli php7.2-curl php7.2-intl php7.2-mbstring php7.2-xml php7.2-zip \
    php7.2-bcmath php7.2-cli php7.2-fpm php7.2-imap php7.2-json php7.2-opcache php7.2-apcu php7.2-xmlrpc \
    php7.2-bz2 php7.2-common php7.2-gd php7.2-ldap php7.2-pgsql php7.2-readline php7.2-soap php7.2-tidy php7.2-xsl php-apcu -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get remove --purge -y software-properties-common python-software-properties && \
    apt-get autoremove -y && \
    apt-get clean && \
    apt-get autoclean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* ~/.composer

# Setup Environment
ENV NGINX_WEBROOT   /semarthris/public
ENV SYMFONY_ENV     dev
ENV VARNISH_CONFIG  /etc/varnish/default.vcl
ENV CACHE_SIZE      512m
ENV VARNISHD_PARAMS -p default_ttl=3600 -p default_grace=3600
ENV VARNISH_PORT    80
ENV BACKEND_HOST    localhost
ENV BACKEND_PORT    8080

# Supervisor Configuration
ADD docker/supervisor/supervisor.conf /etc/supervisord.conf

# Nginx Configuration
ADD docker/nginx/sites-enabled/site.conf /etc/nginx/conf.d/default.conf
ADD docker/nginx/sites-enabled/php-fpm.conf /etc/nginx/conf.d/php-fpm.conf
ADD docker/nginx/nginx.conf /etc/nginx/nginx.conf
ADD docker/nginx/fastcgi_cache /etc/nginx/fastcgi_cache
ADD docker/nginx/static_files.conf /etc/nginx/static_files.conf
ADD docker/nginx/logs/site.access.log /var/log/nginx/site.access.log
ADD docker/nginx/logs/site.error.log /var/log/nginx/site.error.log
ADD docker/nginx/etc/sysctl.conf /etc/sysctl.conf
ADD docker/nginx/etc/security/limits.conf /etc/security/limits.conf

RUN mkdir -p /tmp/nginx/cache
RUN chmod 777 -R /tmp/nginx

RUN chmod 777 /var/log/nginx/site.access.log
RUN chmod 777 /var/log/nginx/site.error.log

# PHP Configuration
ADD docker/php/php.ini /etc/php/7.2/fpm/php.ini
ADD docker/php/php.ini /etc/php/7.2/cli/php.ini
ADD docker/php/php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf
RUN mkdir /run/php
RUN touch /run/php/php7.2-fpm.sock
RUN chmod 777 /run/php/php7.2-fpm.sock

# Setup Application
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN composer global require "hirak/prestissimo:~0.3" --prefer-dist --no-progress --no-suggest --optimize-autoloader --classmap-authoritative -vvv \
&& composer clear-cache

WORKDIR /semarthris

COPY composer.json ./
COPY composer.lock ./

RUN mkdir -p \
        var/cache \
        var/logs \
        var/sessions \
    && chmod 777 -R var/ \
    && composer update --prefer-dist --no-autoloader --no-scripts --no-progress --no-suggest -vvv \
    && composer clear-cache

COPY bin bin/
COPY config config/
COPY data data/
COPY public public/
COPY src src/
COPY templates templates/
COPY .env.dist ./.env.dist

RUN composer dump-autoload --optimize --classmap-authoritative

# Here we go
ADD docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 443 80

CMD ["/start.sh"]
