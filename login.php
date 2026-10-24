<?php
session_start(); // بدء الجلسة

include 'db-conn.php'; // تضمين ملف الاتصال بقاعدة البيانات

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // جلب بيانات المستخدم من قاعدة البيانات
    $sql = "SELECT id, email, password, Fname, Lname, birthday, country FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // التحقق من صحة كلمة المرور
        if (password_verify($password, $user['password'])) {
            // حفظ معلومات المستخدم في الجلسة
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['Fname'] = $user['Fname'];
            $_SESSION['Lname'] = $user['Lname'];
            $_SESSION['birthday'] = $user['birthday'];
            $_SESSION['country'] = $user['country'];

            // إعادة التوجيه إلى الصفحة الرئيسية بعد تسجيل الدخول بنجاح
            header("Location: index.php");
            exit();
        } else {
            $error = "كلمة المرور غير صحيحة.";
        }
    } else {
        $error = "البريد الإلكتروني غير موجود.";
    }

    $stmt->close();
    $conn->close();
}
?>
