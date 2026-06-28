<?php
// Dashboard - only logged-in users can see this
session_start();

// Guard: not logged in -> back to login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'User';
$email     = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hospital Cares</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="auth_style.css">
</head>
<body>

    <!-- header (same as the rest of the site) -->
    <header>
        <a href="home.html" class="logo"><span>H</span>ospital <span>C</span>ares.</a>
        <nav class="navbar">
            <ul>
                <li><a href="home.html">home</a></li>
                <li><a href="records.php">records</a></li>
                <li><a href="logout.php">logout</a></li>
            </ul>
        </nav>
        <div class="fas fa-bars"></div>
    </header>

    <!-- dashboard -->
    <section class="dash-section">
        <div class="dash-box">
            <h1>Welcome, <span><?php echo htmlspecialchars($full_name); ?></span></h1>
            <p>You are signed in as <strong><?php echo htmlspecialchars($email); ?></strong>.</p>
            <p>From here you can manage the patient and appointment records, or manage your account.</p>

            <div class="dash-actions">
                <a href="records.php" class="dash-btn-primary">go to records</a>
                <a href="logout.php" class="dash-btn-grey">logout</a>
                <button class="dash-btn-danger" onclick="openDeleteModal()">delete account</button>
            </div>
        </div>
    </section>

    <!-- delete confirmation modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <h3>Delete Account?</h3>
            <p>This will permanently remove your account. This action cannot be undone. Are you sure?</p>
            <form method="POST" action="delete_account.php" class="modal-actions">
                <button type="button" class="dash-btn-grey" onclick="closeDeleteModal()">cancel</button>
                <button type="submit" class="dash-btn-danger">yes, delete</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="app.js"></script>
    <script>
        function openDeleteModal()  { document.getElementById('deleteModal').classList.add('active'); }
        function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('active'); }
    </script>
</body>
</html>

