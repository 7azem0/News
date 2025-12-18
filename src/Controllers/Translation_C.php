<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/DataBase_Connection.php";

require_once __DIR__ . '/../Models/Article.php';
require_once __DIR__ . '/../Services/Translation_S.php';
require_once __DIR__ . '/../Models/User.php';


$db = new Database();
$PDO = $db->connect();
$translator = new TranslationService();

// Get user subscription to filter available languages
$userModel = new User();
$subscription = null;
if (isset($_SESSION['user_id'])) {
    $subscription = $userModel->getSubscription($_SESSION['user_id']);
}
$plan = $subscription ? $subscription['plan'] : null;
$availableLangs = $translator->getAvailableLangsForPlan($plan);

$articleId = (int)($_GET['id'] ?? 1);
$selectedLang = $_GET['lang'] ?? 'en';

// Fetch the original article
$stmt = $PDO->prepare("SELECT * FROM articles WHERE id=?");
$stmt->execute([$articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$article) die("Article not found");

$languageCodeMap = [
    'English' => 'en', 'Arabic' => 'ar', 'French' => 'fr', 'Spanish' => 'es',
    'German' => 'de', 'Italian' => 'it', 'Portuguese' => 'pt', 'Russian' => 'ru',
    'Chinese' => 'zh', 'Dutch' => 'nl', 'Swedish' => 'sv',
    'Japanese' => 'ja', 'Korean' => 'ko',
    'Hindi' => 'hi', 'Turkish' => 'tr', 'Persian' => 'fa'
];
$articleLangCode = $languageCodeMap[$article['language'] ?? 'English'] ?? 'en';

if (!isset($availableLangs[$articleLangCode])) {
    $availableLangs[$articleLangCode] = $article['language'] ?? 'Original';
}

$translatedArticle = $article;
if($selectedLang !== $articleLangCode) {
    try {
        $translatedArticle = $translator->translateArticle($articleId, $selectedLang, $plan);
    } catch(Exception $e) {
        // If there's an error in translation, show the original article
        $translatedArticle = ['title'=>$article['title'], 'content'=>$article['content']];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($translatedArticle['title']) ?></title>
</head>
<body>

    <form method="GET" action="">
        <input type="hidden" name="id" value="<?= $articleId ?>">
        <label for="lang">Language: </label>
        <select name="lang" id="lang" onchange="this.form.submit()">
            <?php foreach ($availableLangs as $code => $name): ?>
                <option value="<?= $code ?>" <?= $selectedLang==$code ? 'selected' : '' ?>><?= $name ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <hr>

    <h1><?= htmlspecialchars($translatedArticle['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($translatedArticle['content'])) ?></p>

</body>
</html>