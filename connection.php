<?php

$pdo = new PDO('mysql:host=localhost;dbname=event_management', 'root', '');


$jsonData = file_get_contents('Events.json');
$data = json_decode($jsonData, true);

foreach ($data as $entry) {
    $employeeName = $entry['employee_name'];
    $employeeEmail = $entry['employee_mail'];
    $eventName = $entry['event_name'];
    $eventDate = $entry['event_date'];
    $fee = $entry['participation_fee'];

    
    $stmt = $pdo->prepare("INSERT INTO employees (name, email) VALUES (:name, :email) ON DUPLICATE KEY UPDATE email = email");
    $stmt->execute([':name' => $employeeName, ':email' => $employeeEmail]);
    $employeeId = $pdo->lastInsertId();

    
    $stmt = $pdo->prepare("INSERT INTO events (name, date) VALUES (:name, :date) ON DUPLICATE KEY UPDATE date = date");
    $stmt->execute([':name' => $eventName, ':date' => $eventDate]);
    $eventId = $pdo->lastInsertId();

    
    $stmt = $pdo->prepare("INSERT INTO participations (employee_id, event_id, fee) VALUES (:employee_id, :event_id, :fee)");
    $stmt->execute([':employee_id' => $employeeId, ':event_id' => $eventId, ':fee' => $fee]);
}




$employeeName = $_GET['employee_name'] ?? '';
$eventName = $_GET['event_name'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';


$sql = "SELECT e.name as employee_name, ev.name as event_name, p.fee, ev.date 
        FROM participations p
        JOIN employees e ON p.employee_id = e.id
        JOIN events ev ON p.event_id = ev.id
        WHERE 1=1";

$params = [];
if (!empty($employeeName)) {
    $sql .= " AND e.name LIKE :employee_name";
    $params[':employee_name'] = '%' . $employeeName . '%';
}
if (!empty($eventName)) {
    $sql .= " AND ev.name LIKE :event_name";
    $params[':event_name'] = '%' . $eventName . '%';
}
if (!empty($startDate)) {
    $sql .= " AND ev.date >= :start_date";
    $params[':start_date'] = $startDate;
}
if (!empty($endDate)) {
    $sql .= " AND ev.date <= :end_date";
    $params[':end_date'] = $endDate;
}


$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>































































?>
