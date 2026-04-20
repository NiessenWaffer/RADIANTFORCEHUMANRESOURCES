<?php
session_start();
require_once __DIR__ . '/../config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $stmt = $pdo->prepare("UPDATE job_applications SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['id']]);
        $success = "Application status updated!";
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM job_applications WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $success = "Application deleted!";
    }
}

// Get filter parameters
$location_filter = $_GET['location'] ?? '';

// Fetch all job applications from website with optional location filter
$query = "SELECT * FROM job_applications";
$params = [];

if (!empty($location_filter)) {
    $query .= " WHERE location = ?";
    $params[] = $location_filter;
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get unique locations for filter dropdown
$locations_stmt = $pdo->query("SELECT DISTINCT location FROM job_applications WHERE location IS NOT NULL AND location != '' ORDER BY location");
$locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);

// Count by status
$status_counts = [
    'unread' => 0,
    'read' => 0,
    'shortlisted' => 0,
    'rejected' => 0,
    'hired' => 0
];

foreach ($applications as $app) {
    if (isset($status_counts[$app['status']])) {
        $status_counts[$app['status']]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Job Applications | Radiant Force HR</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <link rel="stylesheet" href="../assets/dark-mode.css">
    <link rel="stylesheet" href="../assets/applications-table.css">
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="content-wrapper">
            <header class="dashboard-header">
                <div>
                    <h1>Website Job Applications</h1>
                    <p>Applications submitted through the website job form</p>
                </div>
            </header>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <!-- Location Filter -->
            <?php if (!empty($locations)): ?>
            <div class="content-section" style="margin-bottom: 20px;">
                <form method="GET" style="display: flex; align-items: center; gap: 10px;">
                    <label for="location" style="font-weight: 600; color: #374151;">Filter by Location:</label>
                    <select name="location" id="location" onchange="this.form.submit()" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; min-width: 200px;">
                        <option value="">All Locations (<?php echo count($applications); ?>)</option>
                        <?php foreach ($locations as $loc): ?>
                            <?php
                            // Count applications for this location
                            $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM job_applications WHERE location = ?");
                            $count_stmt->execute([$loc]);
                            $loc_count = $count_stmt->fetchColumn();
                            ?>
                            <option value="<?php echo htmlspecialchars($loc); ?>" <?php echo $location_filter === $loc ? 'selected' : ''; ?>>
                                📍 <?php echo htmlspecialchars($loc); ?> (<?php echo $loc_count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($location_filter)): ?>
                        <a href="manage-job-applications.php" style="padding: 8px 12px; background: #ef4444; color: white; border-radius: 6px; text-decoration: none; font-size: 14px;">
                            Clear Filter
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-label">Total Applications</span>
                    <span class="stat-value"><?php echo count($applications); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Unread</span>
                    <span class="stat-value" style="color: #f59e0b;"><?php echo $status_counts['unread']; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Shortlisted</span>
                    <span class="stat-value" style="color: #10b981;"><?php echo $status_counts['shortlisted']; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Hired</span>
                    <span class="stat-value" style="color: #3b82f6;"><?php echo $status_counts['hired']; ?></span>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="content-section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Location</th>
                            <th>Age</th>
                            <th>Height/Weight</th>
                            <th>Files</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applications)): ?>
                            <tr>
                                <td colspan="9" class="no-data">No applications found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                                <?php
                                $files = json_decode($app['resume_files'], true);
                                $fileCount = is_array($files) ? count($files) : 0;
                                ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></strong>
                                        <?php if ($app['status'] === 'unread'): ?>
                                            <span class="badge badge-warning">New</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($app['job_preferred']); ?></td>
                                    <td>
                                        <?php if (!empty($app['location'])): ?>
                                            <span class="badge badge-primary" style="background: #3b82f6;">
                                                📍 <?php echo htmlspecialchars($app['location']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary" style="opacity: 0.6;">General</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($app['age']); ?> yrs</td>
                                    <td><?php echo htmlspecialchars($app['height']); ?>cm / <?php echo htmlspecialchars($app['weight']); ?>kg</td>
                                    <td>
                                        <?php if ($fileCount > 0): ?>
                                            <span class="badge badge-info"><?php echo $fileCount; ?> file(s)</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">No files</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                            <select name="status" onchange="this.form.submit()" class="status-select status-<?php echo $app['status']; ?>">
                                                <option value="unread" <?php echo $app['status'] === 'unread' ? 'selected' : ''; ?>>Unread</option>
                                                <option value="read" <?php echo $app['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                                <option value="shortlisted" <?php echo $app['status'] === 'shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                                                <option value="rejected" <?php echo $app['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                <option value="hired" <?php echo $app['status'] === 'hired' ? 'selected' : ''; ?>>Hired</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                                    <td>
                                        <a href="view-job-application.php?id=<?php echo $app['id']; ?>" class="btn-action btn-view" title="View Details">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this application?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                            <button type="submit" class="btn-action btn-delete" title="Delete">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../assets/admin-script.js"></script>
</body>
</html>
