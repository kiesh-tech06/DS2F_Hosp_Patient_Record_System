<?php
include 'db_connect.php';

// If the form was submitted, insert the new patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $_POST['full_name'];
    $age     = intval($_POST['age']);
    $gender  = $_POST['gender'];
    $contact = $_POST['contact'];
    $email   = $_POST['email'];

    $stmt = $conn->prepare(
        "INSERT INTO patients (full_name, age, gender, contact, email) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sisss", $name, $age, $gender, $contact, $email);
    $stmt->execute();

    header("Location: records.php");   // done, back to the list
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Patient</title>
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
    <h1>Add New Patient</h1>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="full_name" required>

        <label>Age</label>
        <input type="number" name="age" required>

        <label>Gender</label>
        <select name="gender">
            <option>Male</option>
            <option>Female</option>
        </select>

        <label>Contact</label>
        <input type="text" name="contact">

        <label>Email</label>
        <input type="email" name="email">

        <button type="submit">Add Patient</button>
        <a href="records.php">Cancel</a>
    </form>
</body>
</html>
