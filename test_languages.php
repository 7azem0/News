<?php
require_once "src/Config/DataBase_Connection.php";
require_once "src/Services/Translation_S.php";
require_once "src/Models/User.php";

echo "Testing language filtering...\n\n";

$translator = new TranslationService();

// Test different plans
$plans = [null, 'Basic', 'Plus', 'Pro'];

foreach ($plans as $plan) {
    echo "Plan: " . ($plan ?? 'No subscription') . "\n";
    $langs = $translator->getAvailableLangsForPlan($plan);
    echo "Available languages: " . count($langs) . "\n";

    if (!empty($langs)) {
        foreach ($langs as $code => $name) {
            echo "  - $code: $name\n";
        }
    } else {
        echo "  - No languages available\n";
    }
    echo "\n";
}

echo "Test completed.\n";
?>
