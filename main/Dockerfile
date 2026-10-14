ARG MOODLE_IMAGE_VERSION=5.3.0.004

############################
## STAGE: build
####################################################################################
FROM kelsoncm/moodle:$MOODLE_IMAGE_VERSION AS build

ARG AVA_IMAGE_VERSION=ava_image_not_built
ARG NVM_INSTALL_SHA256
ARG PHPUNIT_11_PHAR_SHA256

USER root

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


############################
## STAGE: dev
############################
FROM kelsoncm/moodle-for-dev:$MOODLE_IMAGE_VERSION AS dev

USER root
RUN mkdir -p /var/moodledata \
    && chown -R www-data:www-data /var/moodledata \
    && chmod 775 /var/moodledata

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
    && chmod 775 /var/moodledata

COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html

USER root
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
