<?php
include 'db_connect.php';

// If the form was submitted, insert the new appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = intval($_POST['patient_id']);
    $doctor     = $_POST['doctor_name'];
    $date       = $_POST['appointment_date'];
    $time       = $_POST['appointment_time'];
    $status     = $_POST['status'];

    $stmt = $conn->prepare(
        "INSERT INTO appointments (patient_id, doctor_name, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("issss", $patient_id, $doctor, $date, $time, $status);
    $stmt->execute();

    header("Location: records.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Appointment</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        body { font-family: "Roboto", Arial, sans-serif; margin: 30px; color: #444d53; }
        h1 { color: #0188df; text-transform: capitalize; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select { padding: 8px; width: 280px; }
        button { margin-top: 20px; padding: 10px 18px; background: #0188df;
                 color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Add New Appointment</h1>

    <form method="POST">
        <label>Patient ID</label>
        <input type="number" name="patient_id" required>

        <label>Doctor Name</label>
        <input type="text" name="doctor_name" required>

        <label>Appointment Date</label>
        <input type="date" name="appointment_date" required>

        <label>Appointment Time</label>
        <input type="time" name="appointment_time" required>

        <label>Status</label>
        <select name="status">
            <option>Confirmed</option>
            <option>Pending</option>
            <option>Completed</option>
            <option>Cancelled</option>
        </select>

        <button type="submit">Add Appointment</button>
        <a href="records.php">Cancel</a>
    </form>
</body>
</html>
