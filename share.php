<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login-register.php');
    exit();
}

$message = '';

// معالجة تحميل الصورة
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $capture_date = trim($_POST['capture_date']);
    $device = trim($_POST['device']);
    
    // دمج الاسم الأول واسم العائلة في متغير واحد
    $user_name = $_SESSION['Fname'] . ' ' . $_SESSION['Lname'];
    // الحصول على user_id من الجلسة
    $user_id = $_SESSION['user_id'];

    // معالجة تحميل الملف
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageTmpName = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageError = $image['error'];
        $imageType = $image['type'];

        $fileExt = explode('.', $imageName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = ALLOWED_IMAGE_EXTENSIONS;
        $maxSize = UPLOAD_MAX_SIZE;

        if (in_array($fileActualExt, $allowed)) {
            if ($imageError === 0) {
                if ($imageSize < $maxSize) {
                    $newImageName = uniqid('', true) . "." . $fileActualExt;
                    $imageDestination = 'uploads/user_photos/' . $newImageName;
                    move_uploaded_file($imageTmpName, $imageDestination);

                    // حفظ البيانات في قاعدة البيانات، بما في ذلك user_id
                    $sql = "INSERT INTO user_photos (Title, description, image, capture_date, device, user_email, user_name, user_id, accept) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sssssssi', $title, $description, $newImageName, $capture_date, $device, $_SESSION['user_email'], $user_name, $user_id);
                    if ($stmt->execute()) {
                        $message = "Photo uploaded successfully! Cheack photo status in your '<a href='profile.php' >'profile! '</a>'";
                    } else {
                        $message = "Error uploading photo: " . $conn->error;
                    }
                } else {
                    $message = "Your image is too big!";
                }
            } else {
                $message = "There was an error uploading your image!";
            }
        } else {
            $message = "You cannot upload files of this type!";
        }
    } else {
        $message = "Please select an image to upload.";
    }
}
?>


<?php
$pageClass = 'inner-page';
include __DIR__ . '/includes/layout-start.php';
?>
    <style>
        .share-form-container {
            background: var(--cosmic-surface, #12101c);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid var(--cosmic-border, rgba(155, 77, 255, 0.18));
            max-width: 600px;
            margin: calc(var(--header-total) + 16px) auto;
        }
        .share-form-container h2 { color: var(--cosmic-violet); text-align: center; }
        .share-form-container .form-control { background: var(--cosmic-surface-2); border-color: var(--cosmic-border); color: var(--cosmic-text); }
    </style>

<div class="page-container share-form-container">
    <h2><?= e(t('share.title')) ?></h2>
    <p><?= e(t('share.desc')) ?></p>
    
    <?php if ($message !== ''): ?>
        <div class="alert"><?= $message ?></div>
    <?php endif; ?>

    <form action="share.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title"><?= e(t('share.photo_title')) ?></label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description"><?= e(t('share.description')) ?></label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="capture_date"><?= e(t('share.capture_date')) ?></label>
            <input type="date" class="form-control" id="capture_date" name="capture_date" required>
        </div>
        <div class="form-group">
            <label for="device"><?= e(t('share.device')) ?></label>
            <input type="text" class="form-control" id="device" name="device" required>
        </div>
        <div class="form-group">
            <label for="image"><?= e(t('share.select_image')) ?></label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn-cosmic btn-cosmic--primary"><?= e(t('share.submit')) ?></button>
    </form>
</div>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
