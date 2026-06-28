<?php
// Logout handler - ends the session and returns to the login page
session_start();

$_SESSION = array();   // clear all session data
session_destroy();     // destroy the session

header('Location: login.php');
exit;
?>

