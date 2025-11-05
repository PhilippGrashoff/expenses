FROM php:8.3-apache

# Set up required PHP extensions and their dependencies
ARG PHP_EXTENSIONS="pdo pdo_mysql mysqli intl zip xdebug"
ARG SYSTEM_PACKAGES="libicu-dev libzip-dev git mariadb-client"

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get -y update
RUN apt-get install -y ${SYSTEM_PACKAGES}

RUN install-php-extensions ${PHP_EXTENSIONS}

# Enable mod-rewrite module in apache.
RUN a2enmod rewrite

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND