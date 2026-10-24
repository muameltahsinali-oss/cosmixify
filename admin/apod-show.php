<?php

session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php"); // إعادة توجيه إلى صفحة تسجيل الدخول إذا لم يكن هناك جلسة
    exit();
    
}
// الاتصال بقاعدة البيانات
include '../db-conn.php';

// جلب كل البيانات المخزنة في قاعدة البيانات
$sql = "SELECT title, explanation, url, date FROM apod ORDER BY date DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmixify - APOD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Astronomy Pictures of the Day</h1>
        <?php
        if ($result->num_rows > 0) {
            // عرض جميع الصور
            while ($row = $result->fetch_assoc()) {
                echo '<div class="apod-item">';
                echo '<h2>' . $row['title'] . '</h2>';
                echo '<p><strong>Date:</strong> ' . $row['date'] . '</p>';
                echo '<img src="' . $row['url'] . '" alt="APOD Image">';
                echo '<p>' . $row['explanation'] . '</p>';
                echo '</div>';
            }
        } else {
            echo "<p>No data available!</p>";
        }
        ?>
    </div>
</body>
</html>
