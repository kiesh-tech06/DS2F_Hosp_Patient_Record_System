<?php
// Login handler - checks email and password against the database
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if ($email === '' || $password === '') {
        $_SESSION['error'] = 'Please enter both your email and password.';
        header('Location: login.php');
        exit;
    }

    // Look up the user by email
    $stmt = $conn->prepare("SELECT user_id, full_name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();

    // Check the account exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email']     = $user['email'];
        header('Location: dashboard.php');
        exit;
    } else {
        // One message for both cases (safer)
        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: login.php');
        exit;
    }

} else {
    header('Location: login.php');
    exit;
}
?>

