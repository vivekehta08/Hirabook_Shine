# HiraShine: Diamond Hisab Diary - Backend API

Complete PHP MVC backend for the HiraShine Android application. This backend provides RESTful APIs for managing diamond business operations without any authentication system - users are identified solely by Android ID.

## Features

✅ **No Authentication Required** - Android ID based user identification  
✅ **Auto User Creation** - Users created automatically on first API call  
✅ **Dashboard API** - Current month totals and calculations  
✅ **User Profile Management** - Name, Phone, Email  
✅ **Diamond Rate Management** - User-specific rate CRUD  
✅ **Daily Diamond Entries** - Full CRUD operations  
✅ **Withdrawal Management** - Withdrawal tracking and history  
✅ **Reports** - Date-wise and monthly reports  
✅ **Backup & Restore** - Full data backup and restore functionality  
✅ **Multi-language Support Ready** - Backend supports all data types  

## Project Structure

```
HiraBook/
├── api/
│   └── index.php              # API Router
├── config/
│   ├── config.php             # Application configuration
│   └── database.php           # Database connection
├── controllers/
│   ├── UserController.php     # User management
│   ├── DashboardController.php # Dashboard API
│   ├── DiamondRateController.php # Diamond rate CRUD
│   ├── DailyEntryController.php  # Daily entry CRUD
│   ├── WithdrawalController.php  # Withdrawal CRUD
│   ├── ReportController.php      # Reports
│   └── BackupController.php      # Backup & Restore
├── models/
│   ├── UserModel.php
│   ├── DiamondRateModel.php
│   ├── DailyEntryModel.php
│   └── WithdrawalModel.php
├── database_schema.sql         # Database schema
├── .htaccess                   # Apache routing
├── API_DOCUMENTATION.md        # Complete API docs
└── README.md                   # This file
```

## Installation

### 1. Database Setup

1. Create MySQL database:
```sql
CREATE DATABASE hirashine_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import schema:
```bash
mysql -u root -p hirashine_db < database_schema.sql
```

### 2. Configuration

Edit `config/database.php` with your database credentials:

```php
private $host = "localhost";
private $db_name = "hirashine_db";
private $username = "root";
private $password = "your_password";
```

### 3. Server Setup

**Apache:**
- Ensure mod_rewrite is enabled
- Place project in web root (e.g., `/var/www/html/HiraBook/`)
- Update `.htaccess` `RewriteBase` if needed

**Nginx:**
Add to your server block:
```nginx
location /HiraBook {
    try_files $uri $uri/ /HiraBook/api/index.php?$query_string;
}
```

### 4. Permissions

```bash
chmod 755 -R /path/to/HiraBook
chmod 644 .htaccess
```

## API Base URL

```
http://your-domain.com/HiraBook/api
```

## Quick Start

### 1. Initialize User (On App Open)

```bash
POST /user/initialize
{
  "android_id": "unique-android-id-12345",
  "fcm_token": "optional-fcm-token"
}
```

### 2. Get Dashboard

```bash
GET /dashboard/index?android_id=unique-android-id-12345
```

### 3. Add Daily Entry

```bash
POST /daily-entry/add
{
  "android_id": "unique-android-id-12345",
  "entry_date": "2025-01-13",
  "weight": 10.500,
  "rate": 5000.00,
  "total_amount": 52500.00
}
```

## API Documentation

See [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for complete API reference with all endpoints, request/response examples, and error handling.

## Database Schema

### Tables

1. **users** - User information (Android ID based)
2. **diamond_rates** - User-specific diamond rates
3. **daily_entries** - Daily diamond entries
4. **withdrawals** - Withdrawal records

All tables use `android_id` as the user identifier with CASCADE delete.

## Security Features

- ✅ Prepared statements (SQL injection prevention)
- ✅ Input validation
- ✅ Error logging
- ✅ CORS headers configured
- ✅ UTF-8 encoding support

## Error Handling

All APIs return consistent JSON responses:

**Success:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {...}
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error description",
  "data": null
}
```

## Testing

### Using cURL

```bash
# Initialize User
curl -X POST http://localhost/HiraBook/api/user/initialize \
  -H "Content-Type: application/json" \
  -d '{"android_id":"test123","fcm_token":"test-token"}'

# Get Dashboard
curl "http://localhost/HiraBook/api/dashboard/index?android_id=test123"

# Add Entry
curl -X POST http://localhost/HiraBook/api/daily-entry/add \
  -H "Content-Type: application/json" \
  -d '{
    "android_id":"test123",
    "entry_date":"2025-01-13",
    "weight":10.5,
    "rate":5000,
    "total_amount":52500
  }'
```

## Android Integration

### Example: Initialize User

```java
// Get Android ID
String androidId = Settings.Secure.getString(
    getContentResolver(), 
    Settings.Secure.ANDROID_ID
);

// Get FCM Token
FirebaseMessaging.getInstance().getToken()
    .addOnCompleteListener(task -> {
        String fcmToken = task.getResult();
        
        // Call API
        JSONObject request = new JSONObject();
        request.put("android_id", androidId);
        request.put("fcm_token", fcmToken);
        
        // POST to /user/initialize
    });
```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB 10.2+)
- Apache with mod_rewrite (or Nginx)
- PDO MySQL extension

## Support

For issues or questions:
- Email: jethvainfo@gmail.com
- Check API_DOCUMENTATION.md for detailed API reference

## License

Proprietary - HiraShine: Diamond Hisab Diary

---

**Built with ❤️ for Diamond Business Management**

