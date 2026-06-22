############################
## Imagem base
####################################################################################
FROM php:8.3.30-apache-trixie


ENV DEBIAN_FRONTEND=noninteractive
ENV MOODLE_VERSION=4.5.12

ADD build/php-extensions.sh             /tmp/build/php-extensions.sh
ADD build/locale.gen                    /etc/locale.gen
ADD build/moodle-install-package.sh     /usr/local/bin/
ADD runtime/shell/                      /usr/local/bin/
ADD runtime/ini/                        /usr/local/etc/php/conf.d/
ADD runtime/apache/                     /etc/apache2/conf-available/

USER root
RUN    /tmp/build/php-extensions.sh \
    && curl -L https://github.com/moodle/moodle/archive/refs/tags/v$MOODLE_VERSION.tar.gz | tar -zx --strip-components=1 \
    && chmod -R 755 /var/www                 && chown -R www-data:www-data /var/www\
    && mkdir -p /var/moodledata              && chown -R www-data:www-data /var/moodledata \
    && mkdir -p /var/log/moodle              && chown -R www-data:www-data /var/log/moodle \
    && mkdir -p /var/log/apache2             && chown -R www-data:www-data /var/log/apache2 \
    && mkdir -p /var/log/cron                && chown -R www-data:www-data /var/log/cron \
    && a2enconf filter-useragent \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's|\(CustomLog.*access\.log\s\+combined\)$|\1 env=!dontlog|' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|\(CustomLog.*other_vhosts_access\.log\s\+vhost_combined\)$|\1 env=!dontlog|' /etc/apache2/conf-available/other-vhosts-access-log.conf

ADD --chown=www-data:www-data runtime/php/ /var/www/html/

USER www-data
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["apache2-foreground"]
