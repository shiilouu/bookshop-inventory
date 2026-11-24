
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/db.php';

$fullName = '';
$username = '';
$email = '';
$validationErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rawPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $defaultRole = 'staff';

    if ($fullName === '') {
        $validationErrors[] = 'Full name is required.';
    } elseif (mb_strlen($fullName) < 2 || mb_strlen($fullName) > 100) {
        $validationErrors[] = 'Full name must be between 2 and 100 characters.';
    }

    if ($username === '') {
        $validationErrors[] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
        $validationErrors[] = 'Username must be 3–50 characters (letters, numbers, or underscores only).';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationErrors[] = 'Please enter a valid email address.';
    }

    if ($rawPassword === '') {
        $validationErrors[] = 'Password is required.';
    } elseif (strlen($rawPassword) < 8) {
        $validationErrors[] = 'Password must be at least 8 characters long.';
    }

    if ($rawPassword !== $confirmPassword) {
        $validationErrors[] = 'Passwords do not match.';
    }

    if (empty($validationErrors)) {
        $checkStmt = $conn->prepare('SELECT user_id FROM users WHERE username = ? LIMIT 1');
        if ($checkStmt) {
            $checkStmt->bind_param('s', $username);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                $validationErrors[] = 'That username is already taken.';
            }
            $checkStmt->close();
        } else {
            $validationErrors[] = 'Database error while checking username.';
        }
    }

    if (empty($validationErrors)) {
        $passwordHash = password_hash($rawPassword, PASSWORD_DEFAULT);

        $insertStmt = $conn->prepare(
            'INSERT INTO users (full_name, username, password_hash, role) VALUES (?, ?, ?, ?)'
        );

        if ($insertStmt) {
            $insertStmt->bind_param('ssss', $fullName, $username, $passwordHash, $defaultRole);

            if ($insertStmt->execute()) {
                $insertStmt->close();
                $_SESSION['success_msg'] = 'Account created successfully! Please sign in.';
                header('Location: login.php');
                exit();
            }

            $validationErrors[] = 'Could not register account. Please try again.';
            $insertStmt->close();
        } else {
            $validationErrors[] = 'Database error while creating account.';
        }
    }

    if (!empty($validationErrors)) {
        $_SESSION['error_msg'] = implode(' ', $validationErrors);
    }
}

$flashError = $_SESSION['error_msg'] ?? null;
$flashSuccess = $_SESSION['success_msg'] ?? null;
unset($_SESSION['error_msg'], $_SESSION['success_msg']);
?>
=======
?>

>>>>>>> 2a9d9d087a6bc6fa418ee64128e7f86358dc5c48
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

<<<<<<< HEAD
        <?php if (!empty($flashError)): ?>
            <div class="alert error">
                <?php echo htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($flashSuccess)): ?>
            <div class="alert success">
                <?php echo htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

=======
>>>>>>> 2a9d9d087a6bc6fa418ee64128e7f86358dc5c48
        <form action="" method="POST" id="registerForm">
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
<<<<<<< HEAD
                    <input
                        type="text"
                        name="full_name"
                        placeholder="Enter your full name"
                        value="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
=======
                    <input type="text" name="full_name" placeholder="Enter your full name" required>
>>>>>>> 2a9d9d087a6bc6fa418ee64128e7f86358dc5c48
                </div>
            </div>

            <div class="form-group">
                <label>Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-at"></i>
<<<<<<< HEAD
                    <input
                        type="text"
                        name="username"
                        placeholder="Choose a username"
                        value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
=======
                    <input type="text" name="username" placeholder="Choose a username" required>
>>>>>>> 2a9d9d087a6bc6fa418ee64128e7f86358dc5c48
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
<<<<<<< HEAD
                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
=======
                    <input type="email" name="email" placeholder="Enter your email" required>
>>>>>>> 2a9d9d087a6bc6fa418ee64128e7f86358dc5c48
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
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 2a9d9d087a6bc6fa418ee64128e7f86358dc5c48
