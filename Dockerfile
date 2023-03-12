FROM php:8.0-apache-bullseye

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
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# move the apache document root to the laravel public directory
RUN sed -i 's/\/var\/www\/html/\/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf

# enable apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# start web server
CMD service apache2 start && \
    chmod -R 777 /var/www/html/storage && \
    composer install && \
    php artisan key:generate && \
    php artisan migrate && \
    tail -F /var/log/apache2/access.log && \
    tail -F /var/log/apache2/error.log

# USER $user
