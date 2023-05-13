FROM php:7.4-apache

# Enable the mod_rewrite module
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli

# Copy the application files to the container
COPY . /var/www/html/

# Set the working directory to the application directory
WORKDIR /var/www/html/

# Expose port 80 for Apache
EXPOSE 80
