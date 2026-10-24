<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

require '../db-conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$ardescription = trim($_POST['ardescription'] ?? '');
$source = trim($_POST['source'] ?? '');
$sourceUrl = trim($_POST['sourceUrl'] ?? '');
$date = trim($_POST['date'] ?? '');
$category = trim($_POST['category'] ?? '');
$tags = array_map('trim', explode(',', $_POST['tags'] ?? ''));
$image = $_FILES['image']['name'] ?? '';

if (empty($title) || empty($description) || empty($ardescription) || empty($category) || empty($image)) {
    echo 'جميع الحقول المطلوبة يجب تعبئتها!';
    exit();
}

$allowed_extensions = ALLOWED_IMAGE_EXTENSIONS;
$image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
if (!in_array($image_extension, $allowed_extensions, true)) {
    echo 'يجب أن يكون الملف صورة (jpg, jpeg, png, gif, webp).';
    exit();
}

$target_dir = '../uploads/';
$filename = uniqid('photo_', true) . '.' . $image_extension;
$target_file = $target_dir . $filename;
$db_path = 'uploads/' . $filename;

if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
    $sql = 'INSERT INTO photos (Title, description, source, sourceUrl, date, category, image, ardescription) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $title, $description, $source, $sourceUrl, $date, $category, $db_path, $ardescription);

    if ($stmt->execute()) {
        $photo_id = $stmt->insert_id;
        $tag_sql = 'INSERT INTO photos_tags (photo_id, tag) VALUES (?, ?)';
        $stmt_tag = $conn->prepare($tag_sql);

        foreach ($tags as $tag) {
            if (!empty($tag)) {
                $stmt_tag->bind_param('is', $photo_id, $tag);
                $stmt_tag->execute();
            }
        }

        echo 'تم رفع الصورة وإضافة الوسوم بنجاح!';
    } else {
        echo 'خطأ في إدخال البيانات في جدول photos: ' . $stmt->error;
    }

    $stmt->close();
} else {
    echo 'خطأ في رفع الملف.';
}

$conn->close();
