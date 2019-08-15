FROM ubuntu:18.04

MAINTAINER Muhammad Surya Ihsanuddin<surya.kejawen@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

ADD docker/apt/sources.list /etc/apt/sources.list

# Install Software
RUN apt update && apt upgrade -y
RUN apt install nginx-full supervisor vim software-properties-common curl ca-certificates unzip -y
RUN apt update
RUN apt install php php-cli php-curl php-intl php-mbstring php-xml php-zip \
    php-bcmath php-cli php-fpm php-imap php-json php-opcache php-xmlrpc \
    php-bz2 php-common php-gd php-ldap php-pgsql php-readline php-soap php-tidy php-xsl php-apcu php-igbinary php-redis -y

RUN curl -o /usr/local/bin/composer https://getcomposer.org/composer.phar && chmod a+x /usr/local/bin/composer

RUN apt remove --purge -y software-properties-common && \
    apt autoremove -y && \
    apt clean && \
    apt autoclean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* ~/.composer

# Nginx Configuration
ADD docker/nginx/sites-enabled/site.conf /etc/nginx/conf.d/default.conf
ADD docker/nginx/sites-enabled/php-fpm.conf /etc/nginx/conf.d/php-fpm.conf
ADD docker/nginx/nginx.conf /etc/nginx/nginx.conf
ADD docker/nginx/fastcgi_cache /etc/nginx/fastcgi_cache
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

RUN composer config -g repositories.packagist composer https://packagist.jp
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-suggest --optimize-autoloader --classmap-authoritative -vvv && \
   composer clear-cache

WORKDIR /semart

COPY composer.json ./
COPY composer.lock ./

RUN mkdir -p var/cache var/log var/sessions && \
    chmod 777 -R var/ && \
    composer install --prefer-dist --no-autoloader --no-scripts --no-suggest -vvv && \
    composer clear-cache

COPY bin bin/
COPY config config/
COPY public public/
COPY src src/
COPY templates templates/
COPY translations translations/
COPY fixtures fixtures/
COPY .env ./

RUN composer dump-autoload --optimize --classmap-authoritative

# Supervisor Configuration
ADD docker/supervisor/supervisor.conf /etc/supervisord.conf

RUN chmod 755 -R config/

# Here we go
ADD docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 443 80

CMD ["/start.sh"]
