<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

$sql = "SELECT id, Title, description, ardescription, image FROM photos ORDER BY views DESC LIMIT 30";
$result = $conn->query($sql);

$pageClass = 'inner-page';
include __DIR__ . '/includes/layout-start.php';
?>

<section class="page-section page-section--compact">
    <div class="page-container">
        <div class="section-head">
            <h2 class="section-title"><?= t('gallery.top_rated_title') ?></h2>
        </div>
        <?php if ($result && $result->num_rows > 0): ?>
        <div class="bento-grid bento-grid--photos bento-grid--gallery">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $row['description'] = locale_content($row, 'description', 'ardescription');
                $row['badge'] = t('home.badge_top');
                render_bento_photo($row);
                ?>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="home-empty"><?= e(t('home.empty_rated')) ?></div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
