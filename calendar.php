<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

$query = "SELECT id, event_title, event_image, event_date, event_end, status FROM astronomical_events";
$result = $conn->query($query);
$events = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$pageClass = 'inner-page calendar-page';
include __DIR__ . '/includes/layout-start.php';
?>

<div class="calendar">
    <h2><?= e(t('event.calendar_title')) ?></h2>
    <div class="days">
        <?php
        $currentYear = date('Y');
        $currentMonth = date('m');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int) $currentMonth, (int) $currentYear);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dayFormatted = sprintf('%02d', $day);
            $date = "$currentYear-$currentMonth-$dayFormatted";
            $eventClass = '';
            $eventInfo = '';

            foreach ($events as $event) {
                if ($date >= $event['event_date'] && $date <= $event['event_end']) {
                    $eventClass = ($event['status'] === 'Coming') ? 'event-coming' : 'event-ended';
                    $eventInfo = '<strong>' . e($event['event_title']) . '</strong><br><a href="event-details.php?id=' . (int) $event['id'] . '">' . e(t('event.view_details')) . '</a>';
                    break;
                }
            }

            echo "<div class='day $eventClass'><span>$day</span><div class='event-details'>$eventInfo</div></div>";
        }
        ?>
    </div>
</div>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
