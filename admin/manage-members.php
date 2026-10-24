<?php

session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php"); // إعادة توجيه إلى صفحة تسجيل الدخول إذا لم يكن هناك جلسة
    exit();
    
}
// الاتصال بقاعدة البيانات
include '../db-conn.php';
require_once '../includes/csrf.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Manage Users</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        .btn-primary {
            background-color: #6f42c1;
            /* اللون البنفسجي */
            border-color: #6f42c1;
        }

        .btn-primary:hover {
            background-color: #5a379b;
            /* لون أغمق عند التحويم */
            border-color: #5a379b;
        }
    </style>
</head>

<body>

<?php include 'navbar.php' ?>

    <div class="container">
        <h2 class="text-center mb-4">Manage Users</h2>

        <!-- Search Form -->
        <form id="searchForm" class="form-inline mb-4">
            <input type="text" id="searchInput" class="form-control mr-2" placeholder="Search by name or email">
            <button type="button" class="btn btn-primary" onclick="searchUsers()">Search</button>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Birth Date</th>
                        <th>Registration Date</th>
                        <th>Country</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <!-- سيتم تعبئة البيانات بواسطة PHP -->
                    <?php
                    // الاتصال بقاعدة البيانات (تأكد من أن الملف متصل بشكل صحيح)

                    $sql = "SELECT id, Fname, email, birthday, regdate, country FROM users";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["Fname"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["birthday"] . "</td>";
                            echo "<td>" . $row["regdate"] . "</td>";
                            echo "<td>" . $row["country"] . "</td>";
                            echo "<td>
                                    <form method='post' action='delete_user.php' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                                        " . csrf_field() . "
                                        <input type='hidden' name='id' value='" . (int)$row['id'] . "'>
                                        <input type='hidden' name='delete_user' value='1'>
                                        <button type='submit' class='btn btn-sm btn-danger'>Delete</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript -->
    <script>
        function searchUsers() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var rows = document.getElementById('userTable').getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var name = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
                var email = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
                
                if (name.indexOf(input) > -1 || email.indexOf(input) > -1) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    </script>
</body>

</html>
