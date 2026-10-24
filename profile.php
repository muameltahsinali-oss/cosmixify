<?php
require_once __DIR__ . '/includes/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login-register.php');
    exit();
}

require 'db-conn.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare('
    SELECT favs.photo_id, photos.image, photos.Title, photos.description
    FROM favs
    JOIN photos ON favs.photo_id = photos.id
    WHERE favs.user_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$favs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt = $conn->prepare('SELECT id, Title, description, image, accept FROM user_photos WHERE user_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_photos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $allowed = ALLOWED_IMAGE_EXTENSIONS;
    $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed, true) && $_FILES['profile_image']['size'] <= UPLOAD_MAX_SIZE) {
        $filename = uniqid('profile_', true) . '.' . $ext;
        $target_file = 'uploads/' . $filename;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $db_path = 'uploads/' . $filename;
            $stmt = $conn->prepare('UPDATE users SET profile_image = ? WHERE id = ?');
            $stmt->bind_param('si', $db_path, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header('Location: profile.php');
    exit();
}

if (isset($_POST['update_info'])) {
    $fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $email = $_POST['email'];
    $stmt = $conn->prepare('UPDATE users SET Fname = ?, Lname = ?, email = ? WHERE id = ?');
    $stmt->bind_param('sssi', $fname, $Lname, $email, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: profile.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login-register.php');
    exit();
}

$pageClass = 'profile-page';
$navMode = 'profile';
include __DIR__ . '/includes/layout-start.php';
?>

<div class="page-container profile-container">
    <div class="profile-header">
        <div class="profile-image">
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <label for="profile_image">
                    <img src="<?= e($user['profile_image'] ?? 'uploads/default.jpg') ?>" alt="">
                </label>
                <input type="file" name="profile_image" id="profile_image" style="display:none;" onchange="this.form.submit();">
            </form>
        </div>
        <h1 class="user-name"><?= e($user['Fname'] . ' ' . $user['Lname']) ?></h1>
        <form action="profile.php" method="post">
            <div class="profile-buttons">
                <button type="submit" name="logout" class="btn-cosmic btn-cosmic--ghost"><?= e(t('profile.logout')) ?></button>
                <button type="button" class="btn-cosmic btn-cosmic--ghost" onclick="document.getElementById('edit-info').style.display='block'"><?= e(t('profile.edit_info')) ?></button>
                <a href="share.php" class="btn-cosmic btn-cosmic--primary"><?= e(t('profile.share_new')) ?></a>
            </div>
        </form>
    </div>

    <div id="edit-info" style="display:none; margin-bottom:2rem;">
        <form action="profile.php" method="post">
            <input type="text" name="Fname" value="<?= e($user['Fname']) ?>" required>
            <input type="text" name="Lname" value="<?= e($user['Lname']) ?>" required>
            <input type="email" name="email" value="<?= e($user['email']) ?>" required>
            <button class="btn-cosmic btn-cosmic--primary" type="submit" name="update_info"><?= e(t('profile.save_changes')) ?></button>
        </form>
    </div>

    <h2 id="Shares"><?= e(t('profile.your_submissions')) ?></h2>
    <div class="gallery-image">
        <?php foreach ($user_photos as $photo): ?>
            <div class="img-box">
                <a href="user_photo_details.php?id=<?= (int) $photo['id'] ?>">
                    <img src="<?= e(user_photo_path($photo['image'])) ?>" alt="">
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 id="Favorites"><?= e(t('profile.favorites')) ?></h2>
    <div class="gallery-image">
        <?php foreach ($favs as $fav): ?>
            <div class="img-box">
                <a href="photo-details.php?id=<?= (int) $fav['photo_id'] ?>">
                    <img src="<?= e(asset_path($fav['image'])) ?>" alt="">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
