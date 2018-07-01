FROM php:7.2-apache

# Install Util curl, wget etc
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install --no-install-recommends --no-install-suggests -y \
    locales \
    curl \
    wget \
    git \
    vim \
    mlocate \
    unzip \
    cron \
    gnupg2 \
    acl \
    htop

# Installation de composer
# ========================
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# Install YARN
# ============
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get install yarn -y

# purge source list
RUN rm -rf /var/lib/apt/lists/*

RUN export COMPOSER_ALLOW_SUPERUSER=1 \
    && echo "COMPOSER_ALLOW_SUPERUSER : "$COMPOSER_ALLOW_SUPERUSER

# Copy main script
# COPY ../install.sh /var/www/html/install_lexik.sh
# RUN chmod u+rwx /var/www/html/install_lexik.sh

EXPOSE 8080:80
