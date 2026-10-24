<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

if (!isset($_GET['id'])) {
    echo e(t('detail.no_id'));
    exit;
}

$id = (int) $_GET['id'];
$stmt = $conn->prepare('SELECT title, explanation, url, date FROM apod WHERE id = ?');
$stmt->bind_param('i', $id);
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

<main class="photo-details page-section--compact">
    <div class="page-container">
        <img src="<?= e($row['url']) ?>" alt="<?= e($row['title']) ?>" class="photo-img main-image-details">
        <h1 class="image-title-details"><?= e($row['title']) ?></h1>
        <p class="image-meta-details"><strong><?= e(t('detail.date')) ?>:</strong> <?= e($row['date']) ?></p>
        <p class="image-description-details"><?= e($row['explanation']) ?></p>
        <div class="image-actions-details">
            <button id="share-button" class="action-button-details" type="button"><i class="fas fa-share-alt"></i> <?= e(t('detail.share')) ?></button>
            <a href="<?= e($row['url']) ?>" download class="action-button-details"><i class="fas fa-download"></i> <?= e(t('detail.download')) ?></a>
            <div id="copy-message" style="display:none;"><?= e(t('detail.link_copied')) ?></div>
        </div>
    </div>
</main>

<script>
document.getElementById('share-button').addEventListener('click', function() {
    navigator.clipboard.writeText(window.location.href);
    var message = document.getElementById('copy-message');
    message.style.display = 'block';
    setTimeout(function() { message.style.display = 'none'; }, 2000);
});
</script>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
