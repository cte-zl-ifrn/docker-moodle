############################
## Imagem base
####################################################################################
FROM php:8.3.30-apache-trixie

ENV DEBIAN_FRONTEND=noninteractive
ENV MOODLE_VERSION=4.5.10

ADD build/php-extensions.sh             /tmp/build/php-extensions.sh
ADD build/locale.gen                    /etc/locale.gen
ADD build/moodle-install-package.sh     /usr/local/bin/
ADD runtime/shell/                      /usr/local/bin/
ADD runtime/ini/                        /usr/local/etc/php/conf.d/

USER root
RUN    /tmp/build/php-extensions.sh \
    && curl -L https://github.com/moodle/moodle/archive/refs/tags/v$MOODLE_VERSION.tar.gz | tar -zx --strip-components=1 \
    && chmod -R 755 /var/www                 && chown -R www-data:www-data /var/www\
    && mkdir -p /var/moodledata              && chown -R www-data:www-data /var/moodledata \
    && mkdir -p /var/log/moodle              && chown -R www-data:www-data /var/log/moodle \
    && mkdir -p /var/log/apache2             && chown -R www-data:www-data /var/log/apache2 \
    && mkdir -p /var/log/cron                && chown -R www-data:www-data /var/log/cron

ADD --chown=www-data:www-data runtime/php/ /var/www/html/

USER www-data
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["apache2-foreground"]
