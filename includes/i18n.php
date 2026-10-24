<?php

define('SUPPORTED_LANGS', ['en', 'ar']);
define('LANG_COOKIE', 'cosmixify_lang');

function init_i18n(): void
{
    if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGS, true)) {
        setcookie(LANG_COOKIE, $_GET['lang'], [
            'expires' => time() + 86400 * 365,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $lang = $_GET['lang'];
    } elseif (isset($_COOKIE[LANG_COOKIE]) && in_array($_COOKIE[LANG_COOKIE], SUPPORTED_LANGS, true)) {
        $lang = $_COOKIE[LANG_COOKIE];
    } else {
        $lang = 'en';
    }

    $GLOBALS['current_lang'] = $lang;
    $file = __DIR__ . '/../lang/' . $lang . '.php';
    $GLOBALS['translations'] = file_exists($file) ? require $file : [];
}

function current_lang(): string
{
    return $GLOBALS['current_lang'] ?? 'en';
}

function is_rtl(): bool
{
    return current_lang() === 'ar';
}

function t(string $key): string
{
    return $GLOBALS['translations'][$key] ?? $key;
}

function lang_url(string $lang): string
{
    $params = $_GET;
    $params['lang'] = $lang;
    $script = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    $query = http_build_query($params);
    return $script . ($query ? '?' . $query : '');
}

function locale_content(array $row, string $enKey, string $arKey): string
{
    if (current_lang() === 'ar' && !empty($row[$arKey])) {
        return (string) $row[$arKey];
    }
    return (string) ($row[$enKey] ?? '');
}

init_i18n();
