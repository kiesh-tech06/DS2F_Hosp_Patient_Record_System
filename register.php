<?php
// Registration handler - creates a new login account
// Uses the same db_connect.php the rest of the team uses
session_start();
include 'db_connect.php';

// Only run when the register form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect the form input
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    // Validate the input, with clear error messages
    if ($full_name === '' || $email === '' || $password === '' || $confirm === '') {
        $_SESSION['error'] = 'Please fill in all fields.';
        header('Location: login.php?tab=register');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Please enter a valid email address.';
        header('Location: login.php?tab=register');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Password must be at least 6 characters long.';
        header('Location: login.php?tab=register');
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: login.php?tab=register');
        exit;
    }

    // Check whether the email is already registered
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = 'This email is already registered. Please sign in.';
        $stmt->close();
        header('Location: login.php?tab=register');
        exit;
    }
    $stmt->close();

    // Hash the password so it is never stored as plain text
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $full_name, $email, $hashed);

    if ($stmt->execute()) {
        // Account created - log them in straight away
        $_SESSION['user_id']   = $stmt->insert_id;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['email']     = $email;
        $stmt->close();
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        $stmt->close();
        header('Location: login.php?tab=register');
        exit;
    }

} else {
    // If somebody opens this file directly, send them to the login page
    header('Location: login.php');
    exit;
}
?>

