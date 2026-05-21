#!/usr/bin/env bash

set -e

echo "Installing apt dependencies"

# Build packages will be added during the build, but will be removed at the end.
BUILD_PACKAGES="gettext libcurl4-openssl-dev libfreetype6-dev libicu-dev libjpeg62-turbo-dev libpng-dev libpq-dev libxml2-dev libxslt-dev uuid-dev libldap2-dev"

# Packages for Postgres.
PACKAGES_POSTGRES="libpq5"

# Packages for other Moodle runtime dependenices.
PACKAGES_RUNTIME="ghostscript libaio1t64 libcurl4 libgss3 libicu76 libmcrypt-dev libxml2 libxslt1.1 libzip-dev sassc unzip zip"

# Packages for LDAP.
PACKAGES_LDAP="libldap2"

# Packages for other Moodle runtime dependenices.
# aspell - Correção ortográfica Usado no TinyMCE.
# cron - Execução em background. Usado nas Tasks.
# locales - Localização. Usado em Idiomas.
# poppler-utils - PDF para PNG. Usado no Atividade.
PACKAGES_EXTRA="aspell cron locales poppler-utils graphviz"

# echo "deb http://deb.debian.org/debian trixie main contrib non-free" >> /etc/apt/sources.list
# echo "deb http://deb.debian.org/debian trixie-updates main contrib non-free" >> /etc/apt/sources.list
# echo "deb http://security.debian.org/debian-security trixie-security main contrib non-free" >> /etc/apt/sources.list

apt-get update
apt-get upgrade -y
apt-get install -y --no-install-recommends apt-transport-https $BUILD_PACKAGES $PACKAGES_POSTGRES $PACKAGES_RUNTIME $PACKAGES_LDAP $PACKAGES_EXTRA 

echo "Installing php extensions"

# ZIP
docker-php-ext-configure zip --with-zip
docker-php-ext-install zip

docker-php-ext-install -j$(nproc) exif intl opcache pgsql soap xsl

# GD.
docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
docker-php-ext-install -j$(nproc) gd

# LDAP.
docker-php-ext-configure ldap
docker-php-ext-install -j$(nproc) ldap

# APCu, igbinary, Redis, timezonedb, uuid, excimer, PCov, xdebug 
pecl install apcu igbinary pcov timezonedb uuid excimer xdebug 
docker-php-ext-enable apcu igbinary pcov timezonedb uuid excimer xdebug

echo 'apc.enable_cli = On' >> /usr/local/etc/php/conf.d/10-docker-php-ext-apcu.ini

# Install the redis extension enabling igbinary support.
pecl install --configureoptions 'enable-redis-igbinary="yes"' redis
docker-php-ext-enable redis


echo "America/Recife" > /etc/timezone
dpkg-reconfigure -f noninteractive tzdata locales

a2enmod headers

# Keep our image size down..
pecl clear-cache
apt-get remove --purge -y $BUILD_PACKAGES
apt-get autoremove -y
apt-get clean
rm -rf /var/lib/apt/lists/*