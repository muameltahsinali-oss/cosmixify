<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/i18n.php';
require_once __DIR__ . '/helpers.php';
