# HiraShine: Diamond Hisab Diary - API Documentation

## Base URL
```
http://your-domain.com/HiraBook/api
```

## Authentication
**No authentication required.** All APIs use `android_id` for user identification.

---

## API Endpoints

### 1. User Management

#### Initialize User (On App Open)
**POST** `/user/initialize`

Creates user if not exists, updates FCM token if exists.

**Request Body:**
```json
{
  "android_id": "unique-android-id-12345",
  "fcm_token": "fcm-token-optional"
}
```

**Response:**
```json
{
  "success": true,
  "message": "User initialized successfully",
  "data": {
    "id": 1,
    "android_id": "unique-android-id-12345",
    "fcm_token": "fcm-token-optional",
    "name": null,
    "phone": null,
    "email": null,
    "created_at": "2025-01-13 12:00:00",
    "updated_at": "2025-01-13 12:00:00"
  }
}
```

#### Get User Profile
**GET** `/user/profile?android_id=unique-android-id-12345`

**Response:**
```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "android_id": "unique-android-id-12345",
    "name": "John Doe",
    "phone": "1234567890",
    "email": "john@example.com"
  }
}
```

#### Update User Profile
**PUT** `/user/profile`

**Request Body:**
```json
{
  "android_id": "unique-android-id-12345",
  "name": "John Doe",
  "phone": "1234567890",
  "email": "john@example.com"
}
```

---

### 2. Dashboard

#### Get Dashboard Data
**GET** `/dashboard/index?android_id=unique-android-id-12345`

Returns current month totals.

**Response:**
```json
{
  "success": true,
  "message": "Dashboard data retrieved successfully",
  "data": {
    "current_month_total": 50000.00,
    "current_month_withdrawal": 10000.00,
    "current_month_remain_total": 40000.00
  }
}
```

---

### 3. Diamond Rate

#### Add/Update Diamond Rate
**POST** `/diamond-rate/add`

**Request Body:**
```json
{
  "android_id": "unique-android-id-12345",
  "rate": 5000.00
}
```

#### Get Diamond Rate
**GET** `/diamond-rate/get?android_id=unique-android-id-12345`

#### Update Diamond Rate
**PUT** `/diamond-rate/update`

**Request Body:**
```json
{
  "android_id": "unique-android-id-12345",
  "rate": 5500.00
}
```

#### Delete Diamond Rate
**DELETE** `/diamond-rate/delete`

**Request Body:**
```json
{
  "android_id": "unique-android-id-12345"
}
```

---

### 4. Daily Diamond Entry

#### Add Daily Entry
**POST** `/daily-entry/add`

**Request Body:**
```json
{
  "android_id": "unique-android-id-12345",
  "entry_date": "2025-01-13",
  "weight": 10.500,
  "rate": 5000.00,
  "total_amount": 52500.00
}
```

#### Get All Entries
**GET** `/daily-entry/list?android_id=unique-android-id-12345&start_date=2025-01-01&end_date=2025-01-31`

**Optional Query Parameters:**
- `start_date`: YYYY-MM-DD
- `end_date`: YYYY-MM-DD

#### Get Entry by ID
**GET** `/daily-entry/get?id=1&android_id=unique-android-id-12345`

#### Update Entry
**PUT** `/daily-entry/update`

**Request Body:**
```json
{
  "id": 1,
  "android_id": "unique-android-id-12345",
  "entry_date": "2025-01-13",
  "weight": 11.000,
  "rate": 5000.00,
  "total_amount": 55000.00
}
```

#### Delete Entry
**DELETE** `/daily-entry/delete`

**Request Body:**
```json
{
  "id": 1,
  "android_id": "unique-android-id-12345"
}
```

---

### 5. Withdrawal

#### Add Withdrawal
**POST** `/withdrawal/add`

**Request Body:**
```json
{
  "android_id": "unique-android-id-12345",
  "withdrawal_date": "2025-01-13",
  "amount": 10000.00
}
```

#### Get All Withdrawals
**GET** `/withdrawal/list?android_id=unique-android-id-12345&start_date=2025-01-01&end_date=2025-01-31`

#### Get Withdrawal by ID
**GET** `/withdrawal/get?id=1&android_id=unique-android-id-12345`

#### Get Withdrawal History (Date-wise)
**GET** `/withdrawal/history?android_id=unique-android-id-12345`

**Response:**
```json
{
  "success": true,
  "message": "Withdrawal history retrieved successfully",
  "data": [
    {
      "date": "2025-01-13",
      "total_amount": 15000.00,
      "count": 2,
      "withdrawals": [...]
    }
  ]
}
```

#### Update Withdrawal
**PUT** `/withdrawal/update`

**Request Body:**
```json
{
  "id": 1,
  "android_id": "unique-android-id-12345",
  "withdrawal_date": "2025-01-13",
  "amount": 12000.00
}
```

#### Delete Withdrawal
**DELETE** `/withdrawal/delete`

**Request Body:**
```json
{
  "id": 1,
  "android_id": "unique-android-id-12345"
}
```

---

### 6. Reports

#### Date-wise Report
**GET** `/report/date-wise?android_id=unique-android-id-12345&start_date=2025-01-01&end_date=2025-01-31`

**Response:**
```json
{
  "success": true,
  "message": "Date-wise report retrieved successfully",
  "data": {
    "start_date": "2025-01-01",
    "end_date": "2025-01-31",
    "total_diamonds": 25,
    "total_weight": 250.500,
    "total_amount": 1250000.00,
    "total_withdrawals": 50000.00,
    "remaining_balance": 1200000.00,
    "entries": [...],
    "withdrawals": [...]
  }
}
```

#### Monthly Report
**GET** `/report/monthly?android_id=unique-android-id-12345&year=2025&month=1`

**Query Parameters:**
- `year`: YYYY (default: current year)
- `month`: 1-12 (default: current month)

#### All Monthly Reports Summary
**GET** `/report/all-monthly?android_id=unique-android-id-12345`

Returns summary of all months with data.

---

### 7. Backup & Restore

#### Create Backup
**GET** `/backup/create?android_id=unique-android-id-12345`

**Response:**
```json
{
  "success": true,
  "message": "Backup created successfully",
  "data": {
    "android_id": "unique-android-id-12345",
    "backup_date": "2025-01-13 12:00:00",
    "user": {
      "name": "John Doe",
      "phone": "1234567890",
      "email": "john@example.com"
    },
    "diamond_rate": {
      "rate": 5000.00
    },
    "daily_entries": [...],
    "withdrawals": [...],
    "summary": {
      "total_entries": 25,
      "total_withdrawals": 5,
      "total_entry_amount": 1250000.00,
      "total_withdrawal_amount": 50000.00
    }
  }
}
```

#### Restore Backup
**POST** `/backup/restore`

**Request Body:**
```json
{
  "android_id": "unique-android-id-12345",
  "backup_data": {
    "android_id": "unique-android-id-12345",
    "backup_date": "2025-01-13 12:00:00",
    "user": {
      "name": "John Doe",
      "phone": "1234567890",
      "email": "john@example.com"
    },
    "diamond_rate": {
      "rate": 5000.00
    },
    "daily_entries": [...],
    "withdrawals": [...]
  }
}
```

---

## Error Responses

All errors follow this format:

```json
{
  "success": false,
  "message": "Error message here",
  "data": null
}
```

**Common HTTP Status Codes:**
- `200`: Success
- `400`: Bad Request (validation errors)
- `404`: Not Found
- `500`: Internal Server Error

---

## Notes

1. **Android ID is required** for all endpoints (except in request body where specified)
2. **Date format**: Always use `YYYY-MM-DD`
3. **All amounts** are in decimal format (e.g., 5000.00)
4. **No authentication** required - Android ID is the only identifier
5. **User is auto-created** on first API call with new Android ID

---

## Sample Android Integration

### Initialize User (On App Open)
```java
// Android Code Example
String androidId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
String fcmToken = FirebaseMessaging.getInstance().getToken().getResult();

JSONObject request = new JSONObject();
request.put("android_id", androidId);
request.put("fcm_token", fcmToken);

// POST to /user/initialize
```

### Get Dashboard
```java
String url = BASE_URL + "/dashboard/index?android_id=" + androidId;
// GET request
```

---

## Database Schema

See `database_schema.sql` for complete database structure.

**Tables:**
- `users` - User information (Android ID based)
- `diamond_rates` - User-specific diamond rates
- `daily_entries` - Daily diamond entries
- `withdrawals` - Withdrawal records

All tables are linked via `android_id` with CASCADE delete.

