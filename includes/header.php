<?php
/**
 * MarkGigs Main Header
 */
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

    <!-- Flash Messages -->
    <div class="flash-container">
        <?php $flash = get_flash(); if ($flash): ?>
            <div class="flash flash-<?= $flash['type'] ?>">
                <i class="fa-solid <?= $flash['type'] === 'success' ? 'fa-circle-check' : ($flash['type'] === 'danger' ? 'fa-circle-xmark' : 'fa-circle-info') ?>"></i>
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Navbar -->
    <nav class="navbar" id="mainNav">
        <div class="nav-inner">
            <a href="<?= BASE_URL ?>/index.php" class="nav-logo">
                <div class="logo-icon">M</div>
                <span class="grad-text">MarkGigs</span>
            </a>

            <?php if (is_logged_in()): ?>
                <div class="nav-search">
                    <form action="<?= BASE_URL ?>/search.php" method="GET">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" name="q" placeholder="Search people, companies, gigs...">
                    </form>
                </div>

                <div class="nav-links">
                    <a href="<?= BASE_URL ?>/index.php" class="nav-link <?= $active_page === 'feed' ? 'active' : '' ?>">
                        <i class="fa-solid fa-house"></i>
                        <span>Feed</span>
                    </a>
                    <a href="<?= BASE_URL ?>/network.php" class="nav-link <?= $active_page === 'network' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-group"></i>
                        <span>Network</span>
                    </a>
                    <a href="<?= BASE_URL ?>/jobs.php" class="nav-link <?= $active_page === 'jobs' ? 'active' : '' ?>">
                        <i class="fa-solid fa-briefcase"></i>
                        <span>Jobs</span>
                    </a>
                    <a href="<?= BASE_URL ?>/mentors.php" class="nav-link <?= $active_page === 'mentorship' ? 'active' : '' ?>">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>Mentors</span>
                    </a>
                    <a href="<?= BASE_URL ?>/inbox.php" class="nav-link <?= $active_page === 'messages' ? 'active' : '' ?>">
                        <i class="fa-solid fa-message"></i>
                        <span>Messages</span>
                    </a>
                    
                    <div class="nav-avatar-wrap" id="avatarDropdownToggle">
                        <img src="<?= BASE_URL ?>/uploads/avatars/default.svg" alt="Avatar" class="nav-avatar">
                        <div class="dropdown-menu" id="avatarDropdown">
                            <a href="<?= BASE_URL ?>/profile.php">My Profile</a>
                            <a href="<?= BASE_URL ?>/admin.php">Admin Panel</a>
                            <hr>
                            <a href="<?= BASE_URL ?>/auth/logout.php" class="text-danger">Logout</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="nav-links">
                    <a href="<?= BASE_URL ?>/auth/login.php" class="btn btn-ghost">Login</a>
                    <a href="<?= BASE_URL ?>/auth/register.php" class="btn btn-primary">Join Now</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <main class="container">
