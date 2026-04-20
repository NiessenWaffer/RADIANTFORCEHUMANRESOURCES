<?php
session_start();

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ../pages/dashboard.php');
    exit;
}

// Database connection
require_once __DIR__ . '/../config.php';

// Check if admin account exists
$stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
$adminCount = $stmt->fetchColumn();

if ($adminCount === 0) {
    header('Location: setup.php');
    exit;
}

// Handle login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        // Check credentials from database
        $stmt = $pdo->prepare("SELECT id, email, password FROM admin_users WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: ../pages/dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Radiant Force HR</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="../../imagees/logo.png" alt="Radiant Force HR" class="login-logo">
                <h1>Admin Login</h1>
                <p>Access the job applications dashboard</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-login">Sign In</button>
            </form>
            
            <div class="login-footer">
                <a href="../../../radiantforcehumanresources.php">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
