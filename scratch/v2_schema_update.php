<?php
require_once __DIR__ . '/../includes/db.php';

echo "--- Updating Schema: Mentor Achievements ---\n";

try {
    $pdo->exec("
        ALTER TABLE individuals 
        ADD COLUMN achievements_field TEXT DEFAULT NULL,
        ADD COLUMN achievements_academic TEXT DEFAULT NULL,
        ADD COLUMN research_implementations TEXT DEFAULT NULL;
    ");
    echo "Successfully added achievement columns to individuals table.\n";
} catch (Exception $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
}
