<?php
session_start();
require_once __DIR__ . '/../config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch statistics
$stats = [];

// Total cities
$stmt = $pdo->query("SELECT COUNT(*) as count FROM cities WHERE status = 'active'");
$stats['cities'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total locations
$stmt = $pdo->query("SELECT COUNT(*) as count FROM locations WHERE status = 'active'");
$stats['locations'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total job positions
$stmt = $pdo->query("SELECT COUNT(*) as count FROM job_positions WHERE status = 'active'");
$stats['positions'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total applications
$stmt = $pdo->query("SELECT COUNT(*) as count FROM location_applications");
$stats['applications'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Pending applications
$stmt = $pdo->query("SELECT COUNT(*) as count FROM location_applications WHERE status = 'pending'");
$stats['pending'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Recent website applications
try {
    $stmt = $pdo->query("
        SELECT * FROM job_applications 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $recent_applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recent_applications = [];
    error_log("Error fetching job_applications: " . $e->getMessage());
}

// Count website applications
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM job_applications");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $website_apps_count = $result ? $result['count'] : 0;
} catch (PDOException $e) {
    $website_apps_count = 0;
    error_log("Error counting job_applications: " . $e->getMessage());
}

// Count unread website applications
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM job_applications WHERE status = 'unread'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $unread_apps_count = $result ? $result['count'] : 0;
} catch (PDOException $e) {
    $unread_apps_count = 0;
    error_log("Error counting unread job_applications: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Radiant Force HR</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-main">
            <?php include '../includes/header.php'; ?>
            
            <div class="admin-content">
                <h1>Dashboard</h1>
                
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #3b82f6;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['cities']; ?></h3>
                            <p>Active Cities</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #10b981;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['locations']; ?></h3>
                            <p>Hiring Locations</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #f59e0b;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['positions']; ?></h3>
                            <p>Job Positions</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #8b5cf6;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['applications']; ?></h3>
                            <p>Total Applications</p>
                        </div>
                    </div>
                </div>
                
                <!-- Website Applications Stats -->
                <div class="content-section">
                    <div class="section-header">
                        <h2>Website Applications Overview</h2>
                    </div>
                    <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 20px;">
                        <div class="stat-card">
                            <span class="stat-label">Total Website Applications</span>
                            <span class="stat-value"><?php echo $website_apps_count; ?></span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-label">Unread Applications</span>
                            <span class="stat-value" style="color: #f59e0b;"><?php echo $unread_apps_count; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Recent Website Applications -->
                <div class="content-section">
                    <div class="section-header">
                        <h2>Recent Website Applications</h2>
                        <a href="manage-job-applications.php" class="btn-primary">View All</a>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Position</th>
                                    <th>Age</th>
                                    <th>Files</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent_applications)): ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center;">No applications yet</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_applications as $app): ?>
                                        <?php
                                        $files = json_decode($app['resume_files'], true);
                                        $fileCount = is_array($files) ? count($files) : 0;
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></strong>
                                                <?php if ($app['status'] === 'unread'): ?>
                                                    <span class="badge badge-warning" style="margin-left: 8px; font-size: 10px;">New</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($app['job_preferred']); ?></td>
                                            <td><?php echo htmlspecialchars($app['age']); ?> yrs</td>
                                            <td>
                                                <?php if ($fileCount > 0): ?>
                                                    <span class="badge badge-info"><?php echo $fileCount; ?> file(s)</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">No files</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $app['status']; ?>">
                                                    <?php echo ucfirst($app['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                                            <td>
                                                <a href="view-job-application.php?id=<?php echo $app['id']; ?>" class="btn-action">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
