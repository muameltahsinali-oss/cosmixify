<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

require '../db-conn.php';
require_once '../config.php';

if (empty(NASA_API_KEY)) {
    die('NASA API key is not configured. Set NASA_API_KEY in config.php');
}

$api_url = 'https://api.nasa.gov/EPIC/api/natural/images?api_key=' . NASA_API_KEY;
$response = file_get_contents($api_url);
$data = json_decode($response, true);

if (!empty($data)) {
    $check_stmt = $conn->prepare('SELECT id FROM apod WHERE date = ?');
    $insert_stmt = $conn->prepare(
        "INSERT INTO apod (image, category, postDate, date, sourceUrl, source, description, title, url)
         VALUES (?, 'earth', NOW(), ?, ?, 'NASA EPIC', ?, 'Earth Image', ?)"
    );

    foreach ($data as $item) {
        $image = $item['image'];
        $date = $item['date'];
        $caption = $item['caption'];
        $formatted_date = date('Y/m/d', strtotime($date));
        $image_url = 'https://epic.gsfc.nasa.gov/archive/natural/' . $formatted_date . '/jpg/' . $image . '.jpg';

        $check_stmt->bind_param('s', $date);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows === 0) {
            $insert_stmt->bind_param('sssss', $image_url, $date, $image_url, $caption, $image_url);

            if ($insert_stmt->execute()) {
                echo 'Record inserted successfully for ' . htmlspecialchars($date) . '.<br>';
            } else {
                echo 'Error inserting record: ' . $insert_stmt->error . '<br>';
            }
        } else {
            echo 'Record for ' . htmlspecialchars($date) . ' already exists.<br>';
        }
        $check_stmt->free_result();
    }

    $check_stmt->close();
    $insert_stmt->close();
} else {
    echo 'No data found from NASA EPIC API.';
}

$conn->close();
