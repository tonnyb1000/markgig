<?php
require_once 'includes/db.php';
$posts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
echo "Posts: $posts\n";
echo "Users: $users\n";
$first_post = $pdo->query("SELECT * FROM posts LIMIT 1")->fetch();
print_r($first_post);
