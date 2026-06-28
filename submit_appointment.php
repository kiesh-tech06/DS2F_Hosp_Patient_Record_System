<?php
// Appointment request handler
// Saves a booking request from the public appointment form (form.html)
// into the appointment_requests table.
include 'db_connect.php';

// Only run when the form was actually submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect and trim the input from the form
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $message   = trim($_POST['message']);

    // Server-side validation (in case the browser checks are bypassed)
    if ($full_name === '' || $email === '' || $phone === '') {
        // Send the user back with an error flag in the URL
        header("Location: form.html?status=error");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: form.html?status=invalid");
        exit;
    }

    // Save the request using a prepared statement (prevents SQL injection)
    $stmt = $conn->prepare(
        "INSERT INTO appointment_requests (full_name, email, phone, message) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $full_name, $email, $phone, $message);

    if ($stmt->execute()) {
        $stmt->close();
        // Success - send back to the form with a success flag
        header("Location: form.html?status=success");
        exit;
    } else {
        $stmt->close();
        header("Location: form.html?status=error");
        exit;
    }

} else {
    // If somebody opens this file directly, just send them to the form
    header("Location: form.html");
    exit;
}
?>

