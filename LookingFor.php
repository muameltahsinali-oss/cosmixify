<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

if (!isset($_GET['tag'])) {
    echo e(t('looking.no_tag'));
    exit;
}

$tag = $_GET['tag'];
$sql = "SELECT photos.id, photos.Title, photos.description, photos.ardescription, photos.image
        FROM photos
        INNER JOIN photos_tags ON photos.id = photos_tags.photo_id
        WHERE photos_tags.tag = ?
        ORDER BY photos.views DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $tag);
$stmt->execute();
$result = $stmt->get_result();

$pageClass = 'inner-page';
include __DIR__ . '/includes/layout-start.php';
?>

<section class="page-section page-section--compact">
    <div class="page-container">
        <div class="section-head">
            <h2 class="section-title"><?= t('looking.title') ?>: <?= e($tag) ?></h2>
        </div>
        <?php if ($result->num_rows > 0): ?>
        <div class="bento-grid bento-grid--photos bento-grid--gallery">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $row['description'] = locale_content($row, 'description', 'ardescription');
                render_bento_photo($row);
                ?>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="home-empty"><?= e(t('search.no_results')) ?></div>
        <?php endif; ?>
    </div>
</section>

<?php
$stmt->close();
include __DIR__ . '/includes/layout-end.php';
