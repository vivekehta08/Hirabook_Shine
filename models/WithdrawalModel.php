<?php
/**
 * Withdrawal Model
 * Handles withdrawal CRUD operations
 */

require_once __DIR__ . '/../config/database.php';

class WithdrawalModel {
    private $conn;
    private $table = "withdrawals";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Add withdrawal
     */
    public function addWithdrawal($android_id, $withdrawal_date, $amount) {
        try {
            $query = "INSERT INTO " . $this->table . " (android_id, withdrawal_date, amount) 
                      VALUES (:android_id, :withdrawal_date, :amount)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":withdrawal_date", $withdrawal_date);
            $stmt->bindParam(":amount", $amount);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            error_log("WithdrawalModel::addWithdrawal Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get withdrawal by ID
     */
    public function getWithdrawal($id, $android_id) {
        try {
            $query = "SELECT id, android_id, withdrawal_date, amount, created_at, updated_at 
                      FROM " . $this->table . " WHERE id = :id AND android_id = :android_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("WithdrawalModel::getWithdrawal Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all withdrawals for user
     */
    public function getAllWithdrawals($android_id, $start_date = null, $end_date = null) {
        try {
            $query = "SELECT id, android_id, withdrawal_date, amount, created_at, updated_at 
                      FROM " . $this->table . " WHERE android_id = :android_id";
            
            if ($start_date && $end_date) {
                $query .= " AND withdrawal_date BETWEEN :start_date AND :end_date";
            }
            
            $query .= " ORDER BY withdrawal_date DESC, id DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            
            if ($start_date && $end_date) {
                $stmt->bindParam(":start_date", $start_date);
                $stmt->bindParam(":end_date", $end_date);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("WithdrawalModel::getAllWithdrawals Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update withdrawal
     */
    public function updateWithdrawal($id, $android_id, $withdrawal_date, $amount) {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET withdrawal_date = :withdrawal_date, amount = :amount, updated_at = NOW() 
                      WHERE id = :id AND android_id = :android_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":withdrawal_date", $withdrawal_date);
            $stmt->bindParam(":amount", $amount);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("WithdrawalModel::updateWithdrawal Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete withdrawal
     */
    public function deleteWithdrawal($id, $android_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id AND android_id = :android_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("WithdrawalModel::deleteWithdrawal Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get monthly withdrawal total
     */
    public function getMonthlyWithdrawal($android_id, $year, $month) {
        try {
            $query = "SELECT 
                        COALESCE(SUM(amount), 0) as total_withdrawal,
                        COUNT(*) as total_count
                      FROM " . $this->table . " 
                      WHERE android_id = :android_id 
                      AND YEAR(withdrawal_date) = :year 
                      AND MONTH(withdrawal_date) = :month";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":year", $year);
            $stmt->bindParam(":month", $month);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("WithdrawalModel::getMonthlyWithdrawal Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get current month withdrawal total
     */
    public function getCurrentMonthWithdrawal($android_id) {
        try {
            $query = "SELECT 
                        COALESCE(SUM(amount), 0) as total_withdrawal,
                        COUNT(*) as total_count
                      FROM " . $this->table . " 
                      WHERE android_id = :android_id 
                      AND YEAR(withdrawal_date) = YEAR(CURDATE()) 
                      AND MONTH(withdrawal_date) = MONTH(CURDATE())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("WithdrawalModel::getCurrentMonthWithdrawal Error: " . $e->getMessage());
            return false;
        }
    }
}

