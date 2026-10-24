<?php
/**
 * Sync NASA APOD entries into the apod table.
 * Usage: php database/sync-apod.php [days]
 * Uses NASA_API_KEY from config.php, or DEMO_KEY as fallback.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db-conn.php';

$days = isset($argv[1]) ? max(1, min(30, (int) $argv[1])) : 14;
$apiKey = NASA_API_KEY !== '' ? NASA_API_KEY : 'DEMO_KEY';

echo "Syncing last {$days} days of NASA APOD...\n";

$check = $conn->prepare('SELECT id FROM apod WHERE date = ?');
$insert = $conn->prepare(
    'INSERT INTO apod (title, explanation, url, date, source, sourceUrl)
     VALUES (?, ?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE title = VALUES(title), explanation = VALUES(explanation), url = VALUES(url)'
);

$synced = 0;
$today = new DateTime('today', new DateTimeZone('UTC'));

for ($i = 0; $i < $days; $i++) {
    $date = (clone $today)->modify("-{$i} days")->format('Y-m-d');
    $url = "https://api.nasa.gov/planetary/apod?api_key={$apiKey}&date={$date}";

    $ctx = stream_context_create(['http' => ['timeout' => 15]]);
    $response = @file_get_contents($url, false, $ctx);

    if ($response === false) {
        echo "  Skip {$date}: request failed\n";
        sleep(1);
        continue;
    }

    $data = json_decode($response, true);

    if (!isset($data['title'], $data['explanation'], $data['date'])) {
        echo "  Skip {$date}: invalid response\n";
        sleep(1);
        continue;
    }

    $imageUrl = $data['url'] ?? ($data['hdurl'] ?? '');
    if ($imageUrl === '' || ($data['media_type'] ?? 'image') !== 'image') {
        echo "  Skip {$date}: not an image APOD\n";
        continue;
    }

    $sourceUrl = 'https://apod.nasa.gov/apod/ap' . date('ymd', strtotime($data['date'])) . '.html';
    $source = 'NASA APOD';
    $title = $data['title'];
    $explanation = $data['explanation'];
    $apodDate = $data['date'];

    $insert->bind_param('ssssss', $title, $explanation, $imageUrl, $apodDate, $source, $sourceUrl);

    if ($insert->execute()) {
        echo "  OK {$apodDate}: {$title}\n";
        $synced++;
    }

    usleep(350000);
}

$check->close();
$insert->close();
$conn->close();

echo "Done. Synced {$synced} APOD entries.\n";
