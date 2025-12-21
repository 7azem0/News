<?php
require_once __DIR__ . "/../config/DataBase_Connection.php";

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->connect();
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

    public function existsByUsername(string $username): bool {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function existsByEmail(string $email): bool {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /* --- Login Rate Limiting --- */

    public function recordLoginAttempt(string $email, string $ip) {
        // Create table if not exists
        $this->conn->exec("CREATE TABLE IF NOT EXISTS login_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            ip VARCHAR(45) NOT NULL,
            attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $stmt = $this->conn->prepare("INSERT INTO login_attempts (email, ip) VALUES (?, ?)");
        $stmt->execute([$email, $ip]);
    }

    public function getRecentAttempts(string $email, string $ip, int $minutes = 15): int {
        try {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) FROM login_attempts 
                WHERE (email = ? OR ip = ?) 
                AND attempted_at > NOW() - INTERVAL ? MINUTE
            ");
            $stmt->execute([$email, $ip, $minutes]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0; // Table might not exist yet
        }
    }

    public function clearLoginAttempts(string $email, string $ip) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM login_attempts WHERE email = ? OR ip = ?");
            $stmt->execute([$email, $ip]);
        } catch (PDOException $e) {
            // Ignore
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
            // Check if user is suspended
            if (isset($user['status']) && $user['status'] === 'suspended') {
                return 'suspended';
            }

            if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin']; // Store admin status
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
    // 1. دالة لجلب بيانات المستخدم بالكامل عن طريق الـ ID
    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Change UserName & Gmail
    public function updateProfile($id, $username, $email) {
        $query = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Change Password
    public function updatePassword($id, $hashedPassword) {
        $query = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // --- Admin Methods ---

    public function getAllUsers() {
        $stmt = $this->conn->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function promoteToAdmin($id) {
        $stmt = $this->conn->prepare("UPDATE users SET is_admin = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
