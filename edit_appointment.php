<?php
include 'db_connect.php';

$id = intval($_GET['id']);   // which appointment are we editing?

// If the form was submitted, save the changes (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = intval($_POST['patient_id']);
    $doctor     = $_POST['doctor_name'];
    $date       = $_POST['appointment_date'];
    $time       = $_POST['appointment_time'];
    $status     = $_POST['status'];

    $stmt = $conn->prepare(
        "UPDATE appointments SET patient_id=?, doctor_name=?, appointment_date=?, appointment_time=?, status=? WHERE appointment_id=?"
    );
    $stmt->bind_param("issssi", $patient_id, $doctor, $date, $time, $status, $id);
    $stmt->execute();

    header("Location: records.php");
    exit;
}

// Otherwise, fetch the current details to fill the form
$stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$appt = $stmt->get_result()->fetch_assoc();

if (!$appt) {
    die("Appointment not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
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
    <h1>Edit Appointment Record</h1>

    <form method="POST">
        <label>Patient ID</label>
        <input type="number" name="patient_id" value="<?php echo $appt['patient_id']; ?>" required>

        <label>Doctor Name</label>
        <input type="text" name="doctor_name" value="<?php echo $appt['doctor_name']; ?>" required>

        <label>Appointment Date</label>
        <input type="date" name="appointment_date" value="<?php echo $appt['appointment_date']; ?>" required>

        <label>Appointment Time</label>
        <input type="time" name="appointment_time" value="<?php echo $appt['appointment_time']; ?>" required>

        <label>Status</label>
        <select name="status">
            <option <?php if ($appt['status']=='Confirmed') echo 'selected'; ?>>Confirmed</option>
            <option <?php if ($appt['status']=='Pending')   echo 'selected'; ?>>Pending</option>
            <option <?php if ($appt['status']=='Completed') echo 'selected'; ?>>Completed</option>
            <option <?php if ($appt['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>

        <button type="submit">Save Changes</button>
        <a href="records.php">Cancel</a>
    </form>
</body>
</html>