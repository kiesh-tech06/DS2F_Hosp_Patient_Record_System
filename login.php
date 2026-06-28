<?php
// Login / Registration page
session_start();

//Already logged in? Go to Dashboard Page
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Grab any message from the handlers, then clear it
$error   = isset($_SESSION['error'])   ? $_SESSION['error']   : '';
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
unset($_SESSION['error'], $_SESSION['success']);

// Which tab to show
$tab = (isset($_GET['tab']) && $_GET['tab'] === 'register') ? 'register' : 'login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hospital Cares</title>

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!-- team's main stylesheet -->
    <link rel="stylesheet" href="style.css">
    <!-- extra styles just for the login page -->
    <link rel="stylesheet" href="auth_style.css">
</head>
<body>

    <!-- header section (same look as the rest of the site) -->
    <header>
        <a href="home.html" class="logo"><span>H</span>ospital <span>C</span>ares.</a>
        <nav class="navbar">
            <ul>
                <li><a href="home.html">home</a></li>
                <li><a href="home.html#about">about</a></li>
                <li><a href="form.html">appointment</a></li>
            </ul>
        </nav>
        <div class="fas fa-bars"></div>
    </header>

    <!-- login / register card -->
    <section class="auth-section">
        <div class="auth-box">

            <h1 class="auth-title"><span>W</span>elcome</h1>
            <p class="auth-subtitle">Sign in to manage patient &amp; appointment records</p>

            <?php if ($error): ?>
                <div class="auth-message error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="auth-message success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <!-- tabs -->
            <div class="auth-tabs">
                <button class="auth-tab <?php echo $tab === 'login' ? 'active' : ''; ?>"
                        id="tabLogin" onclick="showTab('login')">Sign In</button>
                <button class="auth-tab <?php echo $tab === 'register' ? 'active' : ''; ?>"
                        id="tabRegister" onclick="showTab('register')">Create Account</button>
            </div>

            <!-- sign in form -->
            <form class="auth-form <?php echo $tab === 'register' ? 'hidden' : ''; ?>"
                  id="loginForm" method="POST" action="login_process.php">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="sign in" class="auth-btn">
            </form>

            <!-- create account form -->
            <form class="auth-form <?php echo $tab === 'login' ? 'hidden' : ''; ?>"
                  id="registerForm" method="POST" action="register.php">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password (min. 6 characters)" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <input type="submit" value="create account" class="auth-btn">
            </form>

        </div>
    </section>

    <!-- jquery + team's navbar script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="app.js"></script>

    <!-- tab switching -->
    <script>
        function showTab(tab) {
            var loginForm = document.getElementById('loginForm');
            var registerForm = document.getElementById('registerForm');
            var tabLogin = document.getElementById('tabLogin');
            var tabRegister = document.getElementById('tabRegister');

            if (tab === 'register') {
                registerForm.classList.remove('hidden');
                loginForm.classList.add('hidden');
                tabRegister.classList.add('active');
                tabLogin.classList.remove('active');
            } else {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                tabLogin.classList.add('active');
                tabRegister.classList.remove('active');
            }
        }
    </script>
</body>
</html>

