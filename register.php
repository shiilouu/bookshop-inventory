
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | BookNest</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="auth-body">

<div class="auth-container">
    <div class="auth-brand">
        <i class="fas fa-book-open"></i>
        <h1>BookNest</h1>
        <p>Bookshop Inventory Management System</p>
    </div>

    <div class="auth-card">
        <h2>Create an Account</h2>
        <p class="auth-subtitle">Fill in your information to get started.</p>

        <form action="" method="POST" id="registerForm">
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="full_name" placeholder="Enter your full name" required>
                </div>
            </div>

            <div class="form-group">
                <label>Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-at"></i>
                    <input type="text" name="username" placeholder="Choose a username" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="registerPassword" placeholder="Create a password" minlength="8" required>
                    <i class="fas fa-eye password-toggle" data-target="registerPassword"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm your password" required>
                    <i class="fas fa-eye password-toggle" data-target="confirmPassword"></i>
                </div>
            </div>

            <button type="submit" class="btn-primary full-width">Create Account</button>
        </form>

        <p class="auth-bottom">
            Already have an account? <a href="login.php">Sign in</a>
        </p>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
