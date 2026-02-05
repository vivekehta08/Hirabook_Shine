# How to Import Postman Collection

## Quick Import Steps

1. **Open Postman**
   - Launch Postman application

2. **Click Import**
   - Click the "Import" button (top left)
   - Or use shortcut: `Ctrl+O` (Windows) / `Cmd+O` (Mac)

3. **Select File**
   - Choose "File" tab
   - Click "Choose Files"
   - Select `HiraShine_API.postman_collection.json`
   - Click "Import"

4. **Done!**
   - Collection will appear in your Postman workspace
   - All 23+ API endpoints are ready to use
   - Android ID `a4f99a889de0ce32` is pre-configured in all requests

---

## Collection Structure

The collection is organized into folders:

1. **User Management** (3 requests)
   - Initialize User
   - Get User Profile
   - Update User Profile

2. **Dashboard** (1 request)
   - Get Dashboard

3. **Diamond Rate** (4 requests)
   - Add, Get, Update, Delete

4. **Daily Entry** (6 requests)
   - Add, Get All, Get by ID, Update, Delete

5. **Withdrawal** (5 requests)
   - Add, Get All, Get History, Update, Delete

6. **Reports** (3 requests)
   - Date-wise Report
   - Monthly Report
   - All Monthly Reports

7. **Backup & Restore** (2 requests)
   - Create Backup
   - Restore Backup

---

## Testing Order

### 1. First Time Setup
```
1. User Management → 1. Initialize User
2. User Management → 3. Update User Profile
3. Diamond Rate → Add Diamond Rate
```

### 2. Add Data
```
4. Daily Entry → Add Daily Entry
5. Daily Entry → Add Daily Entry 2
6. Withdrawal → Add Withdrawal
```

### 3. View Data
```
7. Dashboard → Get Dashboard
8. Daily Entry → Get All Entries
9. Withdrawal → Get All Withdrawals
```

### 4. Reports & Backup
```
10. Reports → Get Monthly Report
11. Backup & Restore → Create Backup
```

---

## Update Base URL (if needed)

If your server URL is different:

1. Right-click on collection name
2. Click "Edit"
3. Go to "Variables" tab
4. Add variable:
   - Variable: `base_url`
   - Initial Value: `http://localhost/HiraBook/api`
5. Update URLs in requests (or use variable)

---

## Android ID

All requests use Android ID: `a4f99a889de0ce32`

To change for another device:
- Find and replace `a4f99a889de0ce32` in all requests
- Or create a collection variable for `android_id`

---

## Tips

- **Save Responses**: Click "Save Response" to keep test data
- **Tests**: Add test scripts to verify responses
- **Environment**: Create environment for different servers (dev/prod)
- **Collection Runner**: Run all requests in sequence

---

**File Location:** `HiraShine_API.postman_collection.json`

