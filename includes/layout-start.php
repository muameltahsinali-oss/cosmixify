<?php
$pageClass = $pageClass ?? 'inner-page';
$navMode = $navMode ?? 'inner';
$hideBottomNav = $hideBottomNav ?? false;
$hideSearch = $hideSearch ?? false;

include __DIR__ . '/../title.php';
include __DIR__ . '/../styles.php';
if (!empty($pageStyles) && is_array($pageStyles)) {
    foreach ($pageStyles as $styleHref) {
        echo '    <link href="' . e($styleHref) . '" rel="stylesheet">' . "\n";
    }
}
?>
</head>
<body class="<?= e($pageClass) ?><?= !$hideBottomNav ? ' has-bottom-nav' : '' ?>">
<?php if (!$hideSearch): include __DIR__ . '/../search-box.php'; endif; ?>
<?php if (!$hideBottomNav): include __DIR__ . '/bottom-nav.php'; endif; ?>
