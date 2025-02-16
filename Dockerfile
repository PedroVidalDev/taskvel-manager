FROM php:8.2-cli

WORKDIR /var/www/html

# Instalar dependências do sistema e extensões do PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \  # Dependência para o driver do PostgreSQL
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd pdo_pgsql pgsql  # Instala o driver do PostgreSQL

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar o código da aplicação
COPY . .

# Instalar dependências do Composer
RUN composer install --no-dev --optimize-autoloader

# Configurar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Executar as migrações
RUN php artisan migrate --force

# Expor a porta 8000 (padrão do servidor embutido do PHP)
EXPOSE 8000

# Comando para iniciar o servidor embutido do PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
