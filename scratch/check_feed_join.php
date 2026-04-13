<?php
require_once 'includes/db.php';
$posts = $pdo->query("
    SELECT p.id, p.author_id, u.id as user_id, COALESCE(i.full_name, c.name) as author_name
    FROM posts p
    JOIN users u ON p.author_id = u.id
    LEFT JOIN individuals i ON i.user_id = u.id
    LEFT JOIN companies c ON c.user_id = u.id
")->fetchAll();
echo "Valid Join Posts: " . count($posts) . "\n";
print_r($posts);
