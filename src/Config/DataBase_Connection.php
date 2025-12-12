<?php
class Database {
    private $host = "db";         
    private $db_name = "News";    
    private $username = "Rain";
    private $password = "Sunny";
    public $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: 'db';
        $this->db_name = getenv('DB_NAME') ?: 'News';
        $this->username = getenv('DB_USER') ?: 'Rain';
        $this->password = getenv('DB_PASS') ?: 'Sunny';
    }

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
        return $this->conn;
    }
}
