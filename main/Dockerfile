ARG MOODLE_IMAGE_VERSION=5.3.0.004

############################
## STAGE: build
####################################################################################
FROM kelsoncm/moodle:$MOODLE_IMAGE_VERSION AS build

ARG MOODLE_IMAGE_VERSION
ARG AVA_IMAGE_VERSION=$MOODLE_IMAGE_VERSION
ARG NVM_INSTALL_SHA256
ARG PHPUNIT_11_PHAR_SHA256

USER root

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl jq ca-certificates unzip zip rsync \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir -p /var/moodledata \
    && chown -R www-data:www-data /var/moodledata \
    && chmod 775 /var/moodledata

ADD moodle-install-package.sh /usr/local/bin/moodle-install-package.sh
ADD build/plugins  /tmp/build/plugins

USER www-data
WORKDIR /tmp/build/plugins
RUN moodle-install-package.sh
RUN sed -i s/ava_image_not_built/$AVA_IMAGE_VERSION/g /var/www/html/readenv.php
WORKDIR /var/www/html
RUN composer install --no-dev --classmap-authoritative --no-interaction \
    && echo "FallbackResource /r.php" > /var/www/html/public/.htaccess \
    && printf "\nglobal \$CFG;\nif (isset(\$CFG)) {\n  \$CFG->routerconfigured = env_as_bool('CFG_ROUTERCONFIGURED', true);\n}\n" >> /var/www/html/readenv.php


############################
## STAGE: dev
############################
FROM kelsoncm/moodle-for-dev:$MOODLE_IMAGE_VERSION AS dev

USER root
RUN mkdir -p /var/moodledata \
    && chown -R www-data:www-data /var/moodledata \
    && chmod 775 /var/moodledata \
    && sed -i '/<\/Directory>/i \    FallbackResource /r.php' /etc/apache2/conf-available/docker-php.conf

COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html

USER root
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]


############################
## STAGE: final
###################################################################################
FROM kelsoncm/moodle:$MOODLE_IMAGE_VERSION

USER root
RUN mkdir -p /var/moodledata \
    && chown -R www-data:www-data /var/moodledata \
    && chmod 775 /var/moodledata \
    && sed -i '/<\/Directory>/i \    FallbackResource /r.php' /etc/apache2/conf-available/docker-php.conf

COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html

USER root
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
