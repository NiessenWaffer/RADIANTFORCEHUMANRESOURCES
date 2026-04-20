<?php
session_start();
require_once __DIR__ . '/../config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $stmt = $pdo->prepare("UPDATE location_applications SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['id']]);
        $success = "Application status updated!";
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM location_applications WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $success = "Application deleted!";
    }
}

// Fetch all applications with job and location info
$stmt = $pdo->query("
    SELECT la.*, jp.position_title, l.location_name, c.city_name
    FROM location_applications la
    LEFT JOIN job_positions jp ON la.job_position_id = jp.id
    LEFT JOIN locations l ON jp.location_id = l.id
    LEFT JOIN cities c ON l.city_id = c.id
    ORDER BY la.created_at DESC
");
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count by status
$status_counts = [
    'pending' => 0,
    'reviewed' => 0,
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
    <title>Manage Applications | Radiant Force HR</title>
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
                    <h1>Manage Applications</h1>
                    <p>View and manage job applications</p>
                </div>
            </header>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <!-- Status Summary -->
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-label">Total Applications</span>
                    <span class="stat-value"><?php echo count($applications); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Pending</span>
                    <span class="stat-value"><?php echo $status_counts['pending']; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Reviewed</span>
                    <span class="stat-value"><?php echo $status_counts['reviewed']; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Shortlisted</span>
                    <span class="stat-value"><?php echo $status_counts['shortlisted']; ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Hired</span>
                    <span class="stat-value"><?php echo $status_counts['hired']; ?></span>
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Position</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applications)): ?>
                            <tr>
                                <td colspan="8" class="no-data">No applications found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($app['full_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($app['email']); ?></td>
                                    <td><?php echo htmlspecialchars($app['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($app['position_title'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($app['location_name'] ?? '-'); ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                            <select name="status" onchange="this.form.submit()" class="status-select">
                                                <option value="pending" <?php echo $app['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="reviewed" <?php echo $app['status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                                <option value="shortlisted" <?php echo $app['status'] === 'shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                                                <option value="rejected" <?php echo $app['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                <option value="hired" <?php echo $app['status'] === 'hired' ? 'selected' : ''; ?>>Hired</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="view-application.php?id=<?php echo $app['id']; ?>" class="btn-edit" title="View Details">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <form method="POST" onsubmit="return confirm('Delete this application?');" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                                <button type="submit" class="btn-delete" title="Delete">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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
    </script>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
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
            color: var(--primary-color);
        }

        .status-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .status-select:hover {
            border-color: var(--primary-color);
        }
    </style>
</body>
</html>
