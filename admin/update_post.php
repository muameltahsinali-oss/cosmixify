<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php"); // إعادة توجيه إلى صفحة تسجيل الدخول إذا لم يكن هناك جلسة
    exit();
    
}
include '../db-conn.php';

// التحقق من أن الطلب يتضمن جميع البيانات المطلوبة
if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['image'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    // تحديث بيانات المنشور في قاعدة البيانات
    $sql = "UPDATE photos SET Title = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $description, $image, $id);

    if ($stmt->execute()) {
        echo "Post updated successfully";
    } else {
        echo "Error updating post: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Missing required fields";
}

$conn->close();
?>
