#!/bin/bash

# Test Script for HiraShine Backend
# Android ID: a4f99a889de0ce32

BASE_URL="http://localhost/HiraBook/api"
ANDROID_ID="a4f99a889de0ce32"

echo "=========================================="
echo "Testing HiraShine Backend API"
echo "Android ID: $ANDROID_ID"
echo "=========================================="
echo ""

# 1. Initialize User
echo "1. Initializing User..."
curl -X POST "${BASE_URL}/user/initialize" \
  -H "Content-Type: application/json" \
  -d "{
    \"android_id\": \"${ANDROID_ID}\",
    \"fcm_token\": \"test_fcm_token_12345\"
  }" | jq '.'
echo ""
echo ""

# 2. Get Dashboard
echo "2. Getting Dashboard Data..."
curl -X GET "${BASE_URL}/dashboard/index?android_id=${ANDROID_ID}" | jq '.'
echo ""
echo ""

# 3. Update User Profile
echo "3. Updating User Profile..."
curl -X PUT "${BASE_URL}/user/profile" \
  -H "Content-Type: application/json" \
  -d "{
    \"android_id\": \"${ANDROID_ID}\",
    \"name\": \"Test User\",
    \"phone\": \"9876543210\",
    \"email\": \"test@example.com\"
  }" | jq '.'
echo ""
echo ""

# 4. Add Diamond Rate
echo "4. Adding Diamond Rate..."
curl -X POST "${BASE_URL}/diamond-rate/add" \
  -H "Content-Type: application/json" \
  -d "{
    \"android_id\": \"${ANDROID_ID}\",
    \"rate\": 5000.00
  }" | jq '.'
echo ""
echo ""

# 5. Add Daily Entry
echo "5. Adding Daily Entry..."
curl -X POST "${BASE_URL}/daily-entry/add" \
  -H "Content-Type: application/json" \
  -d "{
    \"android_id\": \"${ANDROID_ID}\",
    \"entry_date\": \"2025-01-13\",
    \"weight\": 10.500,
    \"rate\": 5000.00,
    \"total_amount\": 52500.00
  }" | jq '.'
echo ""
echo ""

# 6. Add Another Daily Entry
echo "6. Adding Another Daily Entry..."
curl -X POST "${BASE_URL}/daily-entry/add" \
  -H "Content-Type: application/json" \
  -d "{
    \"android_id\": \"${ANDROID_ID}\",
    \"entry_date\": \"2025-01-14\",
    \"weight\": 8.250,
    \"rate\": 5000.00,
    \"total_amount\": 41250.00
  }" | jq '.'
echo ""
echo ""

# 7. Add Withdrawal
echo "7. Adding Withdrawal..."
curl -X POST "${BASE_URL}/withdrawal/add" \
  -H "Content-Type: application/json" \
  -d "{
    \"android_id\": \"${ANDROID_ID}\",
    \"withdrawal_date\": \"2025-01-13\",
    \"amount\": 10000.00
  }" | jq '.'
echo ""
echo ""

# 8. Get All Entries
echo "8. Getting All Daily Entries..."
curl -X GET "${BASE_URL}/daily-entry/list?android_id=${ANDROID_ID}" | jq '.'
echo ""
echo ""

# 9. Get All Withdrawals
echo "9. Getting All Withdrawals..."
curl -X GET "${BASE_URL}/withdrawal/list?android_id=${ANDROID_ID}" | jq '.'
echo ""
echo ""

# 10. Get Dashboard (After Data Entry)
echo "10. Getting Updated Dashboard..."
curl -X GET "${BASE_URL}/dashboard/index?android_id=${ANDROID_ID}" | jq '.'
echo ""
echo ""

# 11. Get Monthly Report
echo "11. Getting Monthly Report..."
curl -X GET "${BASE_URL}/report/monthly?android_id=${ANDROID_ID}&year=2025&month=1" | jq '.'
echo ""
echo ""

# 12. Create Backup
echo "12. Creating Backup..."
curl -X GET "${BASE_URL}/backup/create?android_id=${ANDROID_ID}" | jq '.'
echo ""
echo ""

echo "=========================================="
echo "Testing Complete!"
echo "=========================================="

