<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include '../db-conn.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // حذف الإدخالات المرتبطة في photos_tags
    $sql = "DELETE FROM photos_tags WHERE photo_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // حذف الإدخال من جدول photos
    $sql = "DELETE FROM photos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage-posts.php");
        exit();
    } else {
        echo "Error deleting post: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No ID provided";
}

$conn->close();
?>
