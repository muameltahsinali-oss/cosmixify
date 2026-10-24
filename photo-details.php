<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

$conn->query("INSERT INTO site_visits (visit_date) VALUES (NOW())");

if (!isset($_GET['id'])) {
    echo e(t('detail.no_id'));
    exit;
}

$photo_id = (int) $_GET['id'];
$sql = "SELECT Title, description, ardescription, category, source, sourceUrl, date, image FROM photos WHERE id = ?";
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

$update_views_sql = "UPDATE photos SET views = views + 1 WHERE id = ?";
$stmt_update = $conn->prepare($update_views_sql);
$stmt_update->bind_param('i', $photo_id);
$stmt_update->execute();
$stmt_update->close();

$title = $row['Title'];
$description = locale_content($row, 'description', 'ardescription');
$category = $row['category'];
$source = $row['source'];
$sourceUrl = $row['sourceUrl'];
$date = $row['date'];
$image = $row['image'];

$tags_sql = "SELECT tag FROM photos_tags WHERE photo_id = ?";
$stmt_tags = $conn->prepare($tags_sql);
$stmt_tags->bind_param('i', $photo_id);
$stmt_tags->execute();
$tags_result = $stmt_tags->get_result();
$tags = [];
while ($tag_row = $tags_result->fetch_assoc()) {
    $tags[] = $tag_row['tag'];
}
$stmt_tags->close();

$is_favorited = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $fav_check_sql = "SELECT 1 FROM favs WHERE user_id = ? AND photo_id = ?";
    $stmt_fav = $conn->prepare($fav_check_sql);
    $stmt_fav->bind_param('ii', $user_id, $photo_id);
    $stmt_fav->execute();
    $is_favorited = $stmt_fav->get_result()->num_rows > 0;
    $stmt_fav->close();
}

if (isset($_POST['save_to_favorites']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $save_sql = "INSERT INTO favs (user_id, photo_id) VALUES (?, ?)";
    $stmt_save = $conn->prepare($save_sql);
    $stmt_save->bind_param('ii', $user_id, $photo_id);
    if ($stmt_save->execute()) {
        $is_favorited = true;
    }
    $stmt_save->close();
}

if (isset($_POST['remove_from_favorites']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $remove_sql = "DELETE FROM favs WHERE user_id = ? AND photo_id = ?";
    $stmt_remove = $conn->prepare($remove_sql);
    $stmt_remove->bind_param('ii', $user_id, $photo_id);
    if ($stmt_remove->execute()) {
        $is_favorited = false;
    }
    $stmt_remove->close();
}

$pageClass = 'inner-page detail-page';
include __DIR__ . '/includes/layout-start.php';
?>

<main class="image-display-details">
    <div class="image-container-details">
        <img src="<?= e(asset_path($image)) ?>" alt="<?= e($title) ?>" class="main-image-details">
        <div class="image-info-details">
            <h2 class="image-title-details"><?= e($title) ?></h2>
            <p class="image-description-details"><?= $description ?></p>
            <div class="image-meta-details">
                <span><strong><?= e(t('detail.category')) ?>:</strong> <?= e($category) ?></span>
                <span><strong><?= e(t('detail.source')) ?>:</strong> <a href="<?= e($sourceUrl) ?>"><?= e($source) ?></a></span>
                <span><strong><?= e(t('detail.date')) ?>:</strong> <?= e($date) ?></span>
                <?php foreach ($tags as $tag): ?>
                    <a href="search.php?SpaceSearch=<?= urlencode($tag) ?>"><?= e($tag) ?></a>
                <?php endforeach; ?>
            </div>
            <div class="image-actions-details">
                <button id="share-button" class="action-button-details" type="button"><i class="fas fa-share-alt"></i> <?= e(t('detail.share')) ?></button>
                <a href="<?= e(asset_path($image)) ?>" download class="action-button-details"><i class="fas fa-download"></i> <?= e(t('detail.download')) ?></a>
                <div id="copy-message" style="display:none;"><?= e(t('detail.link_copied')) ?></div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="photo-details.php?id=<?= $photo_id ?>" method="post">
                        <?php if ($is_favorited): ?>
                            <button type="submit" name="remove_from_favorites" class="action-button-details"><i class="fas fa-heart-broken"></i> <?= e(t('detail.remove_fav')) ?></button>
                        <?php else: ?>
                            <button type="submit" name="save_to_favorites" class="action-button-details"><i class="fas fa-heart"></i> <?= e(t('detail.add_fav')) ?></button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<section class="related-images-details">
    <h3><?= e(t('detail.related')) ?></h3>
    <div class="related-images-container-details">
        <?php
        $related_sql = "SELECT id, Title, image FROM photos WHERE category = ? AND id != ? LIMIT 4";
        $stmt_related = $conn->prepare($related_sql);
        $stmt_related->bind_param('si', $category, $photo_id);
        $stmt_related->execute();
        $related_result = $stmt_related->get_result();

        if ($related_result->num_rows > 0) {
            while ($related_row = $related_result->fetch_assoc()) {
                echo '<div class="image-card-details">';
                echo '<a href="photo-details.php?id=' . (int) $related_row['id'] . '">';
                echo '<img src="' . e(asset_path($related_row['image'])) . '" alt="' . e($related_row['Title']) . '">';
                echo '<p>' . e($related_row['Title']) . '</p>';
                echo '</a></div>';
            }
        } else {
            echo '<p>' . e(t('detail.no_related')) . '</p>';
        }
        $stmt_related->close();
        ?>
    </div>
</section>

<script>
document.getElementById('share-button').addEventListener('click', function() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        var message = document.getElementById('copy-message');
        message.style.display = 'block';
        setTimeout(function() { message.style.display = 'none'; }, 2000);
    });
});
</script>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
