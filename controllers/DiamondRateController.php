<?php
/**
 * Diamond Rate Controller
 * Handles diamond rate CRUD operations
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DiamondRateModel.php';

class DiamondRateController {
    private $userModel;
    private $rateModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->rateModel = new DiamondRateModel();
    }

    /**
     * Add or update diamond rate
     */
    public function addRate() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            validateRequired(['android_id', 'rate'], $data);

            $android_id = trim($data['android_id']);
            $rate = floatval($data['rate']);

            if ($rate <= 0) {
                sendError("Rate must be greater than 0");
            }

            // Ensure user exists
            if (!$this->userModel->userExists($android_id)) {
                sendError("User not found", 404);
            }

            $result = $this->rateModel->addRate($android_id, $rate);
            
            if ($result) {
                sendSuccess("Diamond rate saved successfully", $result);
            } else {
                sendError("Failed to save diamond rate", 500);
            }
        } catch(Exception $e) {
            error_log("DiamondRateController::addRate Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get diamond rate
     */
    public function getRate() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            $rate = $this->rateModel->getRate($android_id);
            
            if ($rate) {
                sendSuccess("Diamond rate retrieved successfully", $rate);
            } else {
                sendSuccess("No diamond rate found", null);
            }
        } catch(Exception $e) {
            error_log("DiamondRateController::getRate Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Update diamond rate
     */
    public function updateRate() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            validateRequired(['android_id', 'rate'], $data);

            $android_id = trim($data['android_id']);
            $rate = floatval($data['rate']);

            if ($rate <= 0) {
                sendError("Rate must be greater than 0");
            }

            $result = $this->rateModel->updateRate($android_id, $rate);
            
            if ($result) {
                $updatedRate = $this->rateModel->getRate($android_id);
                sendSuccess("Diamond rate updated successfully", $updatedRate);
            } else {
                sendError("Failed to update diamond rate", 500);
            }
        } catch(Exception $e) {
            error_log("DiamondRateController::updateRate Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Delete diamond rate
     */
    public function deleteRate() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['android_id']) || empty($data['android_id'])) {
                sendError("Android ID is required");
            }

            $android_id = trim($data['android_id']);

            $result = $this->rateModel->deleteRate($android_id);
            
            if ($result) {
                sendSuccess("Diamond rate deleted successfully");
            } else {
                sendError("Failed to delete diamond rate or rate not found", 500);
            }
        } catch(Exception $e) {
            error_log("DiamondRateController::deleteRate Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }
}

