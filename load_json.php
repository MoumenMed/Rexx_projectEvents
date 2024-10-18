<?php
require 'db_config.php';  


$jsonData = file_get_contents('Events.json');
$data = json_decode($jsonData, true);

foreach ($data as $entry) {
    $employeeName = $entry['employee_name'];
    $employeeEmail = $entry['employee_mail'];
    $eventName = $entry['event_name'];
    $eventDate = $entry['event_date'];
    $fee = $entry['participation_fee'];
    $version = isset($entry['version']) ? $entry['version'] : null;

    
    $stmt = $pdo->prepare("INSERT INTO employees (name, email) VALUES (:name, :email) ON DUPLICATE KEY UPDATE email = email");
    $stmt->execute([':name' => $employeeName, ':email' => $employeeEmail]);
    $employeeId = $pdo->lastInsertId();

    
    $stmt = $pdo->prepare("INSERT INTO events (name, date, version) VALUES (:name, :date, :version) ON DUPLICATE KEY UPDATE date = date, version = version");
    $stmt->execute([':name' => $eventName, ':date' => $eventDate, ':version' => $version]);
    $eventId = $pdo->lastInsertId();

    
    $stmt = $pdo->prepare("INSERT INTO participations (employee_id, event_id, fee) VALUES (:employee_id, :event_id, :fee)");
    $stmt->execute([':employee_id' => $employeeId, ':event_id' => $eventId, ':fee' => $fee]);
}

echo "Data loaded successfully!";
?>
