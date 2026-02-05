<?php
/**
 * Withdrawal Controller
 * Handles withdrawal CRUD operations
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/WithdrawalModel.php';

class WithdrawalController {
    private $userModel;
    private $withdrawalModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->withdrawalModel = new WithdrawalModel();
    }

    /**
     * Add withdrawal
     */
    public function addWithdrawal() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            validateRequired(['android_id', 'withdrawal_date', 'amount'], $data);

            $android_id = trim($data['android_id']);
            $withdrawal_date = trim($data['withdrawal_date']);
            $amount = floatval($data['amount']);

            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $withdrawal_date)) {
                sendError("Invalid date format. Use YYYY-MM-DD");
            }

            if ($amount <= 0) {
                sendError("Amount must be greater than 0");
            }

            // Ensure user exists
            if (!$this->userModel->userExists($android_id)) {
                sendError("User not found", 404);
            }

            $withdrawalId = $this->withdrawalModel->addWithdrawal($android_id, $withdrawal_date, $amount);
            
            if ($withdrawalId) {
                $withdrawal = $this->withdrawalModel->getWithdrawal($withdrawalId, $android_id);
                sendSuccess("Withdrawal added successfully", $withdrawal);
            } else {
                sendError("Failed to add withdrawal", 500);
            }
        } catch(Exception $e) {
            error_log("WithdrawalController::addWithdrawal Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get all withdrawals
     */
    public function getAllWithdrawals() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            $start_date = $_GET['start_date'] ?? null;
            $end_date = $_GET['end_date'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            $withdrawals = $this->withdrawalModel->getAllWithdrawals($android_id, $start_date, $end_date);
            
            if ($withdrawals !== false) {
                sendSuccess("Withdrawals retrieved successfully", $withdrawals);
            } else {
                sendError("Failed to retrieve withdrawals", 500);
            }
        } catch(Exception $e) {
            error_log("WithdrawalController::getAllWithdrawals Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get withdrawal by ID
     */
    public function getWithdrawal() {
        try {
            $id = $_GET['id'] ?? null;
            $android_id = $_GET['android_id'] ?? null;
            
            if (!$id || !$android_id) {
                sendError("ID and Android ID are required");
            }

            $withdrawal = $this->withdrawalModel->getWithdrawal($id, $android_id);
            
            if ($withdrawal) {
                sendSuccess("Withdrawal retrieved successfully", $withdrawal);
            } else {
                sendError("Withdrawal not found", 404);
            }
        } catch(Exception $e) {
            error_log("WithdrawalController::getWithdrawal Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get withdrawal history (date-wise)
     */
    public function getWithdrawalHistory() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            $withdrawals = $this->withdrawalModel->getAllWithdrawals($android_id);
            
            if ($withdrawals !== false) {
                // Group by date
                $history = [];
                foreach ($withdrawals as $withdrawal) {
                    $date = $withdrawal['withdrawal_date'];
                    if (!isset($history[$date])) {
                        $history[$date] = [
                            'date' => $date,
                            'total_amount' => 0,
                            'count' => 0,
                            'withdrawals' => []
                        ];
                    }
                    $history[$date]['total_amount'] += floatval($withdrawal['amount']);
                    $history[$date]['count']++;
                    $history[$date]['withdrawals'][] = $withdrawal;
                }
                
                // Convert to indexed array
                $historyArray = array_values($history);
                
                sendSuccess("Withdrawal history retrieved successfully", $historyArray);
            } else {
                sendError("Failed to retrieve withdrawal history", 500);
            }
        } catch(Exception $e) {
            error_log("WithdrawalController::getWithdrawalHistory Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Update withdrawal
     */
    public function updateWithdrawal() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            validateRequired(['id', 'android_id', 'withdrawal_date', 'amount'], $data);

            $id = intval($data['id']);
            $android_id = trim($data['android_id']);
            $withdrawal_date = trim($data['withdrawal_date']);
            $amount = floatval($data['amount']);

            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $withdrawal_date)) {
                sendError("Invalid date format. Use YYYY-MM-DD");
            }

            if ($amount <= 0) {
                sendError("Amount must be greater than 0");
            }

            $result = $this->withdrawalModel->updateWithdrawal($id, $android_id, $withdrawal_date, $amount);
            
            if ($result) {
                $withdrawal = $this->withdrawalModel->getWithdrawal($id, $android_id);
                sendSuccess("Withdrawal updated successfully", $withdrawal);
            } else {
                sendError("Failed to update withdrawal or withdrawal not found", 500);
            }
        } catch(Exception $e) {
            error_log("WithdrawalController::updateWithdrawal Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Delete withdrawal
     */
    public function deleteWithdrawal() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id']) || !isset($data['android_id'])) {
                sendError("ID and Android ID are required");
            }

            $id = intval($data['id']);
            $android_id = trim($data['android_id']);

            $result = $this->withdrawalModel->deleteWithdrawal($id, $android_id);
            
            if ($result) {
                sendSuccess("Withdrawal deleted successfully");
            } else {
                sendError("Failed to delete withdrawal or withdrawal not found", 500);
            }
        } catch(Exception $e) {
            error_log("WithdrawalController::deleteWithdrawal Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }
}

