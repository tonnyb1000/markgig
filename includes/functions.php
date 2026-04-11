<?php
/**
 * MarkGigs Shared Functions
 */
require_once 'db.php';

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Require login
function require_login() {
    if (!is_logged_in()) {
        header("Location: " . BASE_URL . "/auth/login.php");
        exit;
    }
}

// Check role
function has_role($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// CSRF check
function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Redirect helpers
function redirect($path) {
    header("Location: " . BASE_URL . "/" . ltrim($path, '/'));
    exit;
}

// Flash messages
function set_flash($message, $type = 'info') {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Get user display name
function get_display_name($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT COALESCE(i.full_name, c.name) as display_name 
        FROM users u 
        LEFT JOIN individuals i ON i.user_id = u.id 
        LEFT JOIN companies c ON c.user_id = u.id 
        WHERE u.id = ?
    ");
    $stmt->execute([$user_id]);
    $res = $stmt->fetch();
    return $res ? $res['display_name'] : 'User';
}

// Placeholder for Send Mail (will implement properly with STMP instructions)
function send_email($to, $subject, $body) {
    // This will be expanded with PHPMailer later
    return mail($to, $subject, $body);
}
