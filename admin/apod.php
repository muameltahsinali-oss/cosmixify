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

set_time_limit(600);

$apiKey = NASA_API_KEY;
$baseUrl = "https://api.nasa.gov/planetary/apod?api_key={$apiKey}&date=";

$days = 30;
$today = new DateTime();
$interval = new DateInterval('P1D');
$period = new DatePeriod($today->sub(new DateInterval("P{$days}D")), $interval, $days);

$check_stmt = $conn->prepare('SELECT id FROM apod WHERE date = ?');
$insert_stmt = $conn->prepare(
    'INSERT INTO apod (title, explanation, url, date) VALUES (?, ?, ?, ?)'
);

foreach ($period as $date) {
    $formattedDate = $date->format('Y-m-d');
    $apiUrl = $baseUrl . $formattedDate;
    $response = @file_get_contents($apiUrl);

    if ($response === false) {
        echo "Failed to fetch APOD data for $formattedDate.<br>";
        continue;
    }

    $data = json_decode($response, true);

    if (isset($data['title'], $data['explanation'], $data['url'], $data['date'])) {
        $title = $data['title'];
        $explanation = $data['explanation'];
        $url = $data['url'];
        $apodDate = $data['date'];

        $check_stmt->bind_param('s', $apodDate);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows === 0) {
            $insert_stmt->bind_param('ssss', $title, $explanation, $url, $apodDate);
            if ($insert_stmt->execute()) {
                echo "Data for $apodDate saved successfully!<br>";
            } else {
                echo 'Error: ' . $insert_stmt->error . '<br>';
            }
        } else {
            echo "Data for $apodDate already exists!<br>";
        }
        $check_stmt->free_result();
    } else {
        echo "Incomplete data for $formattedDate.<br>";
    }
}

$check_stmt->close();
$insert_stmt->close();
$conn->close();
