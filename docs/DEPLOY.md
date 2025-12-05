# 🚀 Deployment Guide

Complete guide for deploying the Laravel Files Manager System to production.

## 📋 Table of Contents

- [Pre-Deployment Checklist](#-pre-deployment-checklist)
- [Docker Deployment](#-docker-deployment)
- [Traditional Server Deployment](#-traditional-server-deployment)
- [Cloud Platform Deployment](#-cloud-platform-deployment)
- [Post-Deployment](#-post-deployment)
- [Monitoring & Maintenance](#-monitoring--maintenance)

---

## ✅ Pre-Deployment Checklist

Before deploying, ensure you have:

- [ ] Production database configured (MySQL/PostgreSQL recommended)
- [ ] Cloud storage configured (S3, GCS, or Azure) - **Highly Recommended**
- [ ] SSL certificate configured
- [ ] Environment variables set correctly
- [ ] Application key generated
- [ ] Database migrations ready
- [ ] Frontend assets built
- [ ] Error tracking configured (optional)
- [ ] Backup strategy in place
- [ ] Monitoring tools set up (optional)

---

## 🐳 Docker Deployment

### Production Docker Setup

The easiest way to deploy is using Docker Compose:

```bash
# 1. Clone repository on production server
git clone <repository-url>
cd laravel-files-manager-system

# 2. Copy and configure environment
cp .env.example .env
nano .env  # Edit with production values

# 3. Build and start containers
docker-compose up -d --build

# 4. Verify containers are running
docker-compose ps
```

### Production Environment Variables

Update `.env` with production values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Storage (use cloud storage in production)
FILE_STORAGE_DISK=s3  # or gcs, azure

# AWS S3 (if using S3)
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Docker Production Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f app

# Restart services
docker-compose restart

# Update application
git pull
docker-compose up -d --build

# Run migrations
docker-compose exec app php artisan migrate --force

# Clear caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

### Docker with Reverse Proxy (Nginx)

If you're using an external Nginx as reverse proxy:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

---

## 🖥️ Traditional Server Deployment

### Step 1: Server Requirements

- **Operating System:** Ubuntu 22.04 LTS or similar
- **Web Server:** Nginx or Apache
- **PHP:** 8.3+ with extensions (pdo, pdo_mysql, mbstring, gd, zip, etc.)
- **Database:** MySQL 8.0+ or PostgreSQL 14+
- **Node.js:** 18.0+ (for building assets)
- **Composer:** 2.0+

### Step 2: Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
    php8.3-bcmath php8.3-gd php8.3-zip php8.3-curl -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### Step 3: Clone and Setup Application

```bash
# Clone repository
cd /var/www
sudo git clone <repository-url> files-manager
cd files-manager

# Set permissions
sudo chown -R www-data:www-data /var/www/files-manager
sudo chmod -R 755 /var/www/files-manager
sudo chmod -R 775 storage bootstrap/cache

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### Step 4: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit environment file
nano .env
```

Set production values in `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=files_manager
DB_USERNAME=files_user
DB_PASSWORD=secure_password

FILE_STORAGE_DISK=s3  # Use cloud storage
```

### Step 5: Database Setup

```bash
# Create database
mysql -u root -p
```

```sql
CREATE DATABASE files_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'files_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON files_manager.* TO 'files_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Run migrations
php artisan migrate --force
```

### Step 6: Configure Nginx

Create `/etc/nginx/sites-available/files-manager`:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/files-manager/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/files-manager /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 7: SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal is set up automatically
```

### Step 8: Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### Step 9: Setup Queue Worker (Optional)

If using queues, set up a supervisor process:

```bash
# Install Supervisor
sudo apt install supervisor -y

# Create config file
sudo nano /etc/supervisor/conf.d/files-manager-worker.conf
```

Add:

```ini
[program:files-manager-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/files-manager/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/files-manager/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start files-manager-worker:*
```

---

## ☁️ Cloud Platform Deployment

### AWS (Elastic Beanstalk / EC2)

1. **Create EC2 Instance** or use Elastic Beanstalk
2. **Install dependencies** (PHP, Composer, Node.js)
3. **Clone repository** and configure
4. **Configure RDS** for database
5. **Configure S3** for file storage
6. **Set up Application Load Balancer** with SSL
7. **Configure environment variables** in AWS console

### Google Cloud Platform

1. **Create Compute Engine instance**
2. **Install dependencies**
3. **Use Cloud SQL** for database
4. **Use Cloud Storage** for files
5. **Set up Cloud Load Balancer**
6. **Configure SSL certificate**

### DigitalOcean App Platform

1. **Connect GitHub repository**
2. **Configure build settings:**
   - Build command: `composer install --no-dev && npm install && npm run build`
   - Run command: `php artisan serve --host=0.0.0.0 --port=8080`
3. **Add MySQL database**
4. **Configure environment variables**
5. **Set up managed database**

### Heroku

```bash
# Install Heroku CLI
heroku login

# Create app
heroku create your-app-name

# Add MySQL addon
heroku addons:create cleardb:ignite

# Set environment variables
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set FILE_STORAGE_DISK=s3

# Deploy
git push heroku main

# Run migrations
heroku run php artisan migrate --force
```

---

## 🔧 Post-Deployment

### 1. Verify Application

```bash
# Check application status
curl -I https://your-domain.com

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Test file upload
# Upload a test file through the web interface
```

### 2. Setup Cron Jobs

Add to crontab (`crontab -e`):

```bash
* * * * * cd /var/www/files-manager && php artisan schedule:run >> /dev/null 2>&1
```

Or for Docker:

```bash
docker-compose exec app php artisan schedule:work
```

### 3. Setup Backups

```bash
# Create backup script
nano /usr/local/bin/backup-files-manager.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/files-manager"
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u files_user -p'password' files_manager > $BACKUP_DIR/db_$DATE.sql

# Backup storage (if using local storage)
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz /var/www/files-manager/storage/app/public

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

Make executable:

```bash
chmod +x /usr/local/bin/backup-files-manager.sh
```

Add to crontab:

```bash
0 2 * * * /usr/local/bin/backup-files-manager.sh
```

### 4. Monitor Logs

```bash
# Application logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# PHP-FPM logs
tail -f /var/log/php8.3-fpm.log
```

---

## 📊 Monitoring & Maintenance

### Health Checks

Create a health check endpoint:

```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'storage' => Storage::disk(config('files.storage_disk'))->exists('.') ? 'accessible' : 'error',
    ]);
});
```

### Performance Monitoring

- **Laravel Telescope** (development only)
- **Laravel Debugbar** (development only)
- **New Relic** or **Datadog** for production
- **Sentry** for error tracking

### Regular Maintenance Tasks

```bash
# Clear caches (weekly)
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optimize (after updates)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Update dependencies (monthly)
composer update
npm update

# Check for expired files (daily via scheduler)
php artisan tinker
>>> File::where('expires_at', '<', now())->delete();
```

### Security Updates

```bash
# Update Laravel
composer update laravel/framework

# Update dependencies
composer update
npm update

# Check for vulnerabilities
composer audit
npm audit
```

---

## 🆘 Troubleshooting

### Common Issues

**Issue: 500 Internal Server Error**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Check permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Clear caches
php artisan config:clear
php artisan cache:clear
```

**Issue: Database Connection Failed**
```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check .env file
cat .env | grep DB_

# Verify database exists
mysql -u username -p -e "SHOW DATABASES;"
```

**Issue: Storage Not Accessible**
```bash
# Check storage link
ls -la public/storage

# Recreate link
php artisan storage:link

# Check permissions
chmod -R 775 storage
```

**Issue: Assets Not Loading**
```bash
# Rebuild assets
npm run build

# Check public/build directory
ls -la public/build

# Clear view cache
php artisan view:clear
```

---

## 📚 Additional Resources

- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [Nginx Configuration Guide](https://nginx.org/en/docs/)
- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)
- [Docker Production Best Practices](https://docs.docker.com/develop/dev-best-practices/)

---

**Last Updated:** December 2025

