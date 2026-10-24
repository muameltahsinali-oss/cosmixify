<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

if (!isset($_GET['SpaceSearch'])) {
    echo e(t('search.no_term'));
    exit;
}

$searchTerm = $_GET['SpaceSearch'];

$sql = "
    (SELECT DISTINCT photos.id, photos.Title, photos.description, photos.image, 'photos' AS source
    FROM photos
    LEFT JOIN photos_tags ON photos.id = photos_tags.photo_id
    WHERE (photos.Title COLLATE utf8mb4_unicode_ci LIKE CONCAT('%', ?, '%')
        OR photos.description COLLATE utf8mb4_unicode_ci LIKE CONCAT('%', ?, '%')
        OR photos_tags.tag COLLATE utf8mb4_unicode_ci LIKE CONCAT('%', ?, '%')))

    UNION ALL

    (SELECT DISTINCT apod.id, apod.title AS Title, apod.explanation AS description, apod.url AS image, 'apod' AS source
    FROM apod
    WHERE apod.title COLLATE utf8mb4_unicode_ci LIKE CONCAT('%', ?, '%')
        OR apod.explanation COLLATE utf8mb4_unicode_ci LIKE CONCAT('%', ?, '%'))

    ORDER BY source DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('sssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$pageClass = 'inner-page';
include __DIR__ . '/includes/layout-start.php';
?>

<section class="page-section page-section--compact">
    <div class="page-container">
        <div class="section-head">
            <h2 class="section-title"><?= t('search.title') ?></h2>
        </div>

        <?php if ($result->num_rows > 0): ?>
        <div class="bento-grid bento-grid--photos bento-grid--gallery">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                if ($row['source'] === 'apod') {
                    $row['badge'] = t('home.badge_nasa');
                    render_bento_photo($row, '', '', 'nasa-photo-details.php');
                } else {
                    render_bento_photo($row);
                }
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
