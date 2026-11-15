# News Portal - Laravel 12 (Admin + API)

This Laravel 12 application serves **both**:

- A **full-feature admin panel** to manage articles, categories, tags, ads, media, polls, users, menus, etc.
- A **headless API backend** providing endpoints for home, category, sub-category, article details, archives, webstories, polls, location data, breaking news, advertisements, photo galleries, videos, and more.

---

## Features

### Admin Panel
- Manage articles, categories, tags, webstories, ads, menus
- User, role & writer management
- Poll management
- Tag management
- Photo galleries and videos
- Breaking news & scrolling ticker
- Media uploads and management
- Location data (division/district/upazila)
- Manage data positions
- Content management (add/edit/delete, publish/unpublish, pending, uneditable, editing history)

### API Endpoints
- Home page, categories, sub-categories, articles, archives
- Webstories, polls, and location-based data (division/district/upazila)
- Dynamic menus, ads, breaking news, scroll news
- Photo galleries and videos

### Performance
- Redis caching for tags, articles, categories, and frequently accessed data
- Forever caching or optional TTL(Time To Live)
- Automatic cache invalidation on create/update/delete
- Pagination and search caching per page/query

---

## Requirements

- PHP >= 8.2
- Laravel 12
- MySQL / MariaDB
- Redis (Predis) - 
  - Linux (Ubuntu/Debian) 
    - `sudo apt update`
    - `sudo apt install redis-server`
    - `sudo systemctl enable redis`
    - `sudo systemctl start redis`
- Composer

---

## Installation

```bash
# Clone the repo:
git clone <repo-url>
cd <project-folder>

# install dependencies:
composer install

# copy .env.example to .env:
cp .env.example .env
```

```
# Edit .env file and set your database credentials and set redis information
CACHE_DRIVER=redis
CACHE_PREFIX=news_portal

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

```bash
php artisan key:generate
php artisan migrate --seed
php artisan config:clear
php artisan cache:clear
