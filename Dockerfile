FROM php:8.1-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libzip-dev \
  zip \
  unzip \
  libssl-dev \
  sudo

RUN curl -sL https://deb.nodesource.com/setup_19.x -o /tmp/nodesource_setup.sh
RUN bash /tmp/nodesource_setup.sh
RUN apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Supervisor
# RUN mkdir -p /var/log/supervisor
# COPY "docker-compose/supervisor/config/*" /etc/supervisor/conf.d/

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd

# Install redis extension
# RUN pecl install redis \
# 	&& docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
RUN usermod -aG sudo $user
RUN echo "${user} ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

COPY "docker-compose/php/php.ini-production" "$PHP_INI_DIR/php.ini"

# Set working directory
WORKDIR /var/www

COPY "docker-compose/docker-php-entrypoint.sh" /var/www/docker-compose/docker-php-entrypoint.sh
COPY ".gitignore" /var/www/.gitignore

USER $user

CMD /bin/bash /var/www/docker-compose/docker-php-entrypoint.sh && tail -f .gitignore
