<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/Comment.php';

class CommentController {

    private function ensureAdmin() {
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            header('Location: index.php?page=Home');
            exit;
        }
    }

    public function admin_index() {
        $this->ensureAdmin();
        $commentModel = new Comment();
        $comments = $commentModel->getAllComments();
        include __DIR__ . '/../Views/Admin/Comments/index.php';
    }

    public function approve() {
        $this->ensureAdmin();
        $id = $_POST['id'];
        $commentModel = new Comment();
        $commentModel->updateStatus($id, 'approved');
        header('Location: index.php?page=admin_comments');
    }

    public function reject() {
        $this->ensureAdmin();
        $id = $_POST['id'];
        $commentModel = new Comment();
        $commentModel->updateStatus($id, 'flagged'); // Or delete? Let's flag for now or just 'pending' -> 'flagged' is a form of rejection without deletion
        header('Location: index.php?page=admin_comments');
    }

    public function destroy() {
        $this->ensureAdmin();
        $id = $_POST['id'];
        $commentModel = new Comment();
        $commentModel->delete($id);
        header('Location: index.php?page=admin_comments');
    }

    // User-facing method
    public function store() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        // Validate input
        $articleId = $_POST['article_id'] ?? 0;
        $content = trim($_POST['content'] ?? '');

        if (empty($content) || empty($articleId)) {
            // Redirect back with error
            header('Location: index.php?page=article&id=' . $articleId . '&error=empty');
            exit;
        }

        // Add comment (status defaults to 'pending' in DB)
        $commentModel = new Comment();
        $commentModel->add($_SESSION['user_id'], $articleId, $content);

        // Redirect back to article with success message
        header('Location: index.php?page=article&id=' . $articleId . '&comment=success');
        exit;
    }
}
