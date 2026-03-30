# Base image
FROM php:8.2-apache

# Install mysqli & openssl
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 10000
EXPOSE 10000