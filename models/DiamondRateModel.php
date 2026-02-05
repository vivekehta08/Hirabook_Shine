<?php
/**
 * Diamond Rate Model
 * Handles diamond rate CRUD operations
 */

require_once __DIR__ . '/../config/database.php';

class DiamondRateModel {
    private $conn;
    private $table = "diamond_rates";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Add diamond rate
     */
    public function addRate($android_id, $rate) {
        try {
            // Check if rate already exists for user
            $checkQuery = "SELECT id FROM " . $this->table . " WHERE android_id = :android_id LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":android_id", $android_id);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // Update existing rate
                $query = "UPDATE " . $this->table . " SET rate = :rate, updated_at = NOW() WHERE android_id = :android_id";
            } else {
                // Insert new rate
                $query = "INSERT INTO " . $this->table . " (android_id, rate) VALUES (:android_id, :rate)";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":rate", $rate);
            $stmt->execute();

            return $this->getRate($android_id);
        } catch(PDOException $e) {
            error_log("DiamondRateModel::addRate Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get diamond rate for user
     */
    public function getRate($android_id) {
        try {
            $query = "SELECT id, android_id, rate, created_at, updated_at 
                      FROM " . $this->table . " WHERE android_id = :android_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("DiamondRateModel::getRate Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update diamond rate
     */
    public function updateRate($android_id, $rate) {
        try {
            $query = "UPDATE " . $this->table . " SET rate = :rate, updated_at = NOW() WHERE android_id = :android_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":rate", $rate);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("DiamondRateModel::updateRate Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete diamond rate
     */
    public function deleteRate($android_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE android_id = :android_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("DiamondRateModel::deleteRate Error: " . $e->getMessage());
            return false;
        }
    }
}

