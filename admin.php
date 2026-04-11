<?php
/**
 * MarkGigs Admin Dashboard
 */
require_once 'includes/functions.php';
require_login();

if (!has_role('admin')) {
    set_flash("Access Denied: Admin only.", "danger");
    redirect('index.php');
}

// Handle Verification
if (isset($_GET['verify'])) {
    $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
    $stmt->execute([$_GET['verify']]);
    set_flash("User verified successfully.", "success");
    redirect('admin.php');
}

// Fetch stats
$user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$job_count = $pdo->query("SELECT COUNT(*) FROM opportunities")->fetchColumn();
$app_count = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn();

// Fetch unverified users
$stmt = $pdo->query("
    SELECT u.*, COALESCE(i.full_name, c.name) as name 
    FROM users u 
    LEFT JOIN individuals i ON i.user_id = u.id 
    LEFT JOIN companies c ON c.user_id = u.id 
    WHERE u.is_verified = 0 AND u.role != 'admin'
    ORDER BY u.created_at DESC
");
$unverified = $stmt->fetchAll();

$page_title = "Admin Dashboard";
require_once 'includes/header.php';
?>

<div class="admin-header mb-5">
    <h1 class="page-title">Admin <span class="grad-text">Control Center</span></h1>
    <div class="row" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
        <div class="card text-center">
            <h2 class="m-0"><?= $user_count ?></h2>
            <p class="text-muted small">Total Users</p>
        </div>
        <div class="card text-center">
            <h2 class="m-0"><?= $job_count ?></h2>
            <p class="text-muted small">Open Opportunities</p>
        </div>
        <div class="card text-center">
            <h2 class="m-0"><?= $app_count ?></h2>
            <p class="text-muted small">Total Applications</p>
        </div>
    </div>
</div>

<div class="card">
    <h4 class="mb-4">Verification Queue</h4>
    <?php if (empty($unverified)): ?>
        <p class="text-muted text-center py-4">No users awaiting verification.</p>
    <?php else: ?>
        <table class="mgig-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unverified as $user): ?>
                    <tr>
                        <td>
                            <div class="font-weight-bold"><?= htmlspecialchars($user['name']) ?></div>
                            <small class="text-muted"><?= $user['email'] ?></small>
                        </td>
                        <td><span class="chip"><?= strtoupper($user['role']) ?></span></td>
                        <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <a href="admin.php?verify=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Verify</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
