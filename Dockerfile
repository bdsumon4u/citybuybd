FROM node:22-alpine AS assets

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./
RUN npm run build


FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

ENV LOG_CHANNEL=stderr

RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
    bcmath \
    exif \
    gd \
    intl \
    pdo_mysql \
    zip \
    opcache \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock /var/www/html/
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php-custom.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p /run/nginx /var/log/supervisor /var/www/html/backend /var/www/html/frontend \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/backend

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
