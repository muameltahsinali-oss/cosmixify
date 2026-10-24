<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

$sql = "SELECT id, Title, description, image FROM user_photos WHERE accept = 'on' ORDER BY id DESC LIMIT 30";
$result = $conn->query($sql);

$pageClass = 'inner-page';
include __DIR__ . '/includes/layout-start.php';
?>

<section class="page-section page-section--compact">
    <div class="page-container">
        <div class="section-head">
            <h2 class="section-title"><?= t('gallery.user_title') ?></h2>
        </div>
        <?php if ($result && $result->num_rows > 0): ?>
        <div class="bento-grid bento-grid--photos bento-grid--gallery">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $row['badge'] = t('home.badge_community');
                render_bento_user_photo($row);
                ?>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="home-empty"><?= e(t('home.empty_community')) ?></div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
