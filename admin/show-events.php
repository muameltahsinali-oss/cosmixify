<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

require '../db-conn.php';
require_once '../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    if (isset($_POST['delete_id'])) {
        $event_id = (int) $_POST['delete_id'];
        $stmt = $conn->prepare('DELETE FROM astronomical_events WHERE id = ?');
        $stmt->bind_param('i', $event_id);
        $stmt->execute();
        $stmt->close();
        $success_message = 'Event deleted successfully!';
    }

    if (isset($_POST['toggle_status_id'])) {
        $event_id = (int) $_POST['toggle_status_id'];

        $stmt = $conn->prepare('SELECT status FROM astronomical_events WHERE id = ?');
        $stmt->bind_param('i', $event_id);
        $stmt->execute();
        $stmt->bind_result($current_status);
        $stmt->fetch();
        $stmt->close();

        $new_status = ($current_status === 'Coming') ? 'Ended' : 'Coming';

        $stmt = $conn->prepare('UPDATE astronomical_events SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $new_status, $event_id);
        $stmt->execute();
        $stmt->close();
        $success_message = 'Event status updated successfully!';
    }
}

// Legacy GET support redirect
if (isset($_GET['delete_id']) || isset($_GET['toggle_status_id'])) {
    header('Location: show-events.php');
    exit();
}

// جلب جميع الأحداث
$stmt = $conn->prepare('SELECT id, event_title, event_description, event_date, event_end, status FROM astronomical_events');
$stmt->execute();
$stmt->bind_result($id, $title, $description, $start_date, $end_date, $status);
$events = [];
while ($stmt->fetch()) {
    $events[] = ['id' => $id, 'title' => $title, 'description' => $description, 'start_date' => $start_date, 'end_date' => $end_date, 'status' => $status];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Astronomical Events</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="files/css/dashboard.css" />
    <?php include '../styles.php'; ?>
</head>
<body>
<?php include 'navbar.php' ?>

<div class="container">
    <h2 class="text-center">Manage Astronomical Events</h2>
    
    <?php if (isset($success_message)) echo '<p class="text-success">' . $success_message . '</p>'; ?>

    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo $event['id']; ?></td>
                <td><?php echo $event['title']; ?></td>
                <td><?php echo $event['description']; ?></td>
                <td><?php echo $event['start_date']; ?></td>
                <td><?php echo $event['end_date']; ?></td>
                <td><?php echo $event['status']; ?></td>
                <td>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this event?');">
                        <?php require_once '../includes/csrf.php'; echo csrf_field(); ?>
                        <input type="hidden" name="delete_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="toggle_status_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" class="btn btn-warning btn-sm">
                            <?php echo ($event['status'] == 'Coming') ? 'Mark as Ended' : 'Mark as Coming'; ?>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
