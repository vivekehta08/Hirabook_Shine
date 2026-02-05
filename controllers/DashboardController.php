<?php
/**
 * Dashboard Controller
 * Handles dashboard API endpoints
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DailyEntryModel.php';
require_once __DIR__ . '/../models/WithdrawalModel.php';

class DashboardController {
    private $userModel;
    private $dailyEntryModel;
    private $withdrawalModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->dailyEntryModel = new DailyEntryModel();
        $this->withdrawalModel = new WithdrawalModel();
    }

    /**
     * Get dashboard data (current month totals)
     */
    public function getDashboard() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            // Ensure user exists
            if (!$this->userModel->userExists($android_id)) {
                sendError("User not found", 404);
            }

            // Get current month totals
            $entryTotals = $this->dailyEntryModel->getCurrentMonthTotals($android_id);
            $withdrawalTotals = $this->withdrawalModel->getCurrentMonthWithdrawal($android_id);

            $current_month_total = floatval($entryTotals['total_amount'] ?? 0);
            $current_month_withdrawal = floatval($withdrawalTotals['total_withdrawal'] ?? 0);
            $current_month_remain_total = $current_month_total - $current_month_withdrawal;

            $dashboardData = [
                'current_month_total' => round($current_month_total, 2),
                'current_month_withdrawal' => round($current_month_withdrawal, 2),
                'current_month_remain_total' => round($current_month_remain_total, 2)
            ];

            sendSuccess("Dashboard data retrieved successfully", $dashboardData);
        } catch(Exception $e) {
            error_log("DashboardController::getDashboard Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }
}

