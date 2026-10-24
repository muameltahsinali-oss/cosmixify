<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include '../db-conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM photos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
} else {
    header('Location: manage-posts.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $ar_description = $_POST['ar_description'];
    
    // معالجة رفع الصورة
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image);

        // نقل الصورة إلى مجلد ../uploads/
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file; // مسار الصورة الجديدة
        } else {
            echo "Error uploading the image.";
            exit();
        }
    } else {
        $image_path = $_POST['current_image']; // في حالة عدم تحديث الصورة
    }

    // تحديث البيانات في قاعدة البيانات
    $sql = "UPDATE photos SET Title = ?, description = ?, ardescription = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $ar_description, $image_path, $id);

    if ($stmt->execute()) {
        header("Location: manage-posts.php");
        exit();
    } else {
        echo "Error updating post: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> <!-- تضمين أحدث إصدار من CKEditor -->
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h2 class="text-center mb-4">Edit Post</h2>
    <form action="edit_post.php?id=<?php echo $post['id']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $post['Title']; ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description (English)</label>
            <textarea class="form-control" id="description" name="description" required><?php echo $post['description']; ?></textarea>
        </div>

        <div class="form-group">
            <label for="ar_description">الوصف بالعربية</label>
            <textarea class="form-control" id="ar_description" name="ar_description" required><?php echo $post['ardescription']; ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image">
            <input type="hidden" name="current_image" value="<?php echo $post['image']; ?>">
            <img src="<?php echo $post['image']; ?>" width="100" height="100" alt="Current Image">
        </div>

        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
</div>

<script>
    CKEDITOR.replace('description');   // تفعيل CKEditor للوصف بالإنجليزية
    CKEDITOR.replace('ar_description'); // تفعيل CKEditor للوصف بالعربية
</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
