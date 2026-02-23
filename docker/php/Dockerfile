FROM php:8.3-fpm

# Thiết lập thư mục làm việc
WORKDIR /var/www

# Cài đặt system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# Xóa cache để giảm dung lượng image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt PHP extensions (Postgres, BCMath, GD, v.v.)
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Cài đặt Redis extension qua PECL
RUN pecl install redis && docker-php-ext-enable redis

# Copy Composer từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thêm user để tránh lỗi permission khi chạy lệnh Artisan
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Đổi user hiện tại sang www
USER www

# Expose port 9000 cho PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]