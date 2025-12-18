<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/Article.php';
require_once __DIR__ . '/../Services/NewsAPI_S.php';
require_once __DIR__ . '/../Services/Translation_S.php';
require_once __DIR__ . '/../Models/User.php';

define('VIEWS_PATH', __DIR__ . '/../Views/Articles/');

class ArticleController {

    private Article $articleModel;
    private TranslationService $translator;
    private User $userModel;
    private NewsAPIService $newsService;

    public function __construct() {
        $this->articleModel = new Article();
        $this->translator   = new TranslationService();
        $this->userModel    = new User();
        $this->newsService  = new NewsAPIService();
    }

    /**
     * News page (external API with DB fallback)
     */
    public function news(string $country = 'us', string $category = 'technology') {
        $articles = $this->newsService->fetch($country, $category);

        // If API fails or returns nothing, fallback to DB
        if (empty($articles)) {
            $articles = $this->articleModel->fetchNews($country, $category);
        }

        include VIEWS_PATH . 'news.php';
    }

    /**
     * Articles list OR single article with translation
     */
    public function index() {
        // Articles listing
        if (!isset($_GET['id'])) {
            $articles = $this->articleModel->getAll();
            $news = $this->newsService->fetch('us', 'technology');

            $selectedLang = $_GET['lang'] ?? 'en';

            // Get user subscription if logged in
            $subscription = null;
            if (isset($_SESSION['user_id'])) {
                $subscription = $this->userModel->getSubscription($_SESSION['user_id']);
            }

            $plan = $subscription['plan'] ?? null;

            // Get available languages for plan
            $availableLangs = $this->translator->getAvailableLangsForPlan($plan);

            // Always include English
            if (!isset($availableLangs['en'])) {
                $availableLangs = ['en' => 'English'] + $availableLangs;
            }

            // Translation logic
            if ($selectedLang !== 'en') {
                try {
                    // Translate articles
                    foreach ($articles as &$article) {
                        $translated = $this->translator->translateArticle($article['id'], $selectedLang, $plan);
                        $article['title'] = $translated['title'] ?? $article['title'];
                        $article['content'] = $translated['content'] ?? $article['content'];
                    }

                    // Translate news
                    foreach ($news as &$item) {
                        $item['title'] = $this->translator->translateText($item['title'], $selectedLang);
                        $item['description'] = $this->translator->translateText($item['description'], $selectedLang);
                    }
                } catch (Exception $e) {
                    // fallback to original
                }
            }

            include VIEWS_PATH . 'list.php';
            return;
        }

        // Single article
        $id = (int) $_GET['id'];
        $article = $this->articleModel->getById($id);

        if (!$article) {
            http_response_code(404);
            echo "Article not found";
            return;
        }

        // Check visibility and access
        $canAccess = true;
        $accessMessage = '';
        $sub = null; 
        $subscription = null; 

        if (($article['visibility'] ?? 'public') === 'subscribed') {
            if (!isset($_SESSION['user_id'])) {
                $canAccess = false;
                $accessMessage = "This article is for subscribers only. Please log in or subscribe.";
            } else {
                // Check if user has active subscription
                $sub = $this->userModel->getSubscription($_SESSION['user_id']);
                
                if (!$sub || (isset($sub['expires_at']) && strtotime($sub['expires_at']) <= time())) {
                    $canAccess = false;
                    $accessMessage = "Your subscription has expired. Please renew to access this article.";
                } else {
                    // Check granular plan requirement (Price-based)
                    if (!empty($article['required_plan_id'])) {
                        require_once __DIR__ . '/../Models/Subscription.php';
                        $subModel = new Subscription();
                        
                        $requiredPlan = $subModel->getPlanById($article['required_plan_id']);
                        $userPlanId = $sub['plan_id'] ?? 0;
                        $userPlan = $subModel->getPlanById($userPlanId);
                        
                        // If user plan price < required plan price, deny access
                        if ($userPlan && $requiredPlan) {
                            if ((float)$userPlan['price'] < (float)$requiredPlan['price']) {
                                $canAccess = false;
                                $accessMessage = "This article requires the {$requiredPlan['name']} plan (or higher). Please upgrade your subscription.";
                            }
                        } else {
                            // If plans not found, strictly deny to be safe
                            $canAccess = false;
                            $accessMessage = "Error verifying subscription plan.";
                        }
                    }
                }
            }
        }

        if (!$canAccess) {
             // Access Denied
             $displayArticle = $article;
             $displayArticle['is_blocked'] = true;
             $displayArticle['content'] = '<div class="alert alert-warning" style="margin: 20px 0; padding: 15px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 4px; color: #856404;">' . $accessMessage . ' <a href="?page=plans" style="font-weight: bold;">View Plans</a></div>';
        } else {
             // Access Granted
             $displayArticle = $article;
             $displayArticle['is_blocked'] = false;

             // Handle Translation Logic
             $subscription = $sub ?: ($this->userModel->getSubscription($_SESSION['user_id'] ?? 0));
             $plan = $subscription['plan'] ?? null;

             $availableLangs = $this->translator->getAvailableLangsForPlan($plan);
             
             // Map article's language to code
             $languageCodeMap = [
                 'English' => 'en', 'Arabic' => 'ar', 'French' => 'fr', 'Spanish' => 'es',
                 'German' => 'de', 'Italian' => 'it', 'Portuguese' => 'pt', 'Russian' => 'ru',
                 'Chinese' => 'zh', 'Dutch' => 'nl', 'Norwegian' => 'no', 'Swedish' => 'sv',
                 'Japanese' => 'ja', 'Korean' => 'ko',
                 'Hindi' => 'hi', 'Turkish' => 'tr', 'Persian' => 'fa'
             ];
             $articleLangCode = $languageCodeMap[$article['language'] ?? 'English'] ?? 'en';

             // Always include English and the article's original language in dropdown
             if (!isset($availableLangs['en'])) {
                 $availableLangs = ['en' => 'English'] + $availableLangs;
             }
             if (!isset($availableLangs[$articleLangCode])) {
                 $availableLangs[$articleLangCode] = $article['language'] ?? 'Original';
             }
             
             // Default to article's language, only change if user explicitly selects different language
             $selectedLang = $_GET['lang'] ?? $articleLangCode;

             // Only translate if selected language is different from article's original language
             if ($selectedLang !== $articleLangCode) {
                try {
                    $translated = $this->translator->translateArticle($id, $selectedLang, $plan);
                    $displayArticle['title']   = $translated['title'] ?? $article['title'];
                    $displayArticle['content'] = $translated['content'] ?? $article['content'];
                } catch (Exception $e) {
                     // Log translation error for debugging
                     error_log("Translation failed for article $id: " . $e->getMessage());
                     // fallback handled by $displayArticle = $article
                }
             }
        }
        
        // Populate variables for View
        if (!isset($availableLangs)) $availableLangs = ['en' => 'English']; 
        
        // Map article's language to code for dropdown display
        $languageCodeMap = [
            'English' => 'en', 'Arabic' => 'ar', 'French' => 'fr', 'Spanish' => 'es',
            'German' => 'de', 'Italian' => 'it', 'Portuguese' => 'pt', 'Russian' => 'ru',
            'Chinese' => 'zh', 'Dutch' => 'nl', 'Norwegian' => 'no', 'Swedish' => 'sv',
            'Hebrew' => 'he', 'Urdu' => 'ur', 'Japanese' => 'ja', 'Korean' => 'ko'
        ];
        $articleLangCode = $languageCodeMap[$article['language'] ?? 'English'] ?? 'en';
        
        // Only set selectedLang from URL parameter - don't auto-translate on first visit
        // If no lang parameter, selectedLang will match article's language (no translation)
        if (!isset($selectedLang)) {
            $selectedLang = isset($_GET['lang']) ? $_GET['lang'] : $articleLangCode;
        }

        // Fetch approved comments for this article
        require_once __DIR__ . '/../Models/Comment.php';
        $commentModel = new Comment();
        $comments = $commentModel->getByArticleId($id);

        // Check if user has liked/saved this article
        $isLiked = false;
        $isSaved = false;
        $likeCount = 0;
        
        if (isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/../Models/ArticleInteraction.php';
            $interactionModel = new ArticleInteraction();
            $isLiked = $interactionModel->isLiked($_SESSION['user_id'], $id);
            $isSaved = $interactionModel->isSaved($_SESSION['user_id'], $id);
            $likeCount = $interactionModel->getLikeCount($id);
        } else {
            require_once __DIR__ . '/../Models/ArticleInteraction.php';
            $interactionModel = new ArticleInteraction();
            $likeCount = $interactionModel->getLikeCount($id);
        }

        // Check if user can download PDF (Pro plan or higher)
        $canDownloadPdf = false;
        if (isset($_SESSION['user_id']) && $subscription) {
            require_once __DIR__ . '/../Models/Subscription.php';
            $subModel = new Subscription();
            $userPlan = $subModel->getPlanById($subscription['plan_id']);
            if ($userPlan && (float)$userPlan['price'] >= 29.99) {
                $canDownloadPdf = true;
            }
        }

        // Fetch adjacent articles for navigation
        $userMaxPrice = null;
        if (isset($_SESSION['user_id'])) {
             // Check for active subscription
             $activeSub = $this->userModel->getSubscription($_SESSION['user_id']);
             if ($activeSub && (!isset($activeSub['expires_at']) || strtotime($activeSub['expires_at']) > time())) {
                 require_once __DIR__ . '/../Models/Subscription.php';
                 $subModel = new Subscription();
                 $userP = $subModel->getPlanById($activeSub['plan_id'] ?? 0);
                 if ($userP) $userMaxPrice = (float)$userP['price'];
             }
        }
        $adjacents = $this->articleModel->getAdjacentIds($id, $userMaxPrice);
        $prevArticleId = $adjacents['prev'];
        $nextArticleId = $adjacents['next'];

        include VIEWS_PATH . 'Single.php';
    }

    public function downloadPdf() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $articleId = $_GET['id'] ?? 0;
        $article = $this->articleModel->getById($articleId);

        if (!$article) {
            http_response_code(404);
            echo "Article not found";
            return;
        }

        // Check if user has Pro plan or higher (price >= 29.99)
        $subscription = $this->userModel->getSubscription($_SESSION['user_id']);
        
        if (!$subscription || (isset($subscription['expires_at']) && strtotime($subscription['expires_at']) <= time())) {
            // No active subscription
            header('Location: index.php?page=article&id=' . $articleId . '&error=no_subscription');
            exit;
        }

        // Get user's plan price
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        $userPlan = $subModel->getPlanById($subscription['plan_id']);
        
        // Get Pro plan price (29.99)
        $proPrice = 29.99;
        
        if (!$userPlan || (float)$userPlan['price'] < $proPrice) {
            // User doesn't have Pro plan or higher
            header('Location: index.php?page=article&id=' . $articleId . '&error=upgrade_required');
            exit;
        }

        // User has access, generate PDF
        require_once __DIR__ . '/../Services/PdfGenerator.php';
        $pdfHtml = PdfGenerator::generateArticlePdf($article);
        
        // Output HTML for printing
        echo $pdfHtml;
    }

    // --- Admin Methods ---

    private function ensureAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            redirect('?page=Home');
            exit;
        }
    }

    public function admin_index() {
        $this->ensureAdmin();
        $articles = $this->articleModel->getAllAdmin();
        include __DIR__ . '/../Views/Admin/Articles/index.php';
    }

    public function create() {
        $this->ensureAdmin();
        $categories = $this->articleModel->getAllCategories();
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        $plans = $subModel->getAllPlans();
        
        include __DIR__ . '/../Views/Admin/Articles/form.php';
    }

    public function store() {
        $this->ensureAdmin();
        $data = $_POST;
        
        // Handle File Upload
        if (empty($data['scheduled_at'])) {
            $data['scheduled_at'] = null;
        }

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../Assets/Uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $filename = time() . '_' . basename($_FILES['thumbnail']['name']);
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetPath)) {
                $data['imageUrl'] = 'Assets/Uploads/' . $filename;
            }
        }
        
        if ($this->articleModel->save($data)) {
            redirect('?page=admin_articles');
        } else {
            $error = "Failed to save article.";
            $categories = $this->articleModel->getAllCategories();
            require_once __DIR__ . '/../Models/Subscription.php';
            $subModel = new Subscription();
            $plans = $subModel->getAllPlans();
            
            include __DIR__ . '/../Views/Admin/Articles/form.php';
        }
    }

    public function edit() {
        $this->ensureAdmin();
        $id = $_GET['id'] ?? 0;
        $article = $this->articleModel->getById($id);
        
        if (!$article) {
            redirect('?page=admin_articles');
            exit;
        }

        $categories = $this->articleModel->getAllCategories();
        require_once __DIR__ . '/../Models/Subscription.php';
        $subModel = new Subscription();
        $plans = $subModel->getAllPlans();

        include __DIR__ . '/../Views/Admin/Articles/form.php';
    }

    public function update() {
        $this->ensureAdmin();
        $data = $_POST;
        
        
        // Handle File Upload
        if (empty($data['scheduled_at'])) {
            $data['scheduled_at'] = null;
        }

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../Assets/Uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $filename = time() . '_' . basename($_FILES['thumbnail']['name']);
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetPath)) {
                $data['imageUrl'] = 'Assets/Uploads/' . $filename;
            }
        } else {
            // Keep existing image if no new one
            $data['imageUrl'] = $_POST['existing_image'] ?? '';
        }

        if ($this->articleModel->save($data)) {
            redirect('?page=admin_articles');
        } else {
            $error = "Failed to update article.";
            $article = $data; 
            $categories = $this->articleModel->getAllCategories();
            require_once __DIR__ . '/../Models/Subscription.php';
            $subModel = new Subscription();
            $plans = $subModel->getAllPlans();
            include __DIR__ . '/../Views/Admin/Articles/form.php';
        }
    }

    public function destroy() {
        $this->ensureAdmin();
        $id = $_POST['id'] ?? 0;
        $this->articleModel->delete($id);
        redirect('?page=admin_articles');
    }
}
