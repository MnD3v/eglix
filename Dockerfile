FROM php:8.2-apache

# Install system deps and PHP extensions
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    git \
 && docker-php-ext-configure intl \
 && docker-php-ext-install -j"$(nproc)" intl zip pdo_pgsql \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# Set Apache document root to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer 

WORKDIR /var/www/html
COPY . /var/www/html

# Install PHP deps only (no caching config at build time)
RUN composer install \
      --no-dev \
      --prefer-dist \
      --no-interaction \
      --optimize-autoloader \
      --no-scripts \
 && chown -R www-data:www-data storage bootstrap/cache

ENV PORT=8080
EXPOSE 8080
COPY docker/start.sh /usr/local/bin/start.sh
COPY docker/force-migrations.sh /usr/local/bin/force-migrations.sh
COPY docker/fix-administration-functions.sh /usr/local/bin/fix-administration-functions.sh
COPY docker/force-admin-migration.sh /usr/local/bin/force-admin-migration.sh
RUN chmod +x /usr/local/bin/start.sh /usr/local/bin/force-migrations.sh /usr/local/bin/fix-administration-functions.sh /usr/local/bin/force-admin-migration.sh
CMD ["/usr/local/bin/start.sh"]

