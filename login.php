<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/db.php';

if (!empty($_SESSION['user_id']) && !empty($_SESSION['role'])) {
    $redirectPage = ($_SESSION['role'] === 'admin')
        ? 'modules/dashboard/admin-dashboard.php'
        : 'index.php';
    header('Location: ' . $redirectPage);
    exit();
}

$username = '';
$loginErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $rawPassword = $_POST['password'] ?? '';

    if ($username === '') {
        $loginErrors[] = 'Please enter your username.';
    }

    if ($rawPassword === '') {
        $loginErrors[] = 'Please enter your password.';
    }

    if (empty($loginErrors)) {
        $stmt = $conn->prepare(
            'SELECT user_id, full_name, username, password_hash, role FROM users WHERE username = ? LIMIT 1'
        );

        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $userRow = $result ? $result->fetch_assoc() : null;
            $stmt->close();

            if (
                $userRow !== null &&
                !empty($userRow['password_hash']) &&
                password_verify($rawPassword, $userRow['password_hash'])
            ) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = (int) $userRow['user_id'];
                $_SESSION['full_name'] = (string) $userRow['full_name'];
                $_SESSION['username'] = (string) $userRow['username'];
                $_SESSION['role'] = (string) $userRow['role'];
                $_SESSION['success_msg'] = 'Welcome back, ' . $_SESSION['full_name'] . '!';

                $targetPage = ($_SESSION['role'] === 'admin')
                    ? 'modules/dashboard/admin-dashboard.php'
                    : 'index.php';
                header('Location: ' . $targetPage);
                exit();
            }

            $loginErrors[] = 'Invalid username or password.';
        } else {
            $loginErrors[] = 'Database error while verifying credentials.';
        }
    }

    if (!empty($loginErrors)) {
        $_SESSION['error_msg'] = implode(' ', $loginErrors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BookNest</title>
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
        <h2>Welcome Back!</h2>
        <p class="auth-subtitle">Please sign in to your account.</p>

        <?php if (!empty($_SESSION['error_msg'])): ?>
            <div class="alert error">
                <?php echo htmlspecialchars($_SESSION['error_msg'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success_msg'])): ?>
            <div class="alert success">
                <?php echo htmlspecialchars($_SESSION['success_msg'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input
                        type="text"
                        name="username"
                        placeholder="Enter your username"
                        value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required>
                    <i class="fas fa-eye password-toggle" data-target="loginPassword"></i>
                </div>
            </div>

            <div class="form-options">
                <label><input type="checkbox" name="remember_me"> Remember me</label>
                <a href="forgot-password.php">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-primary full-width">Sign In</button>
        </form>

        <p class="auth-bottom">
            Don't have an account? <a href="register.php">Create an account</a>
        </p>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>