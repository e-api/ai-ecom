<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Docker Setup (aaPanel + Docker)

### Prerequisites
- aaPanel with Nginx installed
- Docker and Docker Compose
- Domain pointed to your server

### One-Time Setup

```bash
cd /www/wwwroot/default/ai-ecom && cp .env .env.example

# 1. Build and start containers
docker compose up -d --build

# 2. Fix permissions (match Docker www-data UID 33)
chown -R 33:33 .

# 3. Remove aaPanel open_basedir restriction
# unlock the .user.ini file:
sudo chattr -i /www/wwwroot/default/ecom/public/.user.ini
rm -f public/.user.ini

# or add this line to .user.ini: 
cat > /www/wwwroot/default/your_project/public/.user.ini << 'EOF'
  open_basedir=/www/wwwroot/default/your_project/:/tmp/:/var/www/:/var/www/public/
EOF

# 4. Laravel setup
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate --seed

# 5. Storage link (use HOST path)
rm -f public/storage
ln -s /www/wwwroot/default/ai-ecom/storage/app/public public/storage
chmod -R 775 storage

curl -s -X PUT https://stg.1byte.pp.ua/s3/ecom-bucket

# 6. Filament & Livewire assets
docker compose exec php php artisan filament:assets
docker compose exec php php artisan vendor:publish --tag=livewire:assets --force

# 7. Optimize
docker compose exec php php artisan optimize

# 8. Start/Stop
docker compose up -d
docker compose down

# 9. Artisan
docker compose exec php php artisan migrate
docker compose exec php php artisan cache:clear

# 10. Composer
docker compose exec php composer install
docker compose exec php composer update

# 11. PostgreSQL
docker compose exec postgres psql -U postgres -d ecom

# View logs
docker compose logs -f

# === Nginx: add these following things ===
# 1. Setup URL Rewrite for Laravel in Nginx config: 
# For files with extensions (images, uploads)
location ~ ^/s3/.*\. {
    allow all;
    modsecurity off;
    rewrite ^/s3/(.*) /$1 break;
    proxy_pass http://127.0.0.1:4566;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    add_header Access-Control-Allow-Origin $http_origin always;
    add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS" always;
    add_header Access-Control-Allow-Headers "Content-Type, Authorization, X-Amz-*, X-Requested-With" always;
    add_header Access-Control-Expose-Headers "ETag, X-Amz-*" always;
    if ($request_method = OPTIONS) {
        return 204;
    }
    client_max_body_size 100M;
}

# For all other S3 requests (listings, bucket operations, no extension)
location /s3/ {
    allow all;
    modsecurity off;
    rewrite ^/s3/(.*) /$1 break;
    proxy_pass http://127.0.0.1:4566;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    add_header Access-Control-Allow-Origin $http_origin always;
    add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS" always;
    add_header Access-Control-Allow-Headers "Content-Type, Authorization, X-Amz-*, X-Requested-With" always;
    add_header Access-Control-Expose-Headers "ETag, X-Amz-*" always;
    if ($request_method = OPTIONS) {
        return 204;
    }
    client_max_body_size 100M;
}

# Security Section
set $bypass_security 0;
if ($uri ~* "^/s3/") {
    set $bypass_security 1;
}

# Skip geo-block for S3 proxy
    if ($uri ~* "^/s3/") {
        set $check_geo 0;
    }
# 2. Set Directory at:
/www/wwwroot/default/your-project  
# and Running Directory at: 
/www/wwwroot/default/your-project/public
# or in your Nginx config, set: 
root /www/wwwroot/default/your-project/public;
# 3. In PHP block: 
// Remove or comment out any existing PHP location blocks, then add this:
#include enable-php-00.conf;
location ~ \.php$ {
  include fastcgi.conf;
  fastcgi_pass 127.0.0.1:9000;
  fastcgi_param SCRIPT_FILENAME /var/www/public$fastcgi_script_name;
}
  
location = /index.php {
  include fastcgi.conf;
  fastcgi_pass 127.0.0.1:9000;
  fastcgi_param SCRIPT_FILENAME /var/www/public$fastcgi_script_name;
}
# 4. Storage block:
location /storage/ {
  alias /www/wwwroot/default/your-project/storage/app/public/;
  expires 30d;
  add_header Cache-Control "public, immutable";
}
```

## Docker Setup (aaPanel Reverse Proxy + Docker)

### Prerequisites
- aaPanel with Nginx and PHP Service installed on Host
- Docker [Postgres] running on Host
- Domain pointed to your server

### One-Time Setup

```bash
cd /www/wwwroot/default/ai-ecom && cp .env .env.example

# Install 
composer install

# Update
composer update

# Key Generate
php artisan key:generate

# Migrate Database
php artisan migrate --seed

# Storage Link
php artisan storage:link

# Cache clear
php artisan cache:clear

# Logs
compose logs -f

# === Nginx: add these following things ===
# proxy_set_header server block
proxy_set_header X-Forwarded-Proto $scheme; # Add this
proxy_set_header X-Forwarded-Host $host; # Add this
proxy_set_header X-Forwarded-Port $server_port; # Add this

```
## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
