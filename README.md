# Portfolio Website - Laravel Application

A professional, responsive portfolio website built with Laravel 12, Tailwind CSS, and Blade templating. Features include project showcase, contact form, resume/CV page, and admin panel for content management.

## Features

✨ **Core Features**
- Responsive design with Tailwind CSS
- Home, About, Projects, Resume, and Contact pages
- Admin authentication (Laravel Breeze)
- Project management CRUD with image upload
- Contact form with message storage
- Professional resume/CV page with print & download options
- SEO optimized (meta tags, Open Graph, sitemap.xml, robots.txt)

## Tech Stack

- **Backend**: PHP 8.2+, Laravel 12, SQLite/MySQL
- **Frontend**: Tailwind CSS, Blade templating
- **Authentication**: Laravel Breeze
- **Database**: SQLite (default) or MySQL
- **Build Tools**: Vite, NPM

## Local Development Setup

### Prerequisites
- PHP 8.2+ with extensions: sqlite3, pdo_sqlite, curl, mbstring, xml
- Composer
- Node.js 20+ (for asset compilation)
- SQLite or MySQL

### Installation Steps

1. **Clone the repository**
```bash
git clone https://github.com/andiprasetio354/andiprasetio354.git
cd andiprasetio354
git checkout portfolio-dev  # Switch to portfolio-dev branch
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Setup database (SQLite default)**
```bash
touch database/database.sqlite
php artisan migrate
```

Or for MySQL, update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=
```

6. **Create storage symlink**
```bash
php artisan storage:link
```

7. **Run development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Development

### Compile Assets
```bash
npm run dev   # Development with watch mode
npm run build # Production build
```

### Create Test User (Admin)
```bash
php artisan tinker
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
]);
exit
```

Then login at `/login`

## Deployment

### 1. Shared Hosting (cPanel, etc.)

**Prerequisites**: SSH access, PHP 8.2+, Composer, Git

**Steps**:
```bash
# SSH into server
ssh user@your-domain.com

# Clone repository
cd public_html  # or your web root
git clone https://github.com/andiprasetio354/andiprasetio354.git .
git checkout portfolio-dev

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Setup environment
cp .env.example .env
# Edit .env with your database & mail credentials
php artisan key:generate
php artisan migrate --force

# Fix permissions
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage bootstrap/cache/*
php artisan storage:link

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Important**: Set your domain's public root to `/public`

### 2. DigitalOcean (via App Platform or Droplet)

#### Option A: DigitalOcean App Platform (Recommended)

1. **Prepare your code**
   - Push to GitHub on `portfolio-dev` branch
   - Create `.do/app.yaml`:
```yaml
name: portfolio
services:
- name: web
  github:
    repo: andiprasetio354/andiprasetio354
    branch: portfolio-dev
  build_command: npm ci && npm run build && composer install --no-dev
  run_command: php -S 0.0.0.0:8080 -t public
  envs:
  - key: APP_ENV
    value: production
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
- key: APP_DEBUG
  value: "false"
```

2. **Create App on DigitalOcean**
   - Go to App Platform → Create App
   - Connect GitHub repo → Select `portfolio-dev` branch
   - Use YAML from above or configure via UI
   - Set environment variables (APP_KEY, mail config)
   - Deploy

#### Option B: DigitalOcean Droplet (Ubuntu 22.04)

1. **Create Droplet** (2GB RAM minimum)

2. **SSH and setup server**
```bash
ssh root@your_droplet_ip

# Update system
apt update && apt upgrade -y

# Install dependencies
apt install -y php8.2-cli php8.2-fpm php8.2-mysql php8.2-sqlite3 php8.2-curl \
  php8.2-mbstring php8.2-xml php8.2-zip composer nginx mysql-server git nodejs npm

# Create app user
useradd -m -s /bin/bash app
```

3. **Deploy application**
```bash
su - app
cd /home/app

# Clone repo
git clone https://github.com/andiprasetio354/andiprasetio354.git .
git checkout portfolio-dev

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Setup .env
cp .env.example .env
# Edit: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, MAIL settings
php artisan key:generate

# Setup database
php artisan migrate --force

# Permissions
sudo chown -R app:app /home/app
chmod -R 755 storage bootstrap/cache
php artisan storage:link

# Cache
php artisan config:cache
php artisan route:cache
```

4. **Configure Nginx**
```bash
sudo nano /etc/nginx/sites-available/portfolio
```

Add:
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /home/app/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

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
}
```

```bash
sudo ln -s /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

5. **Setup SSL (Let's Encrypt)**
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot certonly --nginx -d your-domain.com -d www.your-domain.com
```

Update Nginx config to use SSL, then:
```bash
sudo systemctl restart nginx
```

### 3. Laravel Forge (Recommended for Professionals)

1. **Create Server** on Forge dashboard (DigitalOcean, AWS, Linode, etc.)

2. **Connect GitHub**
   - Go to Apps → Create App
   - Select repo: `andiprasetio354/andiprasetio354`
   - Branch: `portfolio-dev`

3. **Environment Variables**
   - APP_ENV: `production`
   - APP_DEBUG: `false`
   - DB_* (configure database)
   - MAIL_* (configure mail)

4. **Deploy**
   - Forge auto-provisions PHP, Nginx, MySQL, SSL
   - Click "Deploy Now"
   - Monitor deployment logs

5. **Post-Deploy**
```bash
# SSH into server via Forge dashboard
php artisan storage:link
php artisan migrate
```

## Environment Variables

Key `.env` variables:

```env
APP_NAME=Portfolio
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql        # or sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=

# Mail (Gmail example)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # Use App Password, not Gmail password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```

## File Structure

```
.
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── ProjectController.php      # CRUD for projects
│   │       ├── ContactController.php      # Contact form handling
│   │       └── SitemapController.php      # SEO sitemap
│   ├── Models/
│   │   ├── Project.php
│   │   └── ContactMessage.php
│   └── Services/
│       └── SeoService.php
├── database/
│   ├── migrations/
│   └── database.sqlite
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php             # Main layout with SEO
│   │   ├── home.blade.php
│   │   ├── about.blade.php
│   │   ├── resume.blade.php
│   │   ├── contact-form.blade.php
│   │   ├── projects.blade.php
│   │   └── admin/                        # Admin views
│   │       ├── projects/
│   │       └── contact/
│   └── css/app.css
├── routes/
│   └── web.php
├── public/
│   ├── index.php
│   ├── robots.txt
│   └── storage/                          # Symlink to storage/app/public
└── .env.example
```

## Database Schema

### Projects Table
```
id, title, slug, description, tech_stack, image, link, featured, created_at, updated_at
```

### Contact Messages Table
```
id, name, email, subject, message, status, created_at, updated_at
```

### Users Table (Breeze)
```
id, name, email, email_verified_at, password, remember_token, created_at, updated_at
```

## Admin Panel

**Login**: `/login`
- Email: `admin@example.com`
- Password: (set during setup)

**Admin Routes**:
- `/admin/projects` — Manage projects (CRUD)
- `/admin/contact` — View contact messages

## Performance Tips

- Run `php artisan optimize:clear` before deployment
- Use CDN for static assets (images, CSS, JS)
- Enable caching: `php artisan config:cache route:cache`
- Setup queue for email (optional): Configure `QUEUE_CONNECTION=database`

## Troubleshooting

**502 Bad Gateway**
- Check error logs: `tail -f /home/app/storage/logs/laravel.log`
- Verify PHP-FPM is running: `systemctl status php8.2-fpm`

**Database Connection Error**
- Verify `.env` DB credentials
- Run migrations: `php artisan migrate --force`

**Storage Permission Error**
- Fix permissions: `chmod -R 755 storage bootstrap/cache`

**Assets Not Loading**
- Rebuild: `npm run build`
- Clear cache: `php artisan view:clear`

## Support & Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Forge Documentation](https://forge.laravel.com/docs)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)

## License

This project is open source and available under the [MIT License](LICENSE).

---

**Author**: Your Name  
**Portfolio**: [your-domain.com](https://your-domain.com)  
**GitHub**: [github.com/andiprasetio354/andiprasetio354](https://github.com/andiprasetio354/andiprasetio354)


In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
