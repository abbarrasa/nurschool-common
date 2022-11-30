FROM php:8.1.0-fpm

ARG UID
EXPOSE $UID

RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN chown -R appuser:appuser /home/appuser/
RUN echo "export COLUMNS=300" >> /home/appuser/.bashrc

COPY ./php.ini /usr/local/etc/php/php.ini

RUN apt-get update \
    && apt-get install -y git acl openssl openssh-client wget zip vim libssh-dev \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip    

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
RUN composer self-update
RUN mkdir -p /appdata/lib/common

WORKDIR /appdata/lib/common