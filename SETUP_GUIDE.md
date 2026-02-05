# HiraShine Backend - Setup Guide

Complete step-by-step guide to set up the HiraShine backend API.

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Apache with mod_rewrite enabled (or Nginx)
- Web server access (localhost or remote server)

---

## Step 1: Database Setup

### 1.1 Create Database

Login to MySQL:
```bash
mysql -u root -p
```

Create database:
```sql
CREATE DATABASE hirashine_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 1.2 Import Schema

```bash
mysql -u root -p hirashine_db < database_schema.sql
```

Or manually:
```sql
USE hirashine_db;
SOURCE /path/to/HiraBook/database_schema.sql;
```

### 1.3 Verify Tables

```sql
USE hirashine_db;
SHOW TABLES;
```

You should see:
- users
- diamond_rates
- daily_entries
- withdrawals

---

## Step 2: Configure Database Connection

Edit `config/database.php`:

```php
private $host = "localhost";        // Your MySQL host
private $db_name = "hirashine_db";  // Database name
private $username = "root";         // MySQL username
private $password = "your_password"; // MySQL password
```

---

## Step 3: Server Configuration

### Option A: Apache Setup

1. **Copy files to web root:**
```bash
sudo cp -r HiraBook /var/www/html/
# OR
sudo cp -r HiraBook /var/www/
```

2. **Set permissions:**
```bash
sudo chown -R www-data:www-data /var/www/html/HiraBook
sudo chmod -R 755 /var/www/html/HiraBook
sudo chmod 644 /var/www/html/HiraBook/.htaccess
```

3. **Enable mod_rewrite:**
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

4. **Update .htaccess RewriteBase** (if needed):
```apache
RewriteBase /HiraBook/
# OR if in subdirectory:
RewriteBase /your-subdirectory/HiraBook/
```

5. **Test URL:**
```
http://localhost/HiraBook/api/dashboard/index?android_id=test123
```

### Option B: Nginx Setup

1. **Add to server block** (`/etc/nginx/sites-available/default`):

```nginx
location /HiraBook {
    root /var/www/html;
    index index.php;
    
    try_files $uri $uri/ /HiraBook/api/index.php?$query_string;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

2. **Reload Nginx:**
```bash
sudo nginx -t
sudo systemctl reload nginx
```

### Option C: XAMPP/WAMP (Windows/Local)

1. Copy `HiraBook` folder to:
   - XAMPP: `C:\xampp\htdocs\HiraBook`
   - WAMP: `C:\wamp64\www\HiraBook`

2. Access via:
   ```
   http://localhost/HiraBook/api/dashboard/index?android_id=test123
   ```

---

## Step 4: PHP Configuration

### 4.1 Check PHP Version
```bash
php -v
```
Should be PHP 7.4 or higher.

### 4.2 Enable Required Extensions

Check if PDO MySQL is enabled:
```bash
php -m | grep pdo_mysql
```

If not enabled, edit `php.ini`:
```ini
extension=pdo_mysql
```

### 4.3 Error Reporting (Development)

Edit `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Set to 0 in production
```

### 4.4 Production Settings

For production, edit `config/config.php`:
```php
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
```

---

## Step 5: Test Installation

### 5.1 Test Database Connection

Create test file `test_db.php`:
```php
<?php
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
echo "Database connected successfully!";
?>
```

Run:
```bash
php test_db.php
```

### 5.2 Test API Endpoint

Using cURL:
```bash
curl "http://localhost/HiraBook/api/dashboard/index?android_id=test123"
```

Expected response:
```json
{
  "success": true,
  "message": "Dashboard data retrieved successfully",
  "data": {
    "current_month_total": 0,
    "current_month_withdrawal": 0,
    "current_month_remain_total": 0
  }
}
```

### 5.3 Test User Initialization

```bash
curl -X POST http://localhost/HiraBook/api/user/initialize \
  -H "Content-Type: application/json" \
  -d '{"android_id":"test123","fcm_token":"test-token"}'
```

---

## Step 6: Android App Integration

### 6.1 Update Base URL

In your Android app, set the base URL:
```java
public static final String BASE_URL = "http://your-domain.com/HiraBook/api";
// OR for local testing:
public static final String BASE_URL = "http://10.0.2.2/HiraBook/api"; // Android Emulator
```

### 6.2 Test from Android

1. Get Android ID:
```java
String androidId = Settings.Secure.getString(
    getContentResolver(), 
    Settings.Secure.ANDROID_ID
);
```

2. Call initialize API on app open
3. Test dashboard API
4. Test adding entries

---

## Step 7: Security Checklist

### 7.1 Production Security

- [ ] Set `display_errors = 0` in `config/config.php`
- [ ] Use strong database password
- [ ] Restrict database user permissions
- [ ] Enable HTTPS/SSL
- [ ] Update CORS headers if needed
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Remove test files

### 7.2 Database Security

```sql
-- Create dedicated database user
CREATE USER 'hirashine_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON hirashine_db.* TO 'hirashine_user'@'localhost';
FLUSH PRIVILEGES;
```

Update `config/database.php` with new credentials.

---

## Step 8: Troubleshooting

### Issue: 404 Not Found

**Solution:**
- Check `.htaccess` file exists
- Verify `mod_rewrite` is enabled
- Check `RewriteBase` path in `.htaccess`
- Verify file permissions

### Issue: Database Connection Failed

**Solution:**
- Check database credentials in `config/database.php`
- Verify MySQL service is running
- Check database exists
- Verify user has proper permissions

### Issue: CORS Errors

**Solution:**
- CORS headers are set in `config/config.php`
- For Android app, CORS should work
- If issues persist, check server configuration

### Issue: API Returns 500 Error

**Solution:**
- Check PHP error logs
- Enable error display in `config/config.php` (development only)
- Verify all required files exist
- Check file permissions

### Issue: Prepared Statement Errors

**Solution:**
- Verify PDO MySQL extension is enabled
- Check database connection
- Verify table structure matches schema

---

## Step 9: Backup & Maintenance

### 9.1 Database Backup

```bash
mysqldump -u root -p hirashine_db > backup_$(date +%Y%m%d).sql
```

### 9.2 Restore Database

```bash
mysql -u root -p hirashine_db < backup_20250113.sql
```

### 9.3 Log Rotation

Set up log rotation for PHP error logs:
```bash
sudo logrotate -f /etc/logrotate.d/php
```

---

## Step 10: Performance Optimization

### 10.1 Enable OPcache (Production)

Edit `php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

### 10.2 Database Indexing

Indexes are already created in schema. Verify:
```sql
SHOW INDEX FROM daily_entries;
SHOW INDEX FROM withdrawals;
```

---

## Support

If you encounter issues:
1. Check error logs
2. Verify all steps completed
3. Test with sample requests (see `SAMPLE_REQUESTS.md`)
4. Contact: jethvainfo@gmail.com

---

## Quick Reference

**Database:** `hirashine_db`  
**Base URL:** `http://your-domain.com/HiraBook/api`  
**Test Android ID:** Use any unique string for testing  
**API Docs:** See `API_DOCUMENTATION.md`

---

**Setup Complete! 🎉**

Your HiraShine backend is ready to use.

