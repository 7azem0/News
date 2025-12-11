<?php
require_once "Models/User.php";

class SubscriptionController {

    public function subscribe() {

        header('Content-Type: application/json'); // REQUIRED

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Not logged in']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $plan = $_POST['plan'] ?? '';
        $autoRenew = isset($_POST['autoRenew']) ? 1 : 0;

        // Validate plan
        $validPlans = ['Basic', 'Plus', 'Pro'];
        if (!in_array($plan, $validPlans)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid plan']);
            exit;
        }

        $db = new Database();
        $conn = $db->connect();

        try {
            $stmt = $conn->prepare(
                "INSERT INTO subscriptions (user_id, plan, auto_renew, expires_at)
                 VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 MONTH))
                 ON DUPLICATE KEY UPDATE
                 plan = VALUES(plan),
                 auto_renew = VALUES(auto_renew),
                 expires_at = DATE_ADD(NOW(), INTERVAL 1 MONTH)"
            );

            $stmt->execute([$_SESSION['user_id'], $plan, $autoRenew]);

           
            echo json_encode([
                'success' => true,
                'message' => 'Subscription updated successfully'
            ]);
            exit;

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
}
