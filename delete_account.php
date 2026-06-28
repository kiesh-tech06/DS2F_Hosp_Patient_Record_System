<?php
// Delete account handler - permanently removes the logged-in user
session_start();
include 'db_connect.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Only proceed when the confirm form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = intval($_SESSION['user_id']);

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        // Log them out
        $_SESSION = array();
        session_destroy();
        // Start a fresh session just to show one goodbye message
        session_start();
        $_SESSION['success'] = 'Your account has been permanently deleted.';
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['error'] = 'Could not delete your account. Please try again.';
        $stmt->close();
        header('Location: dashboard.php');
        exit;
    }

} else {
    header('Location: dashboard.php');
    exit;
}
?>

