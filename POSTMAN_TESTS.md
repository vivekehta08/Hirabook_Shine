# Postman Test Collection for Android ID: a4f99a889de0ce32

Complete Postman test collection to verify your HiraShine backend with the Android app.

## Base URL
```
http://localhost/HiraBook/api
```
**Or replace with your server URL**

---

## Test Android ID
```
a4f99a889de0ce32
```

---

## Postman Collection

### 1. Initialize User (On App Open)

**Method:** `POST`  
**URL:** `http://localhost/HiraBook/api/user/initialize`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
  "android_id": "a4f99a889de0ce32",
  "fcm_token": "your_fcm_token_here"
}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "User initialized successfully",
  "data": {
    "id": 1,
    "android_id": "a4f99a889de0ce32",
    "fcm_token": "your_fcm_token_here",
    "name": null,
    "phone": null,
    "email": null,
    "created_at": "2025-01-13 12:00:00",
    "updated_at": "2025-01-13 12:00:00"
  }
}
```

---

### 2. Get Dashboard

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/dashboard/index?android_id=a4f99a889de0ce32`

**Expected Response:**
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

---

### 3. Update User Profile

**Method:** `PUT`  
**URL:** `http://localhost/HiraBook/api/user/profile`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
  "android_id": "a4f99a889de0ce32",
  "name": "John Doe",
  "phone": "9876543210",
  "email": "john@example.com"
}
```

---

### 4. Add Diamond Rate

**Method:** `POST`  
**URL:** `http://localhost/HiraBook/api/diamond-rate/add`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
  "android_id": "a4f99a889de0ce32",
  "rate": 5000.00
}
```

---

### 5. Get Diamond Rate

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/diamond-rate/get?android_id=a4f99a889de0ce32`

---

### 6. Add Daily Diamond Entry

**Method:** `POST`  
**URL:** `http://localhost/HiraBook/api/daily-entry/add`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
  "android_id": "a4f99a889de0ce32",
  "entry_date": "2025-01-13",
  "weight": 10.500,
  "rate": 5000.00,
  "total_amount": 52500.00
}
```

**Add Multiple Entries:**
```json
{
  "android_id": "a4f99a889de0ce32",
  "entry_date": "2025-01-14",
  "weight": 8.250,
  "rate": 5000.00,
  "total_amount": 41250.00
}
```

---

### 7. Get All Daily Entries

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/daily-entry/list?android_id=a4f99a889de0ce32`

**With Date Range:**
```
http://localhost/HiraBook/api/daily-entry/list?android_id=a4f99a889de0ce32&start_date=2025-01-01&end_date=2025-01-31
```

---

### 8. Get Single Entry

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/daily-entry/get?id=1&android_id=a4f99a889de0ce32`

*(Replace `1` with actual entry ID)*

---

### 9. Update Daily Entry

**Method:** `PUT`  
**URL:** `http://localhost/HiraBook/api/daily-entry/update`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
  "id": 1,
  "android_id": "a4f99a889de0ce32",
  "entry_date": "2025-01-13",
  "weight": 11.000,
  "rate": 5000.00,
  "total_amount": 55000.00
}
```

---

### 10. Delete Daily Entry

**Method:** `DELETE`  
**URL:** `http://localhost/HiraBook/api/daily-entry/delete`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
  "id": 1,
  "android_id": "a4f99a889de0ce32"
}
```

---

### 11. Add Withdrawal

**Method:** `POST`  
**URL:** `http://localhost/HiraBook/api/withdrawal/add`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
  "android_id": "a4f99a889de0ce32",
  "withdrawal_date": "2025-01-13",
  "amount": 10000.00
}
```

---

### 12. Get All Withdrawals

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/withdrawal/list?android_id=a4f99a889de0ce32`

---

### 13. Get Withdrawal History (Date-wise)

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/withdrawal/history?android_id=a4f99a889de0ce32`

---

### 14. Get Dashboard (After Data Entry)

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/dashboard/index?android_id=a4f99a889de0ce32`

**Expected Response (with data):**
```json
{
  "success": true,
  "message": "Dashboard data retrieved successfully",
  "data": {
    "current_month_total": 93750.00,
    "current_month_withdrawal": 10000.00,
    "current_month_remain_total": 83750.00
  }
}
```

---

### 15. Get Date-wise Report

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/report/date-wise?android_id=a4f99a889de0ce32&start_date=2025-01-01&end_date=2025-01-31`

---

### 16. Get Monthly Report

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/report/monthly?android_id=a4f99a889de0ce32&year=2025&month=1`

---

### 17. Get All Monthly Reports

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/report/all-monthly?android_id=a4f99a889de0ce32`

---

### 18. Create Backup

**Method:** `GET`  
**URL:** `http://localhost/HiraBook/api/backup/create?android_id=a4f99a889de0ce32`

**Expected Response:**
```json
{
  "success": true,
  "message": "Backup created successfully",
  "data": {
    "android_id": "a4f99a889de0ce32",
    "backup_date": "2025-01-13 12:00:00",
    "user": {...},
    "diamond_rate": {...},
    "daily_entries": [...],
    "withdrawals": [...],
    "summary": {...}
  }
}
```

---

## Testing Checklist

### Initial Setup
- [ ] 1. Initialize user (POST /user/initialize)
- [ ] 2. Get dashboard (should show zeros)
- [ ] 3. Update user profile

### Diamond Rate
- [ ] 4. Add diamond rate
- [ ] 5. Get diamond rate
- [ ] 6. Update diamond rate (if needed)

### Daily Entries
- [ ] 7. Add first daily entry
- [ ] 8. Add second daily entry
- [ ] 9. Get all entries
- [ ] 10. Get single entry
- [ ] 11. Update entry (optional)
- [ ] 12. Delete entry (optional)

### Withdrawals
- [ ] 13. Add withdrawal
- [ ] 14. Get all withdrawals
- [ ] 15. Get withdrawal history

### Reports & Dashboard
- [ ] 16. Get updated dashboard (should show totals)
- [ ] 17. Get monthly report
- [ ] 18. Get date-wise report
- [ ] 19. Get all monthly reports

### Backup & Restore
- [ ] 20. Create backup
- [ ] 21. Test restore (optional)

---

## Quick Test Sequence

Run these in order:

1. **Initialize User**
   ```
   POST /user/initialize
   ```

2. **Add Diamond Rate**
   ```
   POST /diamond-rate/add
   ```

3. **Add Daily Entry**
   ```
   POST /daily-entry/add (multiple entries)
   ```

4. **Add Withdrawal**
   ```
   POST /withdrawal/add
   ```

5. **Get Dashboard** (verify totals)
   ```
   GET /dashboard/index
   ```

6. **Get All Data**
   ```
   GET /daily-entry/list
   GET /withdrawal/list
   ```

---

## Verification Steps

After running tests, verify in database:

```sql
USE hirashine_db;

-- Check user
SELECT * FROM users WHERE android_id = 'a4f99a889de0ce32';

-- Check diamond rate
SELECT * FROM diamond_rates WHERE android_id = 'a4f99a889de0ce32';

-- Check daily entries
SELECT * FROM daily_entries WHERE android_id = 'a4f99a889de0ce32';

-- Check withdrawals
SELECT * FROM withdrawals WHERE android_id = 'a4f99a889de0ce32';

-- Verify totals
SELECT 
    SUM(total_amount) as total_entries,
    (SELECT SUM(amount) FROM withdrawals WHERE android_id = 'a4f99a889de0ce32') as total_withdrawals,
    SUM(total_amount) - (SELECT SUM(amount) FROM withdrawals WHERE android_id = 'a4f99a889de0ce32') as remaining
FROM daily_entries 
WHERE android_id = 'a4f99a889de0ce32';
```

---

## Troubleshooting

### If API returns 404:
- Check `.htaccess` file exists
- Verify `mod_rewrite` is enabled
- Check `RewriteBase` in `.htaccess`

### If Database connection fails:
- Verify database credentials in `config/database.php`
- Check MySQL service is running
- Verify database `hirashine_db` exists

### If Data not saving:
- Check database permissions
- Verify user exists (run initialize first)
- Check PHP error logs

---

**Android ID:** `a4f99a889de0ce32`  
**Base URL:** Update according to your server

