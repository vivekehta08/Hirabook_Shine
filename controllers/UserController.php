<?php
/**
 * User Controller
 * Handles user-related API endpoints
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    /**
     * Initialize user (called on app open)
     * Creates user if not exists, updates FCM token
     */
    public function initialize() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['android_id']) || empty($data['android_id'])) {
                sendError("Android ID is required");
            }

            $android_id = trim($data['android_id']);
            $fcm_token = isset($data['fcm_token']) ? trim($data['fcm_token']) : null;

            $user = $this->userModel->createOrUpdateUser($android_id, $fcm_token);
            
            if ($user) {
                sendSuccess("User initialized successfully", $user);
            } else {
                sendError("Failed to initialize user", 500);
            }
        } catch(Exception $e) {
            error_log("UserController::initialize Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Get user profile
     */
    public function getProfile() {
        try {
            $android_id = $_GET['android_id'] ?? null;
            
            if (!$android_id) {
                sendError("Android ID is required");
            }

            $user = $this->userModel->getUserByAndroidId($android_id);
            
            if ($user) {
                sendSuccess("Profile retrieved successfully", $user);
            } else {
                sendError("User not found", 404);
            }
        } catch(Exception $e) {
            error_log("UserController::getProfile Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            validateRequired(['android_id', 'name', 'phone'], $data);

            $android_id = trim($data['android_id']);
            $name = trim($data['name']);
            $phone = trim($data['phone']);
            $email = isset($data['email']) ? trim($data['email']) : null;

            // Validate email if provided
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                sendError("Invalid email format");
            }

            $result = $this->userModel->updateProfile($android_id, $name, $phone, $email);
            
            if ($result) {
                $user = $this->userModel->getUserByAndroidId($android_id);
                sendSuccess("Profile updated successfully", $user);
            } else {
                sendError("Failed to update profile", 500);
            }
        } catch(Exception $e) {
            error_log("UserController::updateProfile Error: " . $e->getMessage());
            sendError("Server error occurred", 500);
        }
    }
}

