# 使用官方 PHP 镜0
FROM php:8.1-apache

# 设置工作目录
WORKDIR /var/www/html

    

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip

# 启用 Apache 重写模块
RUN a2enmod rewrite

# 复制项目文件
COPY . .

# 设置文件权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 暴露端口
EXPOSE 80
CMD ["apache2-foreground"]
