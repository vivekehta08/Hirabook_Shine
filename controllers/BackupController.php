<?php
/**
 * Backup & Restore Controller
 * Handles full app backup and restore functionality
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DiamondRateModel.php';
require_once __DIR__ . '/../models/DailyEntryModel.php';
require_once __DIR__ . '/../models/WithdrawalModel.php';

class BackupController {
    private $userModel;
    private $rateModel;
    private $entryModel;
    private $withdrawalModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->rateModel = new DiamondRateModel();
        $this->entryModel = new DailyEntryModel();
        $this->withdrawalModel = new WithdrawalModel();
    }

    /**
     * Create full backup of user data
     */
    public function createBackup() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            // Get user details
            $user = $this->userModel->getUserByAndroidId($android_id);
            
            if (!$user) {
                sendError("User not found", 404);
            }

            // Get diamond rate
            $diamondRate = $this->rateModel->getRate($android_id);
            
            // Get all entries
            $entries = $this->entryModel->getAllEntries($android_id);
            
            // Get all withdrawals
            $withdrawals = $this->withdrawalModel->getAllWithdrawals($android_id);

            // Prepare backup data
            $backupData = [
                'android_id' => $android_id,
                'backup_date' => date('Y-m-d H:i:s'),
                'user' => [
                    'name' => $user['name'],
                    'phone' => $user['phone'],
                    'email' => $user['email'],
                    'created_at' => $user['created_at'],
                    'updated_at' => $user['updated_at']
                ],
                'diamond_rate' => $diamondRate ? [
                    'rate' => $diamondRate['rate'],
                    'created_at' => $diamondRate['created_at'],
                    'updated_at' => $diamondRate['updated_at']
                ] : null,
                'daily_entries' => $entries ?: [],
                'withdrawals' => $withdrawals ?: [],
                'summary' => [
                    'total_entries' => count($entries ?: []),
                    'total_withdrawals' => count($withdrawals ?: []),
                    'total_entry_amount' => array_sum(array_column($entries ?: [], 'total_amount')),
                    'total_withdrawal_amount' => array_sum(array_column($withdrawals ?: [], 'amount'))
                ]
            ];

            sendSuccess("Backup created successfully", $backupData);
        } catch(Exception $e) {
            error_log("BackupController::createBackup Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Restore user data from backup
     */
    public function restoreBackup() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['android_id']) || empty($data['android_id'])) {
                sendError("Android ID is required");
            }

            $android_id = trim($data['android_id']);

            // Validate backup data structure
            if (!isset($data['backup_data'])) {
                sendError("Backup data is required");
            }

            $backupData = $data['backup_data'];

            // Ensure android_id matches
            if (isset($backupData['android_id']) && $backupData['android_id'] !== $android_id) {
                sendError("Android ID mismatch");
            }

            // Start transaction (if supported)
            $database = new Database();
            $conn = $database->getConnection();
            $conn->beginTransaction();

            try {
                // Create or update user
                $fcm_token = null; // FCM token not in backup
                $user = $this->userModel->createOrUpdateUser($android_id, $fcm_token);

                // Restore user profile if available
                if (isset($backupData['user'])) {
                    $userData = $backupData['user'];
                    $name = $userData['name'] ?? null;
                    $phone = $userData['phone'] ?? null;
                    $email = $userData['email'] ?? null;
                    
                    if ($name && $phone) {
                        $this->userModel->updateProfile($android_id, $name, $phone, $email);
                    }
                }

                // Restore diamond rate
                if (isset($backupData['diamond_rate']) && $backupData['diamond_rate']) {
                    $rate = floatval($backupData['diamond_rate']['rate']);
                    if ($rate > 0) {
                        $this->rateModel->addRate($android_id, $rate);
                    }
                }

                // Clear existing entries and withdrawals (optional - you may want to merge instead)
                // For now, we'll add new entries (assuming IDs might conflict, you may need to handle this differently)

                // Restore daily entries
                if (isset($backupData['daily_entries']) && is_array($backupData['daily_entries'])) {
                    foreach ($backupData['daily_entries'] as $entry) {
                        // Skip ID and timestamps from backup, use current values
                        $this->entryModel->addEntry(
                            $android_id,
                            $entry['entry_date'],
                            floatval($entry['weight']),
                            floatval($entry['rate']),
                            floatval($entry['total_amount'])
                        );
                    }
                }

                // Restore withdrawals
                if (isset($backupData['withdrawals']) && is_array($backupData['withdrawals'])) {
                    foreach ($backupData['withdrawals'] as $withdrawal) {
                        // Skip ID and timestamps from backup
                        $this->withdrawalModel->addWithdrawal(
                            $android_id,
                            $withdrawal['withdrawal_date'],
                            floatval($withdrawal['amount'])
                        );
                    }
                }

                $conn->commit();

                // Get restored data summary
                $restoredUser = $this->userModel->getUserByAndroidId($android_id);
                $restoredEntries = $this->entryModel->getAllEntries($android_id);
                $restoredWithdrawals = $this->withdrawalModel->getAllWithdrawals($android_id);

                $restoreSummary = [
                    'android_id' => $android_id,
                    'restore_date' => date('Y-m-d H:i:s'),
                    'user_restored' => $restoredUser ? true : false,
                    'entries_restored' => count($restoredEntries ?: []),
                    'withdrawals_restored' => count($restoredWithdrawals ?: [])
                ];

                sendSuccess("Backup restored successfully", $restoreSummary);
            } catch(Exception $e) {
                $conn->rollBack();
                throw $e;
            }
        } catch(Exception $e) {
            error_log("BackupController::restoreBackup Error: " . $e->getMessage());
            sendError("Server error occurred: " . $e->getMessage(), 500);
        }
    }
}

