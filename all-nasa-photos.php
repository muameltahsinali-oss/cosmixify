<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

$sql = "SELECT id, title, explanation, url, date FROM apod ORDER BY date DESC LIMIT 30";
$result = $conn->query($sql);

$pageClass = 'inner-page';
include __DIR__ . '/includes/layout-start.php';
?>

<section class="page-section page-section--compact">
    <div class="page-container">
        <div class="section-head">
            <h2 class="section-title"><?= t('gallery.nasa_title') ?></h2>
        </div>
        <?php if ($result && $result->num_rows > 0): ?>
        <div class="bento-grid bento-grid--nasa bento-grid--gallery">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $photo = [
                    'id' => $row['id'],
                    'Title' => $row['title'],
                    'description' => $row['explanation'],
                    'image' => $row['url'],
                    'badge' => t('home.badge_nasa'),
                ];
                render_bento_photo($photo, '', '', 'nasa-photo-details.php');
                ?>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="home-empty"><?= e(t('home.empty_nasa')) ?></div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
