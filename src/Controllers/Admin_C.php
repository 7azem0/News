<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Core/Helpers.php';

class AdminController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in AND is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            // Not authorized, redirect to Home
            redirect('?page=Home');
            exit;
        }

        // Load Admin Dashboard
        include __DIR__ . "/../Views/Admin/Dashboard.php";
    }
}
