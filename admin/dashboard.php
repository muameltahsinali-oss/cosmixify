<?php

session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php"); // إعادة توجيه إلى صفحة تسجيل الدخول إذا لم يكن هناك جلسة
    exit();
    
}
// الاتصال بقاعدة البيانات
include '../db-conn.php';

// جلب عدد الأحداث الفلكية
$result_events = $conn->query("SELECT COUNT(id) AS total_events FROM astronomical_events");
$row_events = $result_events->fetch_assoc();
$total_events = $row_events['total_events'];

// جلب عدد المنشورات
$result_posts = $conn->query("SELECT COUNT(id) AS total_posts FROM photos");
$row_posts = $result_posts->fetch_assoc();
$total_posts = $row_posts['total_posts'];

// جلب عدد المستخدمين
$result_users = $conn->query("SELECT COUNT(id) AS total_users FROM users");
$row_users = $result_users->fetch_assoc();
$total_users = $row_users['total_users'];

// جلب عدد المستخدمين الجدد في الأسبوع الأخير
$result_new_users = $conn->query("SELECT COUNT(id) AS new_users FROM users WHERE regdate >= NOW() - INTERVAL 7 DAY");
$row_new_users = $result_new_users->fetch_assoc();
$new_users = $row_new_users['new_users'];

// جلب عدد المنشورات الجديدة في الشهر الأخير
$result_new_posts = $conn->query("SELECT COUNT(id) AS new_posts FROM photos WHERE postDate >= NOW() - INTERVAL 1 MONTH");
$row_new_posts = $result_new_posts->fetch_assoc();
$new_posts = $row_new_posts['new_posts'];

// جلب آخر 5 منشورات
$recent_posts = $conn->query("SELECT Title, postDate FROM photos ORDER BY postDate DESC LIMIT 5");

// جلب آخر 5 أحداث فلكية
$recent_events = $conn->query("SELECT event_title, event_date FROM astronomical_events ORDER BY event_date DESC LIMIT 5");

// جلب عدد الزيارات الكلي
$result_total_visits = $conn->query("SELECT COUNT(id) AS total_visits FROM site_visits");
$row_total_visits = $result_total_visits->fetch_assoc();
$total_visits = $row_total_visits['total_visits'];

// جلب عدد الزيارات هذا الشهر
$result_monthly_visits = $conn->query("
    SELECT COUNT(id) AS monthly_visits 
    FROM site_visits 
    WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())");
$row_monthly_visits = $result_monthly_visits->fetch_assoc();
$monthly_visits = $row_monthly_visits['monthly_visits'];
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - الفضاء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d1b2a;
            --secondary-color: #1b263b;
            --accent-color: #415a77;
            --highlight-color: #e0e1dd;
            --text-color: #fff;
            --card-bg: #283046;
            --card-border: #3e4e6c;
            --success-color: #4caf50;
            --warning-color: #ff9800;
        }

        body {
            background-color: var(--primary-color);
            color: var(--text-color);
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
        }

        h2, h3, p {
            color: var(--highlight-color);
        }

        #sidebar-wrapper {
            background-color: var(--secondary-color);
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: -250px;
            transition: left 0.3s ease;
            z-index: 1000;
        }

        #sidebar-wrapper.toggled {
            left: 0;
        }

        .list-group-item {
            background-color: transparent;
            color: var(--highlight-color);
            border: none;
            padding: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .list-group-item:hover {
            background-color: var(--accent-color);
            color: #fff;
            transform: translateX(5px);
        }

        .navbar {
            background-color: var(--secondary-color);
            position: relative;
            z-index: 999;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        #menu-toggle {
            cursor: pointer;
        }

        #page-content-wrapper {
            background-color: var(--secondary-color);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            padding: 2rem;
            margin-left: 0;
        }

        #page-content-wrapper.toggled {
            margin-left: 250px;
        }

        .info-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2rem;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 1.5rem;
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 6px 25px rgba(0, 0, 0, 0.2);
        }

        .icon-container {
            background-color: var(--accent-color);
            padding: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-container.success {
            background-color: var(--success-color);
        }

        .icon-container.warning {
            background-color: var(--warning-color);
        }

        .icon-container i {
            font-size: 2rem;
            color: var(--text-color);
        }

        /* Latest Posts and Events */
        .latest-section {
            background-color: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            color: var(--highlight-color);
        }

        .latest-section h3 {
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .latest-section ul {
            list-style-type: none;
            padding: 0;
        }

        .latest-section ul li {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            #sidebar-wrapper {
                left: -250px;
            }

            #page-content-wrapper {
                margin-left: 0;
            }

            .info-card {
                margin-bottom: 1.5rem;
                padding: 1.5rem;
            }

            .info-card h3 {
                font-size: 2rem;
            }

            .info-card p {
                font-size: 1.1rem;
            }

            .icon-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 fs-4 fw-bold text-uppercase border-bottom">لوحة التحكم</div>
            <div class="list-group list-group-flush my-3">
                <a href="dashboard.php" class="list-group-item second-text"><i class="fas fa-tachometer-alt me-2"></i>الإحصائيات</a>
                <a href="add-post.php" class="list-group-item second-text"><i class="fas fa-plus-circle me-2"></i>إضافة منشور</a>
                <a href="manage-posts.php" class="list-group-item second-text"><i class="fas fa-edit me-2"></i>إدارة المنشورات</a>
                <a href="add-event.php" class="list-group-item second-text"><i class="fas fa-calendar-plus me-2"></i>إضافة حدث فلكي</a>
                <a href="show-events.php" class="list-group-item second-text"><i class="fas fa-calendar-alt me-2"></i>عرض الأحداث الفلكية</a>
                <a href="manage-members.php" class="list-group-item second-text"><i class="fas fa-users me-2"></i>إدارة الأعضاء</a>
                <a href="users_photos.php" class="list-group-item second-text"><i class="fas fa-images me-2"></i>صور المجتمع</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left fs-4" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">الإحصائيات</h2>
                </div>
            </nav>
            <div class="container-fluid px-4">
                <div class="row g-3 my-2">
                    <div class="col-md-3">
                        <div class="info-card">
                            <div>
                                <h3><?= $total_visits ?></h3>
                                <p>الزيارات الكلية</p>
                            </div>
                            <div class="icon-container">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-card">
                            <div>
                                <h3><?= $monthly_visits ?></h3>
                                <p>زيارات هذا الشهر</p>
                            </div>
                            <div class="icon-container">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-card">
                            <div>
                                <h3><?= $total_users ?></h3>
                                <p>عدد المستخدمين</p>
                            </div>
                            <div class="icon-container success">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="info-card">
                            <div>
                                <h3><?= $new_users ?></h3>
                                <p>اعضاء جدد خلال هذه الاسبوع
                                </p>
                            </div>
                            <div class="icon-container success">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="info-card">
                            <div>
                                <h3><?= $new_posts ?></h3>
                                <p>منشورات جديدة خلال الشهر الأخير</p>
                            </div>
                            <div class="icon-container warning">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Latest Posts Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="latest-section">
                            <h3>آخر المنشورات</h3>
                            <ul>
                                <?php while ($post = $recent_posts->fetch_assoc()) : ?>
                                    <li><?= htmlspecialchars($post['Title']) ?> - <small><?= $post['postDate'] ?></small></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Latest Events Section -->
                    <div class="col-md-6">
                        <div class="latest-section">
                            <h3>آخر الأحداث الفلكية</h3>
                            <ul>
                                <?php while ($event = $recent_events->fetch_assoc()) : ?>
                                    <li><?= $event['event_title'] ?> - <small><?= $event['event_date'] ?></small></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
    // Toggle Sidebar
    document.getElementById("menu-toggle").addEventListener("click", function() {
        const sidebar = document.getElementById("sidebar-wrapper");
        const pageContent = document.getElementById("page-content-wrapper");

        sidebar.classList.toggle("toggled");
        pageContent.classList.toggle("toggled");
    });
    </script>
</body>
</html>
