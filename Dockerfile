FROM php:8.2-apache

# Copia o código da aplicação para o diretório web padrão
COPY . /var/www/html

# Habilita o módulo de reescrita do Apache (essencial para URLs amigáveis)
RUN a2enmod rewrite

# Define o DocumentRoot para o diretório 'public'
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Garante que o Apache possa usar as diretivas do .htaccess no diretório public
RUN sed -i '/<Directory \/var\/www\/html\/public>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html