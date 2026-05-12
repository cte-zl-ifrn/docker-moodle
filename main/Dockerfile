ARG MOODLE_IMAGE_VERSION=4.5.11.043

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
## STAGE: final
###################################################################################
FROM ctezlifrn/avamoodlebase:$MOODLE_IMAGE_VERSION

COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html

USER www-data
WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["apache2-foreground"]
