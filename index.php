<?php
require 'db_config.php';
// Database connection

$employeeName = $_GET['employee_name'] ?? '';
$eventName = $_GET['event_name'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';


$sql = "SELECT e.name as employee_name, ev.name as event_name, ev.version, p.fee, ev.date 
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

// Execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Event Participation</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-head bg-success text-light">
                <h1 class="text-light px-2 py-2">Filter Participations</h1>
            </div>
        </div>
        <div class="my-3">
            <a href="add_participation.php" class="btn btn-primary">Add New Participations</a>
            <div>
                <form method="GET">
                    <div class="row py-2">
                        <div class="col-3">
                            <label for="employee_name">Employee Name:</label>
                            <br>
                            <input type="text" name="employee_name" id="employee_name" class="form-control">
                        </div>

                        <div class="col-3">
                            <label for="event_name">Event Name:</label>
                            <br>
                            <input type="text" name="event_name" id="event_name" class="form-control">
                        </div>

                        <div class="col-3">
                            <label for="start_date">Start Date:</label>
                            <br>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-3">
                            <label for="end_date">End Date:</label>
                            <br>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row w-100 py-3">
            <div class="col-12">
                <table class="table w-100 table">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Event Name</th>
                            <th>Version</th>
                            <th>Fee</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalFee = 0; ?>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['employee_name']) ?></td>
                                <td><?= htmlspecialchars($row['event_name']) ?></td>
                                <td><?= htmlspecialchars($row['version']) ?></td>
                                <td><?= htmlspecialchars($row['fee']) ?></td>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                            </tr>
                            <?php $totalFee += $row['fee']; ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total Fee</td>
                            <td><?= htmlspecialchars($totalFee) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>