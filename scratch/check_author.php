<?php
require_once 'includes/db.php';
$u = $pdo->query("SELECT * FROM users WHERE id=2")->fetch();
$i = $pdo->query("SELECT * FROM individuals WHERE user_id=2")->fetch();
$c = $pdo->query("SELECT * FROM companies WHERE user_id=2")->fetch();
print_r($u);
print_r($i);
print_r($c);
