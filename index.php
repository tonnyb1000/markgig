<?php
/**
 * MarkGigs Main Feed / Landing
 */
require_once 'includes/functions.php';

if (!is_logged_in()) {
    $page_title = "Connect, Grow & Get Hired";
    require_once 'includes/header.php';
    require_once 'includes/components/grid_pattern.php';
    ?>
    <section class="hero">
        <div class="hero-grid-wrapper">
            <?php render_grid_pattern([
                'width' => 45,
                'height' => 45,
                'squares' => [
                    [4, 4], [5, 1], [8, 2], [5, 3], [5, 5], 
                    [10, 10], [12, 15], [15, 10], [10, 15]
                ]
            ]); ?>
        </div>
        <div class="hero-bg">
            <div class="hero-orb orb-1"></div>
            <div class="hero-orb orb-2"></div>
        </div>
        <div class="hero-content">
            <div class="hero-badge"><i class="fa-solid fa-sparkles"></i> The Bridging Phase is Live</div>
            <h1 class="hero-title">Connect. Grow. <span class="grad-text">Get Hired.</span></h1>
            <p class="hero-subtitle">The professional network built for the next generation of university talent and top-tier companies.</p>
            <div class="hero-actions">
                <a href="auth/register.php" class="btn btn-primary btn-lg">Join MarkGigs today</a>
                <a href="auth/login.php" class="btn btn-glass btn-lg">Sign In</a>
            </div>
            <div class="hero-stats">
                <div class="stat-chip">⭐ 5.0 University Rating</div>
                <div class="stat-chip">💼 50+ Top Employers</div>
                <div class="stat-chip">🎓 2000+ Students Joined</div>
            </div>
        </div>
    </section>
    <?php
    require_once 'includes/footer.php';
    exit;
}

// --- Logged In: Feed Logic ---
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch User Profile
if ($role === 'individual') {
    $stmt = $pdo->prepare("SELECT * FROM individuals WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
} else {
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
}

// Handle Post Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_post') {
    $content = trim($_POST['content']);
    $type = $_POST['type'] ?? 'update';
    
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO posts (author_id, content, post_type) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $content, $type]);
        set_flash("Post shared successfully!", "success");
        redirect('index.php');
    }
}

// Fetch Feed Posts
$stmt = $pdo->query("
    SELECT p.*, COALESCE(i.full_name, c.name) as author_name, COALESCE(i.avatar, c.logo) as author_avatar,
           (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as likes
    FROM posts p
    JOIN users u ON p.author_id = u.id
    LEFT JOIN individuals i ON i.user_id = u.id
    LEFT JOIN companies c ON c.user_id = u.id
    ORDER BY p.created_at DESC
    LIMIT 20
");
$posts = $stmt->fetchAll();

$active_page = 'feed';
$page_title = "Home Feed";
require_once 'includes/header.php';
?>

<div class="feed-layout">
    <!-- Sidebar: Mini Profile -->
    <aside class="feed-left">
        <div class="card profile-mini">
            <div class="profile-mini-banner"></div>
            <div class="profile-mini-body">
                <img src="<?= BASE_URL ?>/uploads/avatars/default.svg" class="profile-mini-avatar">
                <h3 class="profile-mini-name"><?= $role === 'individual' ? $profile['full_name'] : $profile['name'] ?></h3>
                <p class="profile-mini-sub"><?= $role === 'individual' ? ($profile['headline'] ?? 'Professional') : $profile['industry'] ?></p>
                <div class="card-divider"></div>
                <div class="quick-links">
                    <a href="profile.php"><i class="fa-solid fa-user"></i> My Profile</a>
                    <a href="network.php"><i class="fa-solid fa-users"></i> Network</a>
                    <a href="mentors.php"><i class="fa-solid fa-graduation-cap"></i> Mentorship</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content: Feed -->
    <main class="feed-main">
        <!-- Post Creation -->
        <div class="card mb-4">
            <form action="index.php" method="POST">
                <input type="hidden" name="action" value="create_post">
                <div class="post-create-card">
                    <img src="<?= BASE_URL ?>/uploads/avatars/default.svg" class="post-create-avatar">
                    <textarea name="content" class="form-control" placeholder="Share an update, achievement, or question..." required style="min-height: 80px;"></textarea>
                </div>
                <div class="post-type-bar mt-3">
                    <select name="type" class="form-control" style="width: auto;">
                        <option value="update">Update</option>
                        <option value="achievement">Achievement</option>
                        <option value="question">Question</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm ml-auto">Post Update</button>
                </div>
            </form>
        </div>

        <!-- Posts List -->
        <?php if (empty($posts)): ?>
            <div class="card text-center py-5">
                <p class="text-muted">No posts yet. Be the first to start the conversation!</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card post-card">
                    <div class="post-header">
                        <img src="<?= BASE_URL ?>/uploads/avatars/default.svg" class="post-avatar">
                        <div class="post-author-info">
                            <span class="post-author-name"><?= htmlspecialchars($post['author_name']) ?></span>
                            <span class="post-author-sub"><?= date('M j, Y', strtotime($post['created_at'])) ?></span>
                        </div>
                        <span class="post-type-tag type-<?= $post['post_type'] ?>"><?= $post['post_type'] ?></span>
                    </div>
                    <div class="post-body"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
                    <div class="post-footer">
                        <button class="like-btn">
                            <i class="fa-regular fa-heart"></i> <?= $post['likes'] ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <!-- Sidebar: Suggestions (Placeholder) -->
    <aside class="feed-right">
        <div class="card">
            <h4>Recommended for you</h4>
            <div class="quick-links">
                <p class="text-muted small">Connect with alumni and professionals in your field.</p>
                <a href="network.php" class="btn btn-glass btn-sm w-100 mt-2">Grow your Network</a>
            </div>
        </div>
    </aside>
</div>

<?php require_once 'includes/footer.php'; ?>
