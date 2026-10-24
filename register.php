
<?php
include 'db-conn.php'; // تضمين ملف الاتصال بقاعدة البيانات
session_start(); // بدء الجلسة

// التحقق من طريقة الطلب
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['Fname'];
    $last_name = $_POST['Lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $country = $_POST['country'];
    $birth_date = $_POST['birthday'];

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // التحقق من عدم وجود المستخدم مسبقًا
    $check_user_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // إدخال المستخدم الجديد إلى قاعدة البيانات
        $insert_query = "INSERT INTO users (Fname, Lname, email, password, country, birthday) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $hashed_password, $country, $birth_date);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_email'] = $email;

            // إعادة التوجيه إلى الصفحة الرئيسية بعد النجاح
            header("Location: index.php");
            exit();
        } else {
            $error = "An error occurred please try again later ";
        }
    } else {
        $error = "البريد الإلكتروني مستخدم مسبقًا.";
    }

    $stmt->close();
    $conn->close();
}
?>