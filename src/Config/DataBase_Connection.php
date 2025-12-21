<?php
if (!class_exists('Database')) {
class Database {
    private static $instance = null;
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn = null;

    private function __construct() {
        $this->host = getenv('DB_HOST') ?: 'db';
        $this->db_name = getenv('DB_NAME') ?: 'News';
        $this->username = getenv('DB_USER') ?: 'Rain';
        $this->password = getenv('DB_PASS') ?: 'Sunny';
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect() {
        if ($this->conn !== null) {
            return $this->conn;
        }

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
}
