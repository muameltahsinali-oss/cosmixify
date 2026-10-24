<?php
require_once __DIR__ . '/includes/i18n.php';
$lang = current_lang();
$dir = is_rtl() ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>" dir="<?= e($dir) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e(t('site.name')) ?></title>
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">
