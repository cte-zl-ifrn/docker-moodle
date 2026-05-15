ARG MOODLE_IMAGE_VERSION=4.5.11.042

############################
## STAGE: build
####################################################################################
FROM ctezlifrn/avamoodlebase:$MOODLE_IMAGE_VERSION AS build

ARG AVA_IMAGE_VERSION=ava_image_not_built
ADD build/plugins /tmp/build/plugins
USER www-data
WORKDIR /tmp/build/plugins
RUN for plugin in *.zip ; do /usr/local/bin/moodle-install-package.sh $plugin; done
RUN sed -i s/ava_image_not_built/$AVA_IMAGE_VERSION/g /var/www/html/readenv.php
# Moodle timeline format customization: enable message defaults by replacing
# MESSAGE_DEFAULT_LOGGEDIN + MESSAGE_DEFAULT_LOGGEDOFF with MESSAGE_DEFAULT_ENABLED
# in course/format/timeline/db/messages.php during image build.
RUN sed -i s/MESSAGE_DEFAULT_LOGGEDIN\ \+\ MESSAGE_DEFAULT_LOGGEDOFF/MESSAGE_DEFAULT_ENABLED/g /var/www/html/course/format/timeline/db/messages.php


############################
## STAGE: dev
############################
FROM build AS dev

ENV NVM_DIR=/usr/local/nvm
ENV NODE_VERSION=22.11.0
ENV PATH=${NVM_DIR}/versions/node/v${NODE_VERSION}/bin:${PATH}

USER root

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        bash curl ca-certificates git xz-utils \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir -p ${NVM_DIR} \
    && curl -fsSL https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash \
    && . ${NVM_DIR}/nvm.sh \
    && nvm install ${NODE_VERSION} \
    && nvm use ${NODE_VERSION} \
    && npm install -g grunt-cli

# Install PHPUnit globally (PHPUnit 11 for PHP 8.3 compatibility)
RUN curl -L https://phar.phpunit.de/phpunit-11.phar -o /usr/local/bin/phpunit \
    && chmod +x /usr/local/bin/phpunit

# Configure PCOV for PHPUnit coverage
RUN echo "pcov.enabled=1" >> /usr/local/etc/php/conf.d/20-pcov.ini \
    && echo "pcov.directory=." >> /usr/local/etc/php/conf.d/20-pcov.ini

USER www-data
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["apache2-foreground"]


############################
## STAGE: final
###################################################################################
FROM ctezlifrn/avamoodlebase:$MOODLE_IMAGE_VERSION

COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html

USER www-data
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["apache2-foreground"]
