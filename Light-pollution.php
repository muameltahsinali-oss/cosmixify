<?php
require_once __DIR__ . '/includes/bootstrap.php';

$pageClass = 'inner-page';
$hideBottomNav = true;
include __DIR__ . '/includes/layout-start.php';
?>
<style>
    .light-map-frame {
        position: fixed;
        inset-inline: 0;
        top: var(--header-total);
        border: none;
        width: 100%;
        height: calc(100dvh - var(--header-total));
    }
</style>
<iframe class="light-map-frame" src="https://www.lightpollutionmap.info/#zoom=4.00&lat=45.7116&lon=-51.3669&state=eyJiYXNlbWFwIjoiTGF5ZXJCaW5nUm9hZCIsIm92ZXJsYXkiOiJ3YV8yMDE1Iiwib3ZlcmxheWNvbG9yIjpmYWxzZSwib3ZlcmxheW9wYWNpdHkiOjYwLCJmZWF0dXJlc29wYWNpdHkiOjg1fQ==" allowfullscreen title="<?= e(t('event.light_map')) ?>"></iframe>
<?php include __DIR__ . '/includes/layout-end.php'; ?>
