<?php
require_once "config/DataBase_Connection.php";

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Create a new user
     * Returns:
     *   true  -> success
     *   false -> duplicate username/email or other validation error
     */
    public function create($username, $email, $password) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
            );
            return $stmt->execute([$username, $email, $password]);
        
        } catch (PDOException $e) {

            // MySQL duplicate entry → error code 1062
            if ($e->errorInfo[1] == 1062) {
                return false; 
            }

            // Any other DB error → show it (helps debugging)
            throw $e;
        }
    }


    /**
     * Login user
     */
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;

        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }

        return false;
    }

    /**
     * Get user subscription
     */
    public function getSubscription($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND (expires_at IS NULL OR expires_at > NOW()) ORDER BY id DESC LIMIT 1");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Return null if table doesn't exist or other DB error
            return null;
        }
    }
}
