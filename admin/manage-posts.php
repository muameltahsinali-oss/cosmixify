<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include '../db-conn.php';

// الحصول على جميع المنشورات من قاعدة البيانات
$sql = "SELECT * FROM photos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { margin-top: 50px; }
        img { max-width: 100%; height: auto; }
        .action-buttons { display: flex; gap: 10px; }
        @media (max-width: 768px) {
            table { font-size: 0.9rem; }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h2 class="text-center mb-4">Manage Posts</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?php echo $row['image']; ?>" width="100" height="100" /></td>
                            <td><?php echo $row['Title']; ?></td>
                            <td><?php echo substr($row['description'], 0, 100) . '...'; ?></td>
                            <td class="action-buttons">
                                <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                                <form action="delete_post.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No posts found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
