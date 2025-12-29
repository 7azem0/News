<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/DataBase_Connection.php';
require_once __DIR__ . '/../Models/Subscription.php';

class PayPalController {

    private function paypalErrorMessage(int $status, $data, string $prefix): string {
        $msg = $prefix . ' (HTTP ' . $status . ')';
        if (is_array($data)) {
            if (!empty($data['message'])) {
                $msg .= ': ' . $data['message'];
            } elseif (!empty($data['error'])) {
                $msg .= ': ' . $data['error'];
                if (!empty($data['error_description'])) {
                    $msg .= ' - ' . $data['error_description'];
                }
            }
            if (!empty($data['details']) && is_array($data['details'])) {
                $first = $data['details'][0] ?? null;
                if (is_array($first) && !empty($first['issue'])) {
                    $msg .= ' (' . $first['issue'] . ')';
                }
            }
            if (!empty($data['debug_id'])) {
                $msg .= ' [debug_id ' . $data['debug_id'] . ']';
            }
        }
        return $msg;
    }

    private function paypalFirstIssue($data): ?string {
        if (!is_array($data)) return null;
        if (empty($data['details']) || !is_array($data['details'])) return null;
        $first = $data['details'][0] ?? null;
        if (!is_array($first)) return null;
        return $first['issue'] ?? null;
    }

    private function ensureLoggedInJson(): void {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            exit;
        }
    }

    private function getPayPalBaseUrl(): string {
        $mode = strtolower(getenv('PAYPAL_MODE') ?: 'sandbox');
        return $mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
    }

    private function getPayPalAccessToken(): string {
        $clientId = getenv('PAYPAL_CLIENT_ID') ?: '';
        $secret = getenv('PAYPAL_SECRET') ?: '';

        if ($clientId === '' || $secret === '') {
            throw new RuntimeException('PayPal credentials are not configured');
        }

        $ch = curl_init($this->getPayPalBaseUrl() . '/v1/oauth2/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_USERPWD => $clientId . ':' . $secret,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Accept-Language: en_US',
                'Content-Type: application/x-www-form-urlencoded',
            ],
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new RuntimeException('PayPal token request failed: ' . $err);
        }

        $data = json_decode($response, true);
        if ($status < 200 || $status >= 300 || empty($data['access_token'])) {
            throw new RuntimeException($this->paypalErrorMessage($status, $data, 'PayPal token request failed'));
        }

        return $data['access_token'];
    }

    private function ensurePaymentsTable(): void {
        $conn = Database::getInstance()->connect();
        $conn->exec("
            CREATE TABLE IF NOT EXISTS paypal_payments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                plan_id INT NOT NULL,
                order_id VARCHAR(64) NOT NULL,
                capture_id VARCHAR(64) DEFAULT NULL,
                amount DECIMAL(10,2) NOT NULL,
                currency VARCHAR(8) NOT NULL,
                auto_renew TINYINT(1) NOT NULL DEFAULT 1,
                status VARCHAR(32) NOT NULL DEFAULT 'CREATED',
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_order_id (order_id),
                KEY idx_user_id (user_id),
                KEY idx_plan_id (plan_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function create_order(): void {
        $this->ensureLoggedInJson();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            exit;
        }

        $planId = (int)($_POST['plan_id'] ?? 0);
        $autoRenew = isset($_POST['autoRenew']) ? 1 : 0;

        $subModel = new Subscription();
        $planData = $subModel->getPlanById($planId);
        if (!$planData) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid plan']);
            exit;
        }

        $amountValue = getenv('PAYPAL_AMOUNT') ?: '0.10';
        $currency = getenv('PAYPAL_CURRENCY') ?: 'USD';

        try {
            $token = $this->getPayPalAccessToken();

            $payload = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => 'plan_' . $planId,
                    'custom_id' => (string)$_SESSION['user_id'],
                    'description' => 'Subscription plan: ' . ($planData['name'] ?? 'Plan'),
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => $amountValue,
                    ],
                ]],
                // For JS SDK flow (PayPal Buttons), avoid return/cancel URLs which can fail on localhost.
                'application_context' => [
                    'brand_name' => 'Digital Newsstand',
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW',
                ],
            ];

            $ch = curl_init($this->getPayPalBaseUrl() . '/v2/checkout/orders');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ],
                CURLOPT_POSTFIELDS => json_encode($payload),
            ]);

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                throw new RuntimeException('PayPal order request failed: ' . $err);
            }

            $data = json_decode($response, true);
            if ($status < 200 || $status >= 300 || empty($data['id'])) {
                throw new RuntimeException($this->paypalErrorMessage($status, $data, 'PayPal order creation failed'));
            }

            $this->ensurePaymentsTable();
            $conn = Database::getInstance()->connect();
            $stmt = $conn->prepare("
                INSERT INTO paypal_payments (user_id, plan_id, order_id, amount, currency, auto_renew, status)
                VALUES (?, ?, ?, ?, ?, ?, 'CREATED')
            ");
            $stmt->execute([$_SESSION['user_id'], $planId, $data['id'], $amountValue, $currency, $autoRenew]);

            echo json_encode(['success' => true, 'id' => $data['id']]);
            exit;
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    public function capture_order(): void {
        $this->ensureLoggedInJson();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            exit;
        }

        $orderId = trim((string)($_POST['order_id'] ?? ''));
        if ($orderId === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Missing order id']);
            exit;
        }

        try {
            $this->ensurePaymentsTable();
            $conn = Database::getInstance()->connect();
            $stmt = $conn->prepare("SELECT * FROM paypal_payments WHERE order_id = ? LIMIT 1");
            $stmt->execute([$orderId]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$payment || (int)$payment['user_id'] !== (int)$_SESSION['user_id']) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Order not found']);
                exit;
            }

            $token = $this->getPayPalAccessToken();

            $ch = curl_init($this->getPayPalBaseUrl() . '/v2/checkout/orders/' . rawurlencode($orderId) . '/capture');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ],
            ]);

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                throw new RuntimeException('PayPal capture request failed: ' . $err);
            }

            $data = json_decode($response, true);
            if ($status < 200 || $status >= 300) {
                $issue = $this->paypalFirstIssue($data);
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => $this->paypalErrorMessage($status, $data, 'PayPal capture failed'),
                    'issue' => $issue,
                ]);
                exit;
            }

            $capture = $data['purchase_units'][0]['payments']['captures'][0] ?? null;
            $captureId = $capture['id'] ?? null;
            $captureStatus = $capture['status'] ?? null;
            $capturedAmount = $capture['amount']['value'] ?? null;
            $capturedCurrency = $capture['amount']['currency_code'] ?? null;

            if ($captureStatus !== 'COMPLETED') {
                $upd = $conn->prepare("UPDATE paypal_payments SET status = ? WHERE order_id = ?");
                $upd->execute([$captureStatus ?: 'UNKNOWN', $orderId]);
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Payment not completed']);
                exit;
            }

            if ((string)$capturedCurrency !== (string)$payment['currency'] || (string)$capturedAmount !== (string)$payment['amount']) {
                $upd = $conn->prepare("UPDATE paypal_payments SET status = 'AMOUNT_MISMATCH' WHERE order_id = ?");
                $upd->execute([$orderId]);
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Amount verification failed']);
                exit;
            }

            $upd = $conn->prepare("UPDATE paypal_payments SET capture_id = ?, status = 'COMPLETED' WHERE order_id = ?");
            $upd->execute([$captureId, $orderId]);

            $subModel = new Subscription();
            $planData = $subModel->getPlanById((int)$payment['plan_id']);
            if (!$planData) {
                throw new RuntimeException('Plan not found');
            }

            $duration = (int)($planData['duration_days'] ?? 30);
            $autoRenew = (int)($payment['auto_renew'] ?? 1);

            $insertSub = $conn->prepare("
                INSERT INTO subscriptions (user_id, plan, plan_id, auto_renew, expires_at)
                VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))
            ");
            $insertSub->execute([$_SESSION['user_id'], $planData['name'], (int)$payment['plan_id'], $autoRenew, $duration]);

            echo json_encode(['success' => true]);
            exit;
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}
