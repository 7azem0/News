<?php
require_once __DIR__ . "/../config/DataBase_Connection.php";

class Subscription {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    // --- Plan Methods ---

    public function getAllPlans() {
        $stmt = $this->conn->query("SELECT * FROM plans ORDER BY price ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlanById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM plans WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function savePlan($data) {
        if (!empty($data['id'])) {
            $stmt = $this->conn->prepare("
                UPDATE plans 
                SET name = :name, price = :price, duration_days = :duration_days, features = :features 
                WHERE id = :id
            ");
            return $stmt->execute([
                ':id'            => $data['id'],
                ':name'          => $data['name'],
                ':price'         => $data['price'],
                ':duration_days' => $data['duration_days'],
                ':features'      => $data['features']
            ]);
        } else {
            $stmt = $this->conn->prepare("
                INSERT INTO plans (name, price, duration_days, features) 
                VALUES (:name, :price, :duration_days, :features)
            ");
            return $stmt->execute([
                ':name'          => $data['name'],
                ':price'         => $data['price'],
                ':duration_days' => $data['duration_days'],
                ':features'      => $data['features']
            ]);
        }
    }

    public function deletePlan($id) {
        $stmt = $this->conn->prepare("DELETE FROM plans WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
