<?php
require_once __DIR__ . "/../config/DataBase_Connection.php";

class Game {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    /**
     * Save or update game state
     */
    public function saveState($userId, $gameId, $state, $score) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO user_game_progress (user_id, game_id, state, score, updated_at) 
                VALUES (:user_id, :game_id, :state, :score, NOW())
                ON DUPLICATE KEY UPDATE 
                    state = VALUES(state), 
                    score = VALUES(score), 
                    updated_at = NOW()
            ");

            return $stmt->execute([
                ':user_id' => $userId,
                ':game_id' => $gameId,
                ':state'   => $state,
                ':score'   => $score
            ]);
        } catch (PDOException $e) {
            error_log("Game Save Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get saved game state
     */
    public function getState($userId, $gameId) {
        try {
            $stmt = $this->conn->prepare("SELECT state, score FROM user_game_progress WHERE user_id = ? AND game_id = ?");
            $stmt->execute([$userId, $gameId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
