<?php
/**
 * Daily Entry Controller
 * Handles daily diamond entry CRUD operations
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DailyEntryModel.php';

class DailyEntryController {
    private $userModel;
    private $entryModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->entryModel = new DailyEntryModel();
    }

    /**
     * Add daily entry
     */
    public function addEntry() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            validateRequired(['android_id', 'entry_date', 'weight', 'rate', 'total_amount'], $data);

            $android_id = trim($data['android_id']);
            $entry_date = trim($data['entry_date']);
            $weight = floatval($data['weight']);
            $rate = floatval($data['rate']);
            $total_amount = floatval($data['total_amount']);

            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $entry_date)) {
                sendError("Invalid date format. Use YYYY-MM-DD");
            }

            if ($weight <= 0 || $rate <= 0 || $total_amount <= 0) {
                sendError("Weight, rate, and total amount must be greater than 0");
            }

            // Ensure user exists
            if (!$this->userModel->userExists($android_id)) {
                sendError("User not found", 404);
            }

            $entryId = $this->entryModel->addEntry($android_id, $entry_date, $weight, $rate, $total_amount);
            
            if ($entryId) {
                $entry = $this->entryModel->getEntry($entryId, $android_id);
                sendSuccess("Daily entry added successfully", $entry);
            } else {
                sendError("Failed to add daily entry", 500);
            }
        } catch(Exception $e) {
            error_log("DailyEntryController::addEntry Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get all entries
     */
    public function getAllEntries() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            $start_date = $_GET['start_date'] ?? null;
            $end_date = $_GET['end_date'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            $entries = $this->entryModel->getAllEntries($android_id, $start_date, $end_date);
            
            if ($entries !== false) {
                sendSuccess("Entries retrieved successfully", $entries);
            } else {
                sendError("Failed to retrieve entries", 500);
            }
        } catch(Exception $e) {
            error_log("DailyEntryController::getAllEntries Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get entry by ID
     */
    public function getEntry() {
        try {
            $id = $_GET['id'] ?? null;
            $android_id = $_GET['android_id'] ?? null;
            
            if (!$id || !$android_id) {
                sendError("ID and Android ID are required");
            }

            $entry = $this->entryModel->getEntry($id, $android_id);
            
            if ($entry) {
                sendSuccess("Entry retrieved successfully", $entry);
            } else {
                sendError("Entry not found", 404);
            }
        } catch(Exception $e) {
            error_log("DailyEntryController::getEntry Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Update entry
     */
    public function updateEntry() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            validateRequired(['id', 'android_id', 'entry_date', 'weight', 'rate', 'total_amount'], $data);

            $id = intval($data['id']);
            $android_id = trim($data['android_id']);
            $entry_date = trim($data['entry_date']);
            $weight = floatval($data['weight']);
            $rate = floatval($data['rate']);
            $total_amount = floatval($data['total_amount']);

            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $entry_date)) {
                sendError("Invalid date format. Use YYYY-MM-DD");
            }

            if ($weight <= 0 || $rate <= 0 || $total_amount <= 0) {
                sendError("Weight, rate, and total amount must be greater than 0");
            }

            $result = $this->entryModel->updateEntry($id, $android_id, $entry_date, $weight, $rate, $total_amount);
            
            if ($result) {
                $entry = $this->entryModel->getEntry($id, $android_id);
                sendSuccess("Entry updated successfully", $entry);
            } else {
                sendError("Failed to update entry or entry not found", 500);
            }
        } catch(Exception $e) {
            error_log("DailyEntryController::updateEntry Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Delete entry
     */
    public function deleteEntry() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id']) || !isset($data['android_id'])) {
                sendError("ID and Android ID are required");
            }

            $id = intval($data['id']);
            $android_id = trim($data['android_id']);

            $result = $this->entryModel->deleteEntry($id, $android_id);
            
            if ($result) {
                sendSuccess("Entry deleted successfully");
            } else {
                sendError("Failed to delete entry or entry not found", 500);
            }
        } catch(Exception $e) {
            error_log("DailyEntryController::deleteEntry Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }
}

