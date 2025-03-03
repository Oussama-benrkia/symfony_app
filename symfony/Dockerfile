FROM composer:lts as deps

WORKDIR /app

# Copier uniquement les fichiers nécessaires pour Composer
COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --no-scripts

# ===========================
# Étape 2 : Image de production avec PHP-FPM
# ===========================
FROM php:8.2-fpm

# Installer les dépendances système et extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    unzip git libpq-dev libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo zip mbstring pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Définir le répertoire de travail
WORKDIR /var/www/html/symfony

# Copier les fichiers du projet (sauf vendor)
COPY . . 
# Copier les dépendances installées depuis l'étape de build
COPY --from=deps /app/vendor ./vendor

# Définir les permissions correctes pour Symfony
RUN chmod +x bin/console \
    && chown -R www-data:www-data /var/www/html/symfony \
    && chmod -R 775 /var/www/html/symfony/var \
    && chown -R www-data:www-data /var/www/html/symfony/public \
    && chown -R www-data:www-data /var/www/html/symfony

# Utiliser le fichier de configuration PHP en mode production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Exposer le port de PHP-FPM

# Passer à l'utilisateur www-data pour la sécurité
USER www-data

CMD php bin/console doctrine:migrations:migrate --no-interaction && \
    php bin/console doctrine:fixtures:load --no-interaction && \
    php-fpm
