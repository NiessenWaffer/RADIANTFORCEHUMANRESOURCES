<?php
session_start();
require_once __DIR__ . '/../config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'change_password') {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Get current admin email
            $stmt = $pdo->query("SELECT email, password FROM admin_users LIMIT 1");
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!password_verify($current_password, $admin['password'])) {
                $error = "Current password is incorrect!";
            } elseif ($new_password !== $confirm_password) {
                $error = "New passwords do not match!";
            } elseif (strlen($new_password) < 6) {
                $error = "Password must be at least 6 characters!";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE email = ?");
                $stmt->execute([$hashed_password, $admin['email']]);
                $success = "Password changed successfully!";
            }
        }
    }
}

// Get admin info
$stmt = $pdo->query("SELECT email, created_at FROM admin_users LIMIT 1");
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Get system stats
$stmt = $pdo->query("SELECT COUNT(*) as total FROM companies");
$companies_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) as total FROM jobs");
$jobs_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) as total FROM locations");
$locations_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) as total FROM location_applications");
$applications_count = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings | Radiant Force HR</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <link rel="stylesheet" href="../assets/dark-mode.css">
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <header class="dashboard-header">
                <div>
                    <h1>Settings</h1>
                    <p>Manage your admin account and system settings</p>
                </div>
            </header>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- System Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-label">Total Companies</span>
                    <span class="stat-value"><?php echo $companies_count; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Total Jobs</span>
                    <span class="stat-value"><?php echo $jobs_count; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Total Locations</span>
                    <span class="stat-value"><?php echo $locations_count; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Total Applications</span>
                    <span class="stat-value"><?php echo $applications_count; ?></span>
                </div>
            </div>
            
            <!-- Admin Account Section -->
            <div class="settings-section">
                <h2>Admin Account</h2>
                <div class="info-card">
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($admin['email']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Created:</span>
                        <span class="info-value"><?php echo date('F d, Y', strtotime($admin['created_at'])); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Change Password Section -->
            <div class="settings-section">
                <h2>Change Password</h2>
                <form method="POST" class="settings-form">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label>Current Password <span class="required">*</span></label>
                        <input type="password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>New Password <span class="required">*</span></label>
                        <input type="password" name="new_password" required minlength="6">
                        <small>Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm New Password <span class="required">*</span></label>
                        <input type="password" name="confirm_password" required minlength="6">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Update Password</button>
                    </div>
                </form>
            </div>
            
            <!-- System Information -->
            <div class="settings-section">
                <h2>System Information</h2>
                <div class="info-card">
                    <div class="info-row">
                        <span class="info-label">System Name:</span>
                        <span class="info-value">Radiant Force HR</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Version:</span>
                        <span class="info-value">1.0.0</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Database:</span>
                        <span class="info-value">MySQL</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">PHP Version:</span>
                        <span class="info-value"><?php echo phpversion(); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Preferences Section -->
            <div class="settings-section">
                <h2>Preferences</h2>
                <div class="preference-item">
                    <div class="preference-info">
                        <h3>Dark Mode</h3>
                        <p>Toggle dark mode for the admin panel</p>
                    </div>
                    <button class="btn-secondary" onclick="toggleDarkMode()">Toggle Dark Mode</button>
                </div>
            </div>
        </main>
    </div>

    <script>
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            });
            
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                mobileOverlay.classList.remove('active');
            });
        }

        function toggleDarkMode() {
            const body = document.body;
            body.classList.toggle('dark-mode');
            const isDarkMode = body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDarkMode);
            alert(isDarkMode ? 'Dark mode enabled' : 'Dark mode disabled');
        }
    </script>

    <script src="../assets/dark-mode.js"></script>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .stat-label {
            display: block;
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            display: block;
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        .settings-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: 1px solid #e0e0e0;
        }

        .settings-section h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: #1e3a5f;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 0.5rem;
        }

        .settings-form {
            max-width: 500px;
        }

        .info-card {
            background: #ffffff;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #1e3a5f;
            border: 1px solid #e0e0e0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #333;
        }

        .info-value {
            color: #666;
        }

        .preference-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: #ffffff;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #e0e0e0;
        }

        .preference-info h3 {
            margin: 0 0 0.5rem 0;
            color: #333;
        }

        .preference-info p {
            margin: 0;
            color: #666;
            font-size: 0.875rem;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .form-group small {
            display: block;
            margin-top: 0.25rem;
            color: #999;
            font-size: 0.75rem;
        }

        body.dark-mode .settings-section {
            background-color: var(--dark-card);
            border-color: var(--dark-border);
        }

        body.dark-mode .settings-section h2 {
            color: #007bff;
            border-bottom-color: #007bff;
        }

        body.dark-mode .info-card {
            background-color: #333333;
            border-left-color: #007bff;
            border-color: var(--dark-border);
        }

        body.dark-mode .info-label {
            color: var(--dark-text);
        }

        body.dark-mode .info-value {
            color: #999999;
        }

        body.dark-mode .preference-item {
            background-color: #333333;
            border-color: var(--dark-border);
        }

        body.dark-mode .preference-info h3 {
            color: var(--dark-text);
        }

        body.dark-mode .preference-info p {
            color: #999999;
        }

        body.dark-mode .stat-card {
            background-color: var(--dark-card);
            border-color: var(--dark-border);
        }
    </style>
</body>
</html>
