<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/Game.php';

define('GAMES_VIEWS_PATH', __DIR__ . '/../Views/Games/');

class GamesController {

    private Game $gameModel;

    public function __construct() {
        $this->gameModel = new Game();
    }

    /**
     * Games Hub Page
     */
    public function index() {
        $pageTitle = "Games Arcade";
        include GAMES_VIEWS_PATH . 'Index.php';
    }

    /**
     * Play specific game
     */
    public function play($gameName) {
        // Whitelist allowed games to prevent arbitrary file inclusion
        $allowedGames = ['wordle', 'connections', 'spellingbee'];
        
        if (in_array($gameName, $allowedGames)) {
            $pageTitle = ucfirst($gameName);
            // Pass game name to view for JS loading
            $currentGame = $gameName;
            include GAMES_VIEWS_PATH . ucfirst($gameName) . '.php';
        } else {
            // Game not found
            header("Location: index.php?page=games");
            exit;
        }
    }

    /**
     * API: Save progress
     */
    public function saveProgress() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['gameId']) || !isset($input['state'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $gameId = $input['gameId'];
        $state  = json_encode($input['state']); // Store as JSON string in DB
        $score  = $input['score'] ?? 0;

        $result = $this->gameModel->saveState($userId, $gameId, $state, $score);

        echo json_encode(['success' => $result]);
    }

    /**
     * API: Reset progress
     */
    public function resetProgress() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['gameId'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $gameId = $input['gameId'];
        
        // Reset state to empty but keep record
        // Or simply delete the row? Let's just reset state to null/empty json
        $emptyState = json_encode(null); 
        $result = $this->gameModel->saveState($userId, $gameId, $emptyState, 0);

        echo json_encode(['success' => $result]);
    }

    /**
     * API: Get progress
     */
    public function getProgress() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if (!isset($_GET['gameId'])) {
            echo json_encode(['success' => false, 'message' => 'Missing gameId']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $gameId = $_GET['gameId'];

        $data = $this->gameModel->getState($userId, $gameId);

        if ($data) {
            // Decode state back to JSON object for frontend
            $data['state'] = json_decode($data['state']);
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => true, 'data' => null]);
        }
    }
}
