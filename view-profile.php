<?php
require_once __DIR__ . '/includes/bootstrap.php';
require 'db-conn.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$user_id = (int) $_GET['id'];
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: index.php');
    exit();
}

$stmt = $conn->prepare('SELECT id, Title, description, image FROM user_photos WHERE user_id = ? AND accept = "on"');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_photos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$pageClass = 'profile-page';
$navMode = 'inner';
include __DIR__ . '/includes/layout-start.php';
?>

<div class="page-container profile-container">
    <div class="profile-header">
        <div class="profile-image">
            <img src="<?= e($user['profile_image'] ?? 'uploads/default.jpg') ?>" alt="">
        </div>
        <h1 class="user-name"><?= e($user['Fname'] . ' ' . $user['Lname']) ?></h1>
    </div>

    <h2><?= e(t('profile.my_shares')) ?></h2>
    <div class="bento-grid bento-grid--photos bento-grid--gallery">
        <?php foreach ($user_photos as $photo): ?>
            <?php render_bento_user_photo($photo); ?>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
