# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Copy custom configurations
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Create the log file to be able to run tail
RUN touch /var/log/apache2/error.log
RUN touch /var/log/apache2/access.log

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
