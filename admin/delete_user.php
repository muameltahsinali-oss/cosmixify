<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

require '../db-conn.php';
require_once '../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    csrf_verify();
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        $sql = 'DELETE FROM users WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            header('Location: manage-members.php?deleted=1');
            exit();
        }
        echo 'Error deleting user: ' . $conn->error;
        $stmt->close();
    }
}

$conn->close();
