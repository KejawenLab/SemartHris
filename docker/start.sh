#!/usr/bin/env bash
set -e

for name in NGINX_WEBROOT
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

for name in APP_ENV
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

for name in APP_SECRET
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

for name in DATABASE_DRIVER
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

for name in DATABASE_SERVER_VERSION
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

for name in DATABASE_CHARSET
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

for name in DATABASE_URL
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

for name in REDIS_URL
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /etc/nginx/conf.d/default.conf
done

if [ "$APP_ENV" = 'prod' ]; then
	composer install --prefer-dist --no-dev --no-progress --no-suggest --optimize-autoloader --classmap-authoritative
else
	composer install --prefer-dist --no-progress --no-suggest --optimize-autoloader --classmap-authoritative
fi

chmod 777 -R var/

/usr/bin/supervisord -n -c /etc/supervisord.conf
