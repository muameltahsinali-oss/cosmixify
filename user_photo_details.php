<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

if (!isset($_GET['id'])) {
    echo e(t('detail.no_id'));
    exit;
}

$photo_id = (int) $_GET['id'];
$sql = "SELECT Title, description, image, user_id FROM user_photos WHERE id = ? AND accept = 'on'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $photo_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo e(t('detail.not_found'));
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();

$pageClass = 'inner-page detail-page';
include __DIR__ . '/includes/layout-start.php';
?>

<main class="image-display-details">
    <div class="image-container-details">
        <img src="<?= e(user_photo_path($row['image'])) ?>" alt="<?= e($row['Title']) ?>" class="main-image-details">
        <div class="image-info-details">
            <h2 class="image-title-details"><?= e($row['Title']) ?></h2>
            <p class="image-description-details"><?= e($row['description']) ?></p>
            <div class="image-actions-details">
                <button id="share-button" class="action-button-details" type="button"><i class="fas fa-share-alt"></i> <?= e(t('detail.share')) ?></button>
                <a href="<?= e(user_photo_path($row['image'])) ?>" download class="action-button-details"><i class="fas fa-download"></i> <?= e(t('detail.download')) ?></a>
                <div id="copy-message" style="display:none;"><?= e(t('detail.link_copied')) ?></div>
                <?php if (!empty($row['user_id'])): ?>
                    <a href="view-profile.php?id=<?= (int) $row['user_id'] ?>" class="action-button-details"><?= e(t('nav.profile')) ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById('share-button').addEventListener('click', function() {
    navigator.clipboard.writeText(window.location.href);
    document.getElementById('copy-message').style.display = 'block';
    setTimeout(function() { document.getElementById('copy-message').style.display = 'none'; }, 2000);
});
</script>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
