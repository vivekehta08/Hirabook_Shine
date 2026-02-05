<?php
/**
 * Data Verification Script
 * Verify all data for Android ID: a4f99a889de0ce32
 */

require_once __DIR__ . '/config/database.php';

$android_id = "a4f99a889de0ce32";

echo "========================================\n";
echo "Data Verification for Android ID\n";
echo "Android ID: $android_id\n";
echo "========================================\n\n";

try {
    $database = new Database();
    $conn = $database->getConnection();

    // 1. Check User
    echo "1. USER INFORMATION:\n";
    echo "-------------------\n";
    $query = "SELECT * FROM users WHERE android_id = :android_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":android_id", $android_id);
    $stmt->execute();
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✓ User Found\n";
        echo "  ID: " . $user['id'] . "\n";
        echo "  Name: " . ($user['name'] ?: 'Not set') . "\n";
        echo "  Phone: " . ($user['phone'] ?: 'Not set') . "\n";
        echo "  Email: " . ($user['email'] ?: 'Not set') . "\n";
        echo "  FCM Token: " . (substr($user['fcm_token'] ?: 'Not set', 0, 30)) . "...\n";
        echo "  Created: " . $user['created_at'] . "\n";
        echo "  Updated: " . $user['updated_at'] . "\n";
    } else {
        echo "✗ User NOT Found\n";
    }
    echo "\n";

    // 2. Check Diamond Rate
    echo "2. DIAMOND RATE:\n";
    echo "----------------\n";
    $query = "SELECT * FROM diamond_rates WHERE android_id = :android_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":android_id", $android_id);
    $stmt->execute();
    $rate = $stmt->fetch();
    
    if ($rate) {
        echo "✓ Diamond Rate Found\n";
        echo "  Rate: ₹" . number_format($rate['rate'], 2) . "\n";
        echo "  Created: " . $rate['created_at'] . "\n";
    } else {
        echo "✗ No Diamond Rate Set\n";
    }
    echo "\n";

    // 3. Check Daily Entries
    echo "3. DAILY ENTRIES:\n";
    echo "-----------------\n";
    $query = "SELECT * FROM daily_entries WHERE android_id = :android_id ORDER BY entry_date DESC, id DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":android_id", $android_id);
    $stmt->execute();
    $entries = $stmt->fetchAll();
    
    if ($entries) {
        echo "✓ Found " . count($entries) . " entries\n\n";
        $total_weight = 0;
        $total_amount = 0;
        
        foreach ($entries as $entry) {
            echo "  Entry #" . $entry['id'] . "\n";
            echo "    Date: " . $entry['entry_date'] . "\n";
            echo "    Weight: " . $entry['weight'] . "\n";
            echo "    Rate: ₹" . number_format($entry['rate'], 2) . "\n";
            echo "    Amount: ₹" . number_format($entry['total_amount'], 2) . "\n";
            echo "    Created: " . $entry['created_at'] . "\n";
            echo "\n";
            
            $total_weight += floatval($entry['weight']);
            $total_amount += floatval($entry['total_amount']);
        }
        
        echo "  TOTALS:\n";
        echo "    Total Entries: " . count($entries) . "\n";
        echo "    Total Weight: " . number_format($total_weight, 3) . "\n";
        echo "    Total Amount: ₹" . number_format($total_amount, 2) . "\n";
    } else {
        echo "✗ No Daily Entries Found\n";
    }
    echo "\n";

    // 4. Check Withdrawals
    echo "4. WITHDRAWALS:\n";
    echo "---------------\n";
    $query = "SELECT * FROM withdrawals WHERE android_id = :android_id ORDER BY withdrawal_date DESC, id DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":android_id", $android_id);
    $stmt->execute();
    $withdrawals = $stmt->fetchAll();
    
    if ($withdrawals) {
        echo "✓ Found " . count($withdrawals) . " withdrawals\n\n";
        $total_withdrawal = 0;
        
        foreach ($withdrawals as $withdrawal) {
            echo "  Withdrawal #" . $withdrawal['id'] . "\n";
            echo "    Date: " . $withdrawal['withdrawal_date'] . "\n";
            echo "    Amount: ₹" . number_format($withdrawal['amount'], 2) . "\n";
            echo "    Created: " . $withdrawal['created_at'] . "\n";
            echo "\n";
            
            $total_withdrawal += floatval($withdrawal['amount']);
        }
        
        echo "  TOTAL WITHDRAWAL: ₹" . number_format($total_withdrawal, 2) . "\n";
    } else {
        echo "✗ No Withdrawals Found\n";
    }
    echo "\n";

    // 5. Summary
    echo "5. SUMMARY:\n";
    echo "-----------\n";
    
    $total_entry_amount = 0;
    if ($entries) {
        foreach ($entries as $entry) {
            $total_entry_amount += floatval($entry['total_amount']);
        }
    }
    
    $total_withdrawal_amount = 0;
    if ($withdrawals) {
        foreach ($withdrawals as $withdrawal) {
            $total_withdrawal_amount += floatval($withdrawal['amount']);
        }
    }
    
    $remaining_balance = $total_entry_amount - $total_withdrawal_amount;
    
    echo "  Total Entries: " . count($entries ?: []) . "\n";
    echo "  Total Entry Amount: ₹" . number_format($total_entry_amount, 2) . "\n";
    echo "  Total Withdrawals: " . count($withdrawals ?: []) . "\n";
    echo "  Total Withdrawal Amount: ₹" . number_format($total_withdrawal_amount, 2) . "\n";
    echo "  Remaining Balance: ₹" . number_format($remaining_balance, 2) . "\n";
    echo "\n";

    // 6. Current Month Totals
    echo "6. CURRENT MONTH (January 2025):\n";
    echo "----------------------------------\n";
    
    $query = "SELECT 
                COALESCE(SUM(weight), 0) as total_weight,
                COALESCE(SUM(total_amount), 0) as total_amount,
                COUNT(*) as total_entries
              FROM daily_entries 
              WHERE android_id = :android_id 
              AND YEAR(entry_date) = YEAR(CURDATE()) 
              AND MONTH(entry_date) = MONTH(CURDATE())";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":android_id", $android_id);
    $stmt->execute();
    $monthEntries = $stmt->fetch();
    
    $query = "SELECT 
                COALESCE(SUM(amount), 0) as total_withdrawal,
                COUNT(*) as total_count
              FROM withdrawals 
              WHERE android_id = :android_id 
              AND YEAR(withdrawal_date) = YEAR(CURDATE()) 
              AND MONTH(withdrawal_date) = MONTH(CURDATE())";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":android_id", $android_id);
    $stmt->execute();
    $monthWithdrawals = $stmt->fetch();
    
    $current_month_total = floatval($monthEntries['total_amount'] ?? 0);
    $current_month_withdrawal = floatval($monthWithdrawals['total_withdrawal'] ?? 0);
    $current_month_remain = $current_month_total - $current_month_withdrawal;
    
    echo "  Current Month Total: ₹" . number_format($current_month_total, 2) . "\n";
    echo "  Current Month Withdrawal: ₹" . number_format($current_month_withdrawal, 2) . "\n";
    echo "  Current Month Remaining: ₹" . number_format($current_month_remain, 2) . "\n";
    echo "\n";

    echo "========================================\n";
    echo "Verification Complete!\n";
    echo "========================================\n";

} catch(Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack Trace: " . $e->getTraceAsString() . "\n";
}

