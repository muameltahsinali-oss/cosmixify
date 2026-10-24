<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php"); // إعادة توجيه إلى صفحة تسجيل الدخول إذا لم يكن هناك جلسة
    exit();
    
}
include '../db-conn.php';
// التحقق من أن الطلب يتضمن معرف المنشور
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // جلب بيانات المنشور من قاعدة البيانات
    $sql = "SELECT * FROM photos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        echo json_encode($post);
    } else {
        echo json_encode(['error' => 'Post not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'No post ID provided']);
}

$conn->close();
?>
