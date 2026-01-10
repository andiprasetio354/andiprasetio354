# Deployment Guide

Complete step-by-step deployment instructions for various hosting platforms.

## Quick Reference

| Platform | Difficulty | Setup Time | Cost | Best For |
|----------|-----------|-----------|------|----------|
| **Shared Hosting** | Easy | 15 min | $2-5/mo | Budget-friendly |
| **DigitalOcean Droplet** | Medium | 30 min | $5-12/mo | Full control |
| **DigitalOcean App Platform** | Easy | 10 min | $5-20/mo | Managed, auto-scaling |
| **Laravel Forge** | Easy | 10 min | $12+/mo | Professional, managed |

---

## 1. Shared Hosting (cPanel, Plesk, etc.)

### Hosting Requirements
- ✅ PHP 8.2+ with: pdo_mysql, pdo_sqlite, curl, mbstring, xml, zip
- ✅ Composer support
- ✅ Git support (or FTP upload)
- ✅ 100MB+ disk space
- ✅ MySQL or SQLite database

### Providers Supporting This Setup
- Namecheap (Stellar, Elite)
- Hostinger
- SiteGround
- BlueHost
- DreamHost

### Deployment Steps

**1. Prepare Code**
```bash
# On your local machine
git clone https://github.com/andiprasetio354/andiprasetio354.git
cd andiprasetio354
git checkout portfolio-dev
```

**2. Upload via Git (Recommended)**

Via SSH:
```bash
ssh username@your-domain.com
cd public_html  # or your web root directory

# Clone repository
git clone https://github.com/andiprasetio354/andiprasetio354.git .
git checkout portfolio-dev
```

Or via cPanel File Manager → Upload files manually (if Git not available)

**3. Install Dependencies**

Via SSH:
```bash
# Install composer packages
composer install --no-dev --optimize-autoloader

# Install node packages and build assets
npm install
npm run build
```

**4. Configure Environment**
```bash
# Copy example env
cp .env.example .env

# Generate app key
php artisan key:generate
```

**5. Edit `.env` File**

Via cPanel File Manager or SSH:
```env
APP_NAME=Portfolio
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (ask hosting provider for credentials)
DB_CONNECTION=mysql
DB_HOST=localhost  # or specific host
DB_DATABASE=yourdatabase
DB_USERNAME=yourusername
DB_PASSWORD=yourpassword

# Mail settings
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```

**6. Setup Database**
```bash
# Run migrations
php artisan migrate --force

# (Optional) Seed with sample user
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password123')]);
exit
```

**7. Fix File Permissions**
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage bootstrap/cache/*
```

**8. Create Storage Symlink**
```bash
php artisan storage:link
```

**9. Set Public Root**

Via cPanel:
1. Go to Public HTML / Document Root
2. Set it to your `/public` folder
3. Or create `.htaccess` redirect if needed

**10. Cache Configuration**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**11. Verify Installation**

Visit `https://your-domain.com` and test:
- [ ] Homepage loads correctly
- [ ] All CSS/JS assets load
- [ ] Contact form works
- [ ] Admin login at `/login` works
- [ ] Project admin CRUD works

### Troubleshooting

**"Class not found" errors**
```bash
composer dump-autoload -o
```

**Permission denied (storage)**
```bash
chmod -R 775 storage bootstrap/cache
```

**Mail not sending**
- Verify MAIL_* variables in `.env`
- Check spam folder
- Test with: `php artisan tinker` → `Mail::raw('test', fn($msg) => $msg->to('you@example.com'));`

---

## 2. DigitalOcean Droplet (Ubuntu 22.04)

### Cost: $5-40/month depending on size

### Step 1: Create & Connect to Droplet

1. Go to [DigitalOcean](https://digitalocean.com) → Create → Droplet
2. Choose:
   - **Region**: Closest to your users
   - **Size**: $6/mo (2GB RAM, 50GB SSD) recommended
   - **OS**: Ubuntu 22.04 x64
3. Add SSH key or use password
4. Create Droplet
5. SSH in:
```bash
ssh root@your_droplet_ip
```

### Step 2: Initial Server Setup

```bash
# Update system
apt update && apt upgrade -y

# Install dependencies
apt install -y php8.2-cli php8.2-fpm php8.2-mysql php8.2-sqlite3 \
  php8.2-curl php8.2-mbstring php8.2-xml php8.2-zip composer \
  nginx mysql-server git nodejs npm certbot python3-certbot-nginx

# Create app user (don't run as root)
useradd -m -s /bin/bash app
usermod -aG www-data app
```

### Step 3: Clone & Setup Application

```bash
# Switch to app user
su - app

# Clone repo
cd /home/app
git clone https://github.com/andiprasetio354/andiprasetio354.git .
git checkout portfolio-dev

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Setup .env
cp .env.example .env
nano .env  # Edit database & mail settings

# Generate key
php artisan key:generate

# Setup database
php artisan migrate --force

# Storage link
php artisan storage:link

# Permissions
sudo chown -R app:www-data /home/app
chmod -R 755 storage bootstrap/cache
```

### Step 4: Configure Nginx

```bash
# Create Nginx config
sudo nano /etc/nginx/sites-available/portfolio
```

Paste (replace your-domain.com):
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /home/app/public;
    index index.php index.html index.htm;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "no-referrer-when-downgrade";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static file caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart
sudo systemctl restart nginx php8.2-fpm
```

### Step 5: Setup SSL (Let's Encrypt)

```bash
sudo certbot certonly --nginx -d your-domain.com -d www.your-domain.com
```

Update Nginx config to use SSL (add after line 4):
```nginx
listen 443 ssl http2;
ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

```bash
sudo systemctl restart nginx
```

### Step 6: Setup MySQL Database

```bash
sudo mysql -u root

# Inside MySQL prompt:
CREATE DATABASE portfolio;
CREATE USER 'portfolio_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON portfolio.* TO 'portfolio_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=portfolio
DB_USERNAME=portfolio_user
DB_PASSWORD=strong_password
```

### Step 7: Optimize & Cache

```bash
su - app
cd /home/app

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 8: Auto-renew SSL (Optional but Recommended)

```bash
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

---

## 3. DigitalOcean App Platform (Fully Managed)

### Cost: $5-20/month

Easiest option—no server management needed.

### Step 1: Prepare GitHub

Ensure code is pushed to GitHub:
```bash
git push origin portfolio-dev
```

### Step 2: Create `.do/app.yaml`

In your project root, create `.do/app.yaml`:
```yaml
name: portfolio
services:
- name: web
  github:
    repo: andiprasetio354/andiprasetio354
    branch: portfolio-dev
  build_command: npm ci && npm run build && composer install --no-dev
  run_command: "vendor/bin/heroku-php-apache2 public/"
  source_dir: /
  envs:
  - key: APP_ENV
    value: production
  - key: APP_DEBUG
    value: "false"
  - key: APP_KEY
    scope: RUN_AND_BUILD_TIME
    value: ${APP_KEY}
  http_port: 8080
databases:
- name: db
  engine: MYSQL
  version: "8"
envs:
- key: DB_CONNECTION
  value: mysql
- key: DB_HOST
  value: ${db.MYSQL_HOST}
- key: DB_PORT
  value: "3306"
- key: DB_DATABASE
  value: portfolio
- key: DB_USERNAME
  value: ${db.MYSQL_USER}
- key: DB_PASSWORD
  scope: RUN_AND_BUILD_TIME
  value: ${db.MYSQL_PASSWORD}
- key: MAIL_MAILER
  value: smtp
- key: MAIL_HOST
  value: smtp.gmail.com
- key: MAIL_PORT
  value: "587"
- key: MAIL_USERNAME
  value: your-email@gmail.com
- key: MAIL_PASSWORD
  scope: RUN_AND_BUILD_TIME
  value: your-app-password
- key: MAIL_ENCRYPTION
  value: tls
```

Push to GitHub:
```bash
git add .do/app.yaml
git commit -m "Add DigitalOcean App Platform config"
git push origin portfolio-dev
```

### Step 3: Create App on DigitalOcean

1. Go to [DigitalOcean App Platform](https://cloud.digitalocean.com/apps)
2. Click "Create App"
3. Select "GitHub" → Authorize → Select repo `andiprasetio354/andiprasetio354`
4. Select branch: `portfolio-dev`
5. Choose "Autodeploy" if desired
6. Click "Next"
7. Review environment variables
8. Click "Create Resources"
9. Set custom domain (if owned)
10. Deploy!

---

## 4. Laravel Forge (Professional Hosting)

### Cost: $12+/month (hosting separate)

Best for professionals. Forge handles provisioning & deployment.

### Step 1: Create Forge Server

1. Go to [Laravel Forge](https://forge.laravel.com)
2. Create account (free tier available)
3. Connect cloud provider (DigitalOcean, AWS, Linode, etc.)
4. Create server:
   - Size: 1GB RAM ($5 on DO)
   - Region: Closest to users
   - PHP Version: 8.2

### Step 2: Create Site

1. In Forge dashboard → Create Site
2. Name: `portfolio`
3. Root Domain: `your-domain.com`
4. Project Type: `General PHP / Laravel`
5. Database: Create MySQL

### Step 3: Connect GitHub

1. Site Settings → Deployment → Connect GitHub
2. Select repo: `andiprasetio354/andiprasetio354`
3. Branch: `portfolio-dev`
4. Deployment script (Forge auto-generates):
```bash
cd /home/forge/portfolio.com
git pull origin portfolio-dev
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan migrate --force
php artisan view:clear
```

### Step 4: Deploy

1. Click "Deploy Now"
2. Monitor logs in Forge dashboard
3. Wait for deployment to complete

### Step 5: Post-Deploy

Via SSH (Forge provides direct SSH button):
```bash
php artisan storage:link
```

---

## Email Configuration

### Gmail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password  # Not your Gmail password!
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```

To get App Password:
1. Enable 2FA on Google Account
2. Go to [Security Settings](https://myaccount.google.com/security)
3. Select "App Passwords"
4. Generate for "Mail" + "Windows Computer"
5. Copy 16-character password

### SendGrid
```env
MAIL_MAILER=sendgrid
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

### Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.your-domain.com
MAILGUN_SECRET=your-secret-key
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

---

## SSL/HTTPS

### Free SSL (Let's Encrypt)

**On Shared Hosting**: Usually auto-provisioned via cPanel

**On Droplet**: Already covered above with Certbot

**On Forge**: Click "Enable SSL" in site settings (auto-renews)

---

## Monitoring & Maintenance

### Check Error Logs
```bash
# Shared Hosting / Droplet
tail -f storage/logs/laravel.log

# Forge
Click "Monitor" in dashboard
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Backup
```bash
# Shared Hosting / Droplet
php artisan backup:run  # If using spatie/laravel-backup

# Manual
mysqldump -u username -p database > backup.sql
```

### Monitor Disk Space
```bash
df -h
du -sh *
```

---

## FAQ

**Q: Which platform should I choose?**
- **Just starting?** → Shared Hosting or DigitalOcean App Platform
- **Want full control?** → DigitalOcean Droplet
- **Want managed, professional?** → Laravel Forge

**Q: Can I migrate between platforms?**
- Yes! Database is portable. Just deploy code & migrate DB.

**Q: How do I update the application after deployment?**
```bash
git pull origin portfolio-dev
composer install --no-dev
npm run build
php artisan migrate --force
php artisan config:cache
```

**Q: How do I setup automatic deployments?**
- DigitalOcean App Platform & Forge: Built-in GitHub integration
- Droplet/Shared Hosting: Setup GitHub webhook or manual pull

---

For more help, check [Laravel Deployment Docs](https://laravel.com/docs/deployment)
