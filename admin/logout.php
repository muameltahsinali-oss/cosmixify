<?php
session_start(); // بدء الجلسة

// حذف جميع البيانات من الجلسة
$_SESSION = array();

// إذا كان هناك ملف تعريف ارتباط للجلسة، احذفه
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], 
        $params["secure"], $params["httponly"]
    );
}

// تدمير الجلسة
session_destroy();

// إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
header("Location: index.php");
exit();
?>
