<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? 0;

// Database connection
require_once __DIR__ . '/../config.php';

// Fetch application
$stmt = $pdo->prepare("SELECT * FROM job_applications WHERE id = ?");
$stmt->execute([$id]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$app) {
    header('Location: dashboard.php');
    exit;
}

// Mark as read
if ($app['status'] === 'unread') {
    $stmt = $pdo->prepare("UPDATE job_applications SET status = 'read' WHERE id = ?");
    $stmt->execute([$id]);
}

// Parse resume files
$resume_files = $app['resume_files'] ? json_decode($app['resume_files'], true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application | Admin</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <div class="admin-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../imagees/logo.png" alt="Logo">
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Applications
                </a>
                <a href="manage-jobs.php" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    Manage Jobs
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="../auth/logout.php" class="logout-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Logout
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="view-header">
                <a href="dashboard.php" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Back to Dashboard
                </a>
                <h1>Application Details</h1>
            </div>

            <div class="application-details">
                <div class="detail-card">
                    <h2>Personal Information</h2>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Full Name</label>
                            <p><?php echo htmlspecialchars($app['first_name'] . ' ' . ($app['middle_name'] ? $app['middle_name'] . ' ' : '') . $app['last_name']); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Job Position</label>
                            <p><?php echo htmlspecialchars($app['job_preferred']); ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Age</label>
                            <p><?php echo $app['age'] ? htmlspecialchars($app['age']) : 'N/A'; ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Height</label>
                            <p><?php echo $app['height'] ? htmlspecialchars($app['height']) . ' cm' : 'N/A'; ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Weight</label>
                            <p><?php echo $app['weight'] ? htmlspecialchars($app['weight']) . ' kg' : 'N/A'; ?></p>
                        </div>
                        <div class="detail-item">
                            <label>Application Date</label>
                            <p><?php echo date('F d, Y h:i A', strtotime($app['application_date'])); ?></p>
                        </div>
                    </div>
                </div>

                <?php if ($app['additional_message']): ?>
                <div class="detail-card">
                    <h2>Cover Letter / Additional Message</h2>
                    <div class="message-box">
                        <?php echo nl2br(htmlspecialchars($app['additional_message'])); ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="detail-card">
                    <h2>Resume & Documents</h2>
                    <?php if (!empty($resume_files)): ?>
                        <div class="files-list">
                            <?php foreach ($resume_files as $file): ?>
                                <div class="file-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                        <polyline points="13 2 13 9 20 9"></polyline>
                                    </svg>
                                    <span><?php echo htmlspecialchars($file); ?></span>
                                    <a href="../uploads/<?php echo urlencode($file); ?>" download class="btn-download">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-files">No files uploaded</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Mobile menu toggle
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
    </script>
</body>
</html>
