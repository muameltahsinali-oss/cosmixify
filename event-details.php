<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

if (!isset($_GET['id'])) {
    header('Location: calendar.php');
    exit();
}

$event_id = (int) $_GET['id'];
$query = "SELECT * FROM astronomical_events WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    echo e(t('common.error'));
    exit();
}

$pageClass = 'inner-page';
include __DIR__ . '/includes/layout-start.php';
?>

<section class="page-section page-section--compact">
    <div class="page-container">
        <div class="section-head">
            <h2 class="section-title"><?= e($event['event_title']) ?></h2>
        </div>

        <article class="event-bento-card">
            <img src="<?= e($event['event_image']) ?>" alt="<?= e($event['event_title']) ?>">
            <div class="event-bento-card__body">
                <p><strong><?= e(t('event.starts')) ?>:</strong> <?= e($event['event_date']) ?></p>
                <p><strong><?= e(t('event.ends')) ?>:</strong> <?= e($event['event_end']) ?></p>
                <div><?= $event['event_description'] ?></div>
                <p><strong><?= e(t('event.best_method')) ?>:</strong> <?= e($event['best_viewing_method']) ?></p>
                <p><strong><?= e(t('event.pollution')) ?>:</strong> <?= e(pollution_label($event['light_pollution_viewability'])) ?></p>
                <p><strong><?= e(t('event.best_location')) ?>:</strong> <?= e($event['best_viewing_location']) ?></p>
                <div class="hero-split__actions" style="margin-top:1rem;">
                    <a href="https://calendar.google.com/calendar/u/0/r/eventedit" class="btn-cosmic btn-cosmic--ghost"><?= e(t('event.google_cal')) ?></a>
                    <a href="Light-pollution.php" class="btn-cosmic btn-cosmic--primary"><?= e(t('event.light_map')) ?></a>
                </div>
            </div>
        </article>
    </div>
</section>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
