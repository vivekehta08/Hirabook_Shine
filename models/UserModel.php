<?php
/**
 * User Model
 * Handles user operations based on Android ID
 */

require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Create or update user based on Android ID
     */
    public function createOrUpdateUser($android_id, $fcm_token = null) {
        try {
            // Check if user exists
            $query = "SELECT id FROM " . $this->table . " WHERE android_id = :android_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Update existing user (FCM token)
                $query = "UPDATE " . $this->table . " SET fcm_token = :fcm_token, updated_at = NOW() WHERE android_id = :android_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":fcm_token", $fcm_token);
                $stmt->bindParam(":android_id", $android_id);
                $stmt->execute();
                return $this->getUserByAndroidId($android_id);
            } else {
                // Create new user
                $query = "INSERT INTO " . $this->table . " (android_id, fcm_token) VALUES (:android_id, :fcm_token)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":android_id", $android_id);
                $stmt->bindParam(":fcm_token", $fcm_token);
                $stmt->execute();
                return $this->getUserByAndroidId($android_id);
            }
        } catch(PDOException $e) {
            error_log("UserModel::createOrUpdateUser Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user by Android ID
     */
    public function getUserByAndroidId($android_id) {
        try {
            $query = "SELECT id, android_id, fcm_token, name, phone, email, created_at, updated_at 
                      FROM " . $this->table . " WHERE android_id = :android_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("UserModel::getUserByAndroidId Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile($android_id, $name, $phone, $email = null) {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET name = :name, phone = :phone, email = :email, updated_at = NOW() 
                      WHERE android_id = :android_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("UserModel::updateProfile Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user exists
     */
    public function userExists($android_id) {
        try {
            $query = "SELECT id FROM " . $this->table . " WHERE android_id = :android_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("UserModel::userExists Error: " . $e->getMessage());
            return false;
        }
    }
}

