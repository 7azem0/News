<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/User.php';
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

        $planId = $_POST['plan_id'] ?? 0;
        $autoRenew = isset($_POST['autoRenew']) ? 1 : 0;

        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        $planData = $subModel->getPlanById($planId);

        if (!$planData) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid plan']);
            exit;
        }

        $db = new Database();
        $conn = $db->connect();

        try {
            $stmt = $conn->prepare(
                "INSERT INTO subscriptions (user_id, plan, plan_id, auto_renew, expires_at)
                 VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))
                 ON DUPLICATE KEY UPDATE
                 plan = VALUES(plan),
                 plan_id = VALUES(plan_id),
                 auto_renew = VALUES(auto_renew),
                 expires_at = DATE_ADD(NOW(), INTERVAL ? DAY)"
            );

            $duration = (int)$planData['duration_days'];
            $stmt->execute([$_SESSION['user_id'], $planData['name'], $planId, $autoRenew, $duration, $duration]);

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

    public function cancel() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            redirect('?page=login');
            exit;
        }

        $db = new Database();
        $conn = $db->connect();

        // We can either delete the row OR set expires_at to NOW()
        // Setting to NOW() is better so we keep history or just let it expire immediately.
        // Or strictly delete. Let's delete for specific "Cancel" request as usually implies "Stop right now" or "Turn off auto-renew and let expire".
        // User asked "button to cancel", implying stopping it.
        // Let's turn off auto-renew and set expires_at to NOW() so it ends.
        
        $stmt = $conn->prepare("UPDATE subscriptions SET auto_renew = 0, expires_at = NOW() WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        redirect('?page=plans');
    }

    // --- Admin Plan Management ---

    private function ensureAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            redirect('?page=Home');
            exit;
        }
    }

    public function admin_plans() {
        $this->ensureAdmin();
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        $plans = $subModel->getAllPlans();
        include __DIR__ . '/../Views/Admin/Plans/index.php';
    }

    public function create_plan() {
        $this->ensureAdmin();
        include __DIR__ . '/../Views/Admin/Plans/form.php';
    }

    public function store_plan() {
        $this->ensureAdmin();
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        
        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'duration_days' => $_POST['duration_days'],
            'features' => $_POST['features']
        ];
        
        $subModel->savePlan($data);
        redirect('?page=admin_plans');
    }

    public function edit_plan() {
        $this->ensureAdmin();
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        $id = $_GET['id'] ?? 0;
        $plan = $subModel->getPlanById($id);
        include __DIR__ . '/../Views/Admin/Plans/form.php';
    }

    public function update_plan() {
        $this->ensureAdmin();
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        
        $data = [
            'id' => $_POST['id'],
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'duration_days' => $_POST['duration_days'],
            'features' => $_POST['features']
        ];
        
        $subModel->savePlan($data);
        redirect('?page=admin_plans');
    }

    public function destroy_plan() {
        $this->ensureAdmin();
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        $id = $_POST['id'];
        $subModel->deletePlan($id);
        redirect('?page=admin_plans');
    }

    // --- Manual Assignment ---

    public function assign() {
        $this->ensureAdmin();
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Subscription.php';
        
        $userModel = new User();
        $subModel = new Subscription();
        
        $users = $userModel->getAllUsers(); // Should probably be a search in real app, but list is fine for now
        $plans = $subModel->getAllPlans();
        
        include __DIR__ . '/../Views/Admin/Subscriptions/assign.php';
    }

    public function store_assignment() {
        $this->ensureAdmin();
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        
        $userId = $_POST['user_id'];
        $planId = $_POST['plan_id'];
        $duration = (int)$_POST['duration_days'];
        
        // Simple manual insert/update
        $db = new Database();
        $conn = $db->connect();
        
        // Get Plan Name for legacy column + Plan ID
        $plan = $subModel->getPlanById($planId);
        $planName = $plan['name']; // Fallback
        
        // Custom expiry
        $sql = "INSERT INTO subscriptions (user_id, plan, plan_id, auto_renew, expires_at)
                VALUES (?, ?, ?, 0, DATE_ADD(NOW(), INTERVAL ? DAY))
                ON DUPLICATE KEY UPDATE
                plan = VALUES(plan),
                plan_id = VALUES(plan_id),
                auto_renew = 0,
                expires_at = DATE_ADD(NOW(), INTERVAL ? DAY)";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId, $planName, $planId, $duration, $duration]);
        
        redirect('?page=admin_users'); // Redirect to users list
    }
}
