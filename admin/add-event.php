
<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

require '../db-conn.php'; // الاتصال بقاعدة البيانات

// معالجة إضافة الحدث
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_title = $_POST['event_title'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_end_date = $_POST['event_end_date']; // تاريخ انتهاء الحدث
    $best_viewing_method = $_POST['best_viewing_method']; // أفضل طريقة للمشاهدة
    $light_pollution_viewability = $_POST['light_pollution_viewability']; // امكانية المشاهدة من مناطق التلوث الضوئي
    $best_viewing_location = $_POST['best_viewing_location']; // أفضل مناطق المشاهدة
    
    // معالجة رفع الصورة
    $target_dir = "../uploads/events/";
    $target_file = $target_dir . basename($_FILES["event_image"]["name"]);
    
    if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
        $db_image_path = 'uploads/events/' . basename($_FILES['event_image']['name']);
        $stmt = $conn->prepare('INSERT INTO astronomical_events (event_title, event_description, event_image, event_date, event_end, status, best_viewing_method, light_pollution_viewability, best_viewing_location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $status = 'Coming';
        $stmt->bind_param('sssssssss', $event_title, $event_description, $db_image_path, $event_date, $event_end_date, $status, $best_viewing_method, $light_pollution_viewability, $best_viewing_location);
        $stmt->execute();
        $stmt->close();
        
        $success_message = "Event added successfully!";
    } else {
        $error_message = "Sorry, there was an error uploading the image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Astronomical Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="files/css/dashboard.css" />
    <?php include '../styles.php'; ?>
    <!-- تضمين CKEditor -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>
<body>
<?php include 'navbar.php' ?>

<div class="container">
    <h2 class="text-center">Add New Astronomical Event</h2>
    
    <?php if (isset($success_message)) echo '<p class="text-success">' . $success_message . '</p>'; ?>
    <?php if (isset($error_message)) echo '<p class="text-danger">' . $error_message . '</p>'; ?>

    <form action="add-event.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="event_title">Event Title:</label>
            <input type="text" class="form-control" id="event_title" name="event_title" required>
        </div>
        <div class="form-group">
            <label for="event_description">Event Description:</label>
            <textarea class="form-control" id="event_description" name="event_description" rows="10" required></textarea>
            <script>
                // تهيئة CKEditor على textarea
                CKEDITOR.replace('event_description');
            </script>
        </div>
        <div class="form-group">
            <label for="event_date">Event Start Date:</label>
            <input type="date" class="form-control" id="event_date" name="event_date" required>
        </div>
        <div class="form-group">
            <label for="event_end_date">Event End Date:</label>
            <input type="date" class="form-control" id="event_end_date" name="event_end_date" required>
        </div>

        <!-- حقل افضل طريقة للمشاهدة -->
        <div class="form-group">
            <label for="best_viewing_method">Best Viewing Method:</label>
            <select class="form-control" id="best_viewing_method" name="best_viewing_method" required>
                <option value="Eye">Eye</option>
                <option value="Telescope">Telescope</option>
                <option value="Binoculars">Binoculars</option>
                <option value="Camera">Camera</option>
            </select>
        </div>

        <!-- حقل امكانية المشاهدة من مناطق التلوث الضوئي -->
        <div class="form-group">
            <label for="light_pollution_viewability">Viewable from Light Pollution Areas:</label>
            <select class="form-control" id="light_pollution_viewability" name="light_pollution_viewability" required>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
                <option value="Uncertain">Uncertain</option>
            </select>
        </div>

        <!-- حقل افضل مناطق المشاهدة -->
        <div class="form-group">
            <label for="best_viewing_location">Best Viewing Location:</label>
            <input type="text" class="form-control" id="best_viewing_location" name="best_viewing_location" required>
        </div>

        <div class="form-group">
            <label for="event_image">Event Image:</label>
            <input type="file" class="form-control-file" id="event_image" name="event_image" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
