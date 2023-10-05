# Use an official PHP runtime as a parent image
FROM php:8.0-fpm

# Set the working directory to /var/www
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Copy composer.lock and composer.json to the working directory
COPY composer.lock composer.json ./

# Install Composer dependencies
RUN composer install

# Copy the rest of the application code to the working directory
COPY . .

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
