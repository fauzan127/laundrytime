# ---- FRONTEND BUILD ----
FROM node:18 AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm install --no-audit --no-fund --legacy-peer-deps

COPY . .
RUN npm run build

# ---- PHP + NGINX ----
FROM coollabsio/php-nginx:8.2

WORKDIR /app

# Copy entire app
COPY . .

# Copy built frontend files
COPY --from=frontend /app/public ./public

# Laravel setup
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

RUN php artisan storage:link || true

# Permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
