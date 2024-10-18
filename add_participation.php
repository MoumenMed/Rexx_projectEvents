<?php
require 'db_config.php';


$employees = $pdo->query("SELECT id, name FROM employees")->fetchAll(PDO::FETCH_ASSOC);
$events = $pdo->query("SELECT id, name FROM events")->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = $_POST['employee_id'];
    $eventId = $_POST['event_id'];
    $fee = $_POST['fee'];


    $stmt = $pdo->prepare("INSERT INTO participations (employee_id, event_id, fee) VALUES (:employee_id, :event_id, :fee)");
    $stmt->execute([
        ':employee_id' => $employeeId,
        ':event_id' => $eventId,
        ':fee' => $fee
    ]);


    echo "New participation added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Participation</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-head bg-success ">
                <h1 class="text-light px-2 py-2">Add New Participation</h1>
            </div>
            <div class="card-body px-3">

                <a href="index.php" class="btn btn-danger my-3">List Participations</a>

                <form method="POST" action="">
                    <div class="row">

                        <div class="col-12 my-2">
                            <label for="employee_id" class="fw-bold">Employee:</label>
                            <br>
                            <select name="employee_id" id="employee_id" class="form-control" required>
                                <option value="">Select an Employee</option>
                                <?php foreach ($employees as $employee): ?>
                                    <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 my-2">
                            <label for="event_id" class="fw-bold">Event:</label>
                            <select name="event_id" id="event_id" class="form-control" required>
                                <option value="">Select an Event</option>
                                <?php foreach ($events as $event): ?>
                                    <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="col-12 my-2">
                            <label for="fee" class="fw-bold">Participation Fee:</label>
                            <input type="number" step="0.01" name="fee" id="fee" class="form-control" required>
                        </div>
                        <div class="col-12 my-2">
                            <button type="submit" class="btn btn-primary">Add Participation</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>