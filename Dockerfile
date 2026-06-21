# Tells Render to use a standard web server with PHP 8.2
FROM php:8.2-apache

# Copies your PHP files into the server's web directory
COPY . /var/www/html/

# Opens the standard web port
EXPOSE 80
