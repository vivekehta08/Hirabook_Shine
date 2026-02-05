<?php
/**
 * Report Controller
 * Handles report generation (date-wise and monthly)
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DailyEntryModel.php';
require_once __DIR__ . '/../models/WithdrawalModel.php';

class ReportController {
    private $userModel;
    private $dailyEntryModel;
    private $withdrawalModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->dailyEntryModel = new DailyEntryModel();
        $this->withdrawalModel = new WithdrawalModel();
    }

    /**
     * Get date-wise report
     */
    public function getDateWiseReport() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            $start_date = $_GET['start_date'] ?? null;
            $end_date = $_GET['end_date'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            if (!$start_date || !$end_date) {
                sendError("Start date and end date are required");
            }

            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
                sendError("Invalid date format. Use YYYY-MM-DD");
            }

            // Ensure user exists
            if (!$this->userModel->userExists($android_id)) {
                sendError("User not found", 404);
            }

            // Get entries and withdrawals for date range
            $entries = $this->dailyEntryModel->getAllEntries($android_id, $start_date, $end_date);
            $withdrawals = $this->withdrawalModel->getAllWithdrawals($android_id, $start_date, $end_date);

            // Calculate totals
            $total_diamonds = 0;
            $total_weight = 0;
            $total_amount = 0;
            $total_withdrawals = 0;

            if ($entries) {
                foreach ($entries as $entry) {
                    $total_weight += floatval($entry['weight']);
                    $total_amount += floatval($entry['total_amount']);
                }
                $total_diamonds = count($entries);
            }

            if ($withdrawals) {
                foreach ($withdrawals as $withdrawal) {
                    $total_withdrawals += floatval($withdrawal['amount']);
                }
            }

            $remaining_balance = $total_amount - $total_withdrawals;

            $report = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'total_diamonds' => $total_diamonds,
                'total_weight' => round($total_weight, 3),
                'total_amount' => round($total_amount, 2),
                'total_withdrawals' => round($total_withdrawals, 2),
                'remaining_balance' => round($remaining_balance, 2),
                'entries' => $entries ?: [],
                'withdrawals' => $withdrawals ?: []
            ];

            sendSuccess("Date-wise report retrieved successfully", $report);
        } catch(Exception $e) {
            error_log("ReportController::getDateWiseReport Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get monthly report
     */
    public function getMonthlyReport() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            $year = $_GET['year'] ?? date('Y');
            $month = $_GET['month'] ?? date('m');
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            $year = intval($year);
            $month = intval($month);

            if ($month < 1 || $month > 12) {
                sendError("Invalid month. Must be between 1 and 12");
            }

            // Ensure user exists
            if (!$this->userModel->userExists($android_id)) {
                sendError("User not found", 404);
            }

            // Get monthly totals
            $entryTotals = $this->dailyEntryModel->getMonthlyTotals($android_id, $year, $month);
            $withdrawalTotals = $this->withdrawalModel->getMonthlyWithdrawal($android_id, $year, $month);

            // Get all entries and withdrawals for the month
            $start_date = sprintf('%04d-%02d-01', $year, $month);
            $end_date = date('Y-m-t', strtotime($start_date)); // Last day of month

            $entries = $this->dailyEntryModel->getAllEntries($android_id, $start_date, $end_date);
            $withdrawals = $this->withdrawalModel->getAllWithdrawals($android_id, $start_date, $end_date);

            $total_diamonds = intval($entryTotals['total_entries'] ?? 0);
            $total_weight = floatval($entryTotals['total_weight'] ?? 0);
            $total_amount = floatval($entryTotals['total_amount'] ?? 0);
            $total_withdrawals = floatval($withdrawalTotals['total_withdrawal'] ?? 0);
            $remaining_balance = $total_amount - $total_withdrawals;

            $report = [
                'year' => $year,
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1, $year)),
                'total_diamonds' => $total_diamonds,
                'total_weight' => round($total_weight, 3),
                'total_amount' => round($total_amount, 2),
                'total_withdrawals' => round($total_withdrawals, 2),
                'remaining_balance' => round($remaining_balance, 2),
                'entries' => $entries ?: [],
                'withdrawals' => $withdrawals ?: []
            ];

            sendSuccess("Monthly report retrieved successfully", $report);
        } catch(Exception $e) {
            error_log("ReportController::getMonthlyReport Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get all monthly reports summary
     */
    public function getAllMonthlyReports() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            // Ensure user exists
            if (!$this->userModel->userExists($android_id)) {
                sendError("User not found", 404);
            }

            // Get all entries to determine date range
            $allEntries = $this->dailyEntryModel->getAllEntries($android_id);
            
            if (empty($allEntries)) {
                sendSuccess("No data available", []);
            }

            // Group by year-month
            $monthlyData = [];
            
            foreach ($allEntries as $entry) {
                $date = new DateTime($entry['entry_date']);
                $year = $date->format('Y');
                $month = $date->format('m');
                $key = $year . '-' . $month;
                
                if (!isset($monthlyData[$key])) {
                    $monthlyData[$key] = [
                        'year' => intval($year),
                        'month' => intval($month),
                        'month_name' => $date->format('F'),
                        'total_diamonds' => 0,
                        'total_weight' => 0,
                        'total_amount' => 0,
                        'total_withdrawals' => 0
                    ];
                }
                
                $monthlyData[$key]['total_diamonds']++;
                $monthlyData[$key]['total_weight'] += floatval($entry['weight']);
                $monthlyData[$key]['total_amount'] += floatval($entry['total_amount']);
            }

            // Add withdrawal totals for each month
            foreach ($monthlyData as $key => &$data) {
                $withdrawalTotals = $this->withdrawalModel->getMonthlyWithdrawal(
                    $android_id, 
                    $data['year'], 
                    $data['month']
                );
                $data['total_withdrawals'] = floatval($withdrawalTotals['total_withdrawal'] ?? 0);
                $data['remaining_balance'] = round($data['total_amount'] - $data['total_withdrawals'], 2);
                $data['total_weight'] = round($data['total_weight'], 3);
                $data['total_amount'] = round($data['total_amount'], 2);
                $data['total_withdrawals'] = round($data['total_withdrawals'], 2);
            }

            // Sort by year-month descending
            krsort($monthlyData);
            $reports = array_values($monthlyData);

            sendSuccess("Monthly reports retrieved successfully", $reports);
        } catch(Exception $e) {
            error_log("ReportController::getAllMonthlyReports Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }
}

