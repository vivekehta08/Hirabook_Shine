# Sample API Requests & Responses

This document contains sample requests and responses for testing the HiraShine API.

## Base URL
```
http://localhost/HiraBook/api
```

---

## 1. User Initialization (On App Open)

### Request
```http
POST /user/initialize
Content-Type: application/json

{
  "android_id": "550e8400-e29b-41d4-a716-446655440000",
  "fcm_token": "dK8xYz9vLmN0OmFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6MTIzNDU2Nzg5MA"
}
```

### Response
```json
{
  "success": true,
  "message": "User initialized successfully",
  "data": {
    "id": 1,
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "fcm_token": "dK8xYz9vLmN0OmFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6MTIzNDU2Nzg5MA",
    "name": null,
    "phone": null,
    "email": null,
    "created_at": "2025-01-13 12:00:00",
    "updated_at": "2025-01-13 12:00:00"
  }
}
```

---

## 2. Get Dashboard

### Request
```http
GET /dashboard/index?android_id=550e8400-e29b-41d4-a716-446655440000
```

### Response
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

## 3. Update User Profile

### Request
```http
PUT /user/profile
Content-Type: application/json

{
  "android_id": "550e8400-e29b-41d4-a716-446655440000",
  "name": "Rajesh Kumar",
  "phone": "9876543210",
  "email": "rajesh@example.com"
}
```

### Response
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 1,
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "name": "Rajesh Kumar",
    "phone": "9876543210",
    "email": "rajesh@example.com",
    "created_at": "2025-01-13 12:00:00",
    "updated_at": "2025-01-13 12:05:00"
  }
}
```

---

## 4. Add Diamond Rate

### Request
```http
POST /diamond-rate/add
Content-Type: application/json

{
  "android_id": "550e8400-e29b-41d4-a716-446655440000",
  "rate": 5000.00
}
```

### Response
```json
{
  "success": true,
  "message": "Diamond rate saved successfully",
  "data": {
    "id": 1,
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "rate": "5000.00",
    "created_at": "2025-01-13 12:10:00",
    "updated_at": "2025-01-13 12:10:00"
  }
}
```

---

## 5. Add Daily Diamond Entry

### Request
```http
POST /daily-entry/add
Content-Type: application/json

{
  "android_id": "550e8400-e29b-41d4-a716-446655440000",
  "entry_date": "2025-01-13",
  "weight": 10.500,
  "rate": 5000.00,
  "total_amount": 52500.00
}
```

### Response
```json
{
  "success": true,
  "message": "Daily entry added successfully",
  "data": {
    "id": 1,
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "entry_date": "2025-01-13",
    "weight": "10.500",
    "rate": "5000.00",
    "total_amount": "52500.00",
    "created_at": "2025-01-13 12:15:00",
    "updated_at": "2025-01-13 12:15:00"
  }
}
```

---

## 6. Get All Daily Entries

### Request
```http
GET /daily-entry/list?android_id=550e8400-e29b-41d4-a716-446655440000
```

### Response
```json
{
  "success": true,
  "message": "Entries retrieved successfully",
  "data": [
    {
      "id": 1,
      "android_id": "550e8400-e29b-41d4-a716-446655440000",
      "entry_date": "2025-01-13",
      "weight": "10.500",
      "rate": "5000.00",
      "total_amount": "52500.00",
      "created_at": "2025-01-13 12:15:00",
      "updated_at": "2025-01-13 12:15:00"
    },
    {
      "id": 2,
      "android_id": "550e8400-e29b-41d4-a716-446655440000",
      "entry_date": "2025-01-12",
      "weight": "8.250",
      "rate": "5000.00",
      "total_amount": "41250.00",
      "created_at": "2025-01-12 10:30:00",
      "updated_at": "2025-01-12 10:30:00"
    }
  ]
}
```

---

## 7. Add Withdrawal

### Request
```http
POST /withdrawal/add
Content-Type: application/json

{
  "android_id": "550e8400-e29b-41d4-a716-446655440000",
  "withdrawal_date": "2025-01-13",
  "amount": 10000.00
}
```

### Response
```json
{
  "success": true,
  "message": "Withdrawal added successfully",
  "data": {
    "id": 1,
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "withdrawal_date": "2025-01-13",
    "amount": "10000.00",
    "created_at": "2025-01-13 12:20:00",
    "updated_at": "2025-01-13 12:20:00"
  }
}
```

---

## 8. Get Withdrawal History

### Request
```http
GET /withdrawal/history?android_id=550e8400-e29b-41d4-a716-446655440000
```

### Response
```json
{
  "success": true,
  "message": "Withdrawal history retrieved successfully",
  "data": [
    {
      "date": "2025-01-13",
      "total_amount": 15000.00,
      "count": 2,
      "withdrawals": [
        {
          "id": 1,
          "amount": "10000.00",
          "withdrawal_date": "2025-01-13"
        },
        {
          "id": 2,
          "amount": "5000.00",
          "withdrawal_date": "2025-01-13"
        }
      ]
    },
    {
      "date": "2025-01-12",
      "total_amount": 8000.00,
      "count": 1,
      "withdrawals": [
        {
          "id": 3,
          "amount": "8000.00",
          "withdrawal_date": "2025-01-12"
        }
      ]
    }
  ]
}
```

---

## 9. Get Monthly Report

### Request
```http
GET /report/monthly?android_id=550e8400-e29b-41d4-a716-446655440000&year=2025&month=1
```

### Response
```json
{
  "success": true,
  "message": "Monthly report retrieved successfully",
  "data": {
    "year": 2025,
    "month": 1,
    "month_name": "January",
    "total_diamonds": 15,
    "total_weight": 150.750,
    "total_amount": 753750.00,
    "total_withdrawals": 50000.00,
    "remaining_balance": 703750.00,
    "entries": [...],
    "withdrawals": [...]
  }
}
```

---

## 10. Get Date-wise Report

### Request
```http
GET /report/date-wise?android_id=550e8400-e29b-41d4-a716-446655440000&start_date=2025-01-01&end_date=2025-01-31
```

### Response
```json
{
  "success": true,
  "message": "Date-wise report retrieved successfully",
  "data": {
    "start_date": "2025-01-01",
    "end_date": "2025-01-31",
    "total_diamonds": 25,
    "total_weight": 250.500,
    "total_amount": 1252500.00,
    "total_withdrawals": 75000.00,
    "remaining_balance": 1177500.00,
    "entries": [...],
    "withdrawals": [...]
  }
}
```

---

## 11. Create Backup

### Request
```http
GET /backup/create?android_id=550e8400-e29b-41d4-a716-446655440000
```

### Response
```json
{
  "success": true,
  "message": "Backup created successfully",
  "data": {
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "backup_date": "2025-01-13 12:30:00",
    "user": {
      "name": "Rajesh Kumar",
      "phone": "9876543210",
      "email": "rajesh@example.com",
      "created_at": "2025-01-13 12:00:00",
      "updated_at": "2025-01-13 12:05:00"
    },
    "diamond_rate": {
      "rate": "5000.00",
      "created_at": "2025-01-13 12:10:00",
      "updated_at": "2025-01-13 12:10:00"
    },
    "daily_entries": [
      {
        "id": 1,
        "entry_date": "2025-01-13",
        "weight": "10.500",
        "rate": "5000.00",
        "total_amount": "52500.00"
      }
    ],
    "withdrawals": [
      {
        "id": 1,
        "withdrawal_date": "2025-01-13",
        "amount": "10000.00"
      }
    ],
    "summary": {
      "total_entries": 15,
      "total_withdrawals": 5,
      "total_entry_amount": 753750.00,
      "total_withdrawal_amount": 50000.00
    }
  }
}
```

---

## 12. Restore Backup

### Request
```http
POST /backup/restore
Content-Type: application/json

{
  "android_id": "550e8400-e29b-41d4-a716-446655440000",
  "backup_data": {
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "backup_date": "2025-01-13 12:30:00",
    "user": {
      "name": "Rajesh Kumar",
      "phone": "9876543210",
      "email": "rajesh@example.com"
    },
    "diamond_rate": {
      "rate": 5000.00
    },
    "daily_entries": [
      {
        "entry_date": "2025-01-13",
        "weight": 10.500,
        "rate": 5000.00,
        "total_amount": 52500.00
      }
    ],
    "withdrawals": [
      {
        "withdrawal_date": "2025-01-13",
        "amount": 10000.00
      }
    ]
  }
}
```

### Response
```json
{
  "success": true,
  "message": "Backup restored successfully",
  "data": {
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "restore_date": "2025-01-13 12:35:00",
    "user_restored": true,
    "entries_restored": 15,
    "withdrawals_restored": 5
  }
}
```

---

## Error Examples

### Missing Required Field
```json
{
  "success": false,
  "message": "Missing required fields: android_id, entry_date",
  "data": null
}
```

### User Not Found
```json
{
  "success": false,
  "message": "User not found",
  "data": null
}
```

### Invalid Date Format
```json
{
  "success": false,
  "message": "Invalid date format. Use YYYY-MM-DD",
  "data": null
}
```

---

## Testing with cURL

### Initialize User
```bash
curl -X POST http://localhost/HiraBook/api/user/initialize \
  -H "Content-Type: application/json" \
  -d '{
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "fcm_token": "test-token"
  }'
```

### Get Dashboard
```bash
curl "http://localhost/HiraBook/api/dashboard/index?android_id=550e8400-e29b-41d4-a716-446655440000"
```

### Add Entry
```bash
curl -X POST http://localhost/HiraBook/api/daily-entry/add \
  -H "Content-Type: application/json" \
  -d '{
    "android_id": "550e8400-e29b-41d4-a716-446655440000",
    "entry_date": "2025-01-13",
    "weight": 10.5,
    "rate": 5000,
    "total_amount": 52500
  }'
```

