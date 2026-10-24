<?php
session_start();
include '../db-conn.php'; // الاتصال بقاعدة البيانات

// تحقق من تسجيل دخول المستخدم كمسؤول
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

// معالجة قبول الصورة
if (isset($_POST['accept'])) {
    $photo_id = $_POST['photo_id'];
    $sql = "UPDATE user_photos SET accept = 'on' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();
}

// معالجة تعليق الصورة
if (isset($_POST['suspend'])) {
    $photo_id = $_POST['photo_id'];
    $sql = "UPDATE user_photos SET accept = 'off' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();
}

// معالجة حذف الصورة
if (isset($_POST['delete'])) {
    $photo_id = $_POST['photo_id'];
    $sql = "DELETE FROM user_photos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();
}

// جلب الصور من قاعدة البيانات
$sql = "SELECT * FROM user_photos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .admin-container {
            margin-top: 50px;
        }
        .photo-card {
            margin-bottom: 20px;
            background-color: #1f2833;
            border: 1px solid #0b0c10;
            border-radius: 10px;
            padding: 20px;
            color: #c5c6c7;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }
        .photo-card img {
            max-width: 100%;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .btn-custom {
            background-color: #a64ac9;
            border-color: #a64ac9;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #d48bff;
            border-color: #d48bff;
        }
    </style>
</head>
<body>

<div class="container admin-container">
    <h2 class="text-center">Admin Dashboard - User Photos Management</h2>
    
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="photo-card">
                        <img src="../uploads/user_photos/<?php echo $row['image']; ?>" width="10%" alt="<?php echo $row['Title']; ?>">
                        <h4><?php echo $row['Title']; ?></h4>
                        <p><?php echo $row['description']; ?></p>
                        <p><strong>Date Captured:</strong> <?php echo $row['capture_date']; ?></p>
                        <p><strong>Device Used:</strong> <?php echo $row['device']; ?></p>
                        <p><strong>Status:</strong> <?php echo $row['accept']; ?></p>

                        <form method="post" action="users_photos.php">
                            <input type="hidden" name="photo_id" value="<?php echo $row['id']; ?>">
                            <?php if ($row['accept'] == 'pending'): ?>
                                <button type="submit" name="accept" class="btn btn-custom">Accept</button>
                                <button type="submit" name="suspend" class="btn btn-warning">Suspend</button>
                            <?php elseif ($row['accept'] == 'on'): ?>
                                <button type="submit" name="suspend" class="btn btn-warning">Suspend</button>
                            <?php elseif ($row['accept'] == 'off'): ?>
                                <button type="submit" name="accept" class="btn btn-custom">Accept</button>
                            <?php endif; ?>
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No photos to display.</p>
        <?php endif; ?>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>

</body>
</html>
