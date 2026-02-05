<?php
/**
 * Daily Entry Model
 * Handles daily diamond entry CRUD operations
 */

require_once __DIR__ . '/../config/database.php';

class DailyEntryModel {
    private $conn;
    private $table = "daily_entries";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Add daily entry
     */
    public function addEntry($android_id, $entry_date, $weight, $rate, $total_amount) {
        try {
            $query = "INSERT INTO " . $this->table . " (android_id, entry_date, weight, rate, total_amount) 
                      VALUES (:android_id, :entry_date, :weight, :rate, :total_amount)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":entry_date", $entry_date);
            $stmt->bindParam(":weight", $weight);
            $stmt->bindParam(":rate", $rate);
            $stmt->bindParam(":total_amount", $total_amount);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            error_log("DailyEntryModel::addEntry Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get entry by ID
     */
    public function getEntry($id, $android_id) {
        try {
            $query = "SELECT id, android_id, entry_date, weight, rate, total_amount, created_at, updated_at 
                      FROM " . $this->table . " WHERE id = :id AND android_id = :android_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("DailyEntryModel::getEntry Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all entries for user
     */
    public function getAllEntries($android_id, $start_date = null, $end_date = null) {
        try {
            $query = "SELECT id, android_id, entry_date, weight, rate, total_amount, created_at, updated_at 
                      FROM " . $this->table . " WHERE android_id = :android_id";
            
            if ($start_date && $end_date) {
                $query .= " AND entry_date BETWEEN :start_date AND :end_date";
            }
            
            $query .= " ORDER BY entry_date DESC, id DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            
            if ($start_date && $end_date) {
                $stmt->bindParam(":start_date", $start_date);
                $stmt->bindParam(":end_date", $end_date);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("DailyEntryModel::getAllEntries Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update entry
     */
    public function updateEntry($id, $android_id, $entry_date, $weight, $rate, $total_amount) {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET entry_date = :entry_date, weight = :weight, rate = :rate, 
                          total_amount = :total_amount, updated_at = NOW() 
                      WHERE id = :id AND android_id = :android_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":entry_date", $entry_date);
            $stmt->bindParam(":weight", $weight);
            $stmt->bindParam(":rate", $rate);
            $stmt->bindParam(":total_amount", $total_amount);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("DailyEntryModel::updateEntry Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete entry
     */
    public function deleteEntry($id, $android_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id AND android_id = :android_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("DailyEntryModel::deleteEntry Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get monthly totals
     */
    public function getMonthlyTotals($android_id, $year, $month) {
        try {
            $query = "SELECT 
                        COALESCE(SUM(weight), 0) as total_weight,
                        COALESCE(SUM(total_amount), 0) as total_amount,
                        COUNT(*) as total_entries
                      FROM " . $this->table . " 
                      WHERE android_id = :android_id 
                      AND YEAR(entry_date) = :year 
                      AND MONTH(entry_date) = :month";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->bindParam(":year", $year);
            $stmt->bindParam(":month", $month);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("DailyEntryModel::getMonthlyTotals Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get current month totals
     */
    public function getCurrentMonthTotals($android_id) {
        try {
            $query = "SELECT 
                        COALESCE(SUM(weight), 0) as total_weight,
                        COALESCE(SUM(total_amount), 0) as total_amount,
                        COUNT(*) as total_entries
                      FROM " . $this->table . " 
                      WHERE android_id = :android_id 
                      AND YEAR(entry_date) = YEAR(CURDATE()) 
                      AND MONTH(entry_date) = MONTH(CURDATE())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":android_id", $android_id);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("DailyEntryModel::getCurrentMonthTotals Error: " . $e->getMessage());
            return false;
        }
    }
}

