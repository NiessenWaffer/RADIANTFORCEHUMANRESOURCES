<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Get date range
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Get application statistics
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) as reviewed,
        SUM(CASE WHEN status = 'shortlisted' THEN 1 ELSE 0 END) as shortlisted,
        SUM(CASE WHEN status = 'hired' THEN 1 ELSE 0 END) as hired,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
    FROM location_applications
    WHERE DATE(created_at) BETWEEN :start_date AND :end_date
");
$stmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
$app_stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get job statistics
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_jobs,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_jobs
    FROM job_positions
    WHERE DATE(created_at) BETWEEN :start_date AND :end_date
");
$stmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
$job_stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get newsletter statistics
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total_subscribers,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_subscribers
    FROM newsletter_subscribers
");
$newsletter_stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get contact inquiries statistics
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_inquiries,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_inquiries,
        SUM(CASE WHEN status = 'responded' THEN 1 ELSE 0 END) as responded_inquiries
    FROM contact_inquiries
    WHERE DATE(created_at) BETWEEN :start_date AND :end_date
");
$stmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
$inquiries_stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get daily application trend
$stmt = $pdo->prepare("
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as count
    FROM location_applications
    WHERE DATE(created_at) BETWEEN :start_date AND :end_date
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");
$stmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
$trend_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get top positions
$stmt = $pdo->prepare("
    SELECT 
        jp.position_title,
        COUNT(la.id) as application_count
    FROM job_positions jp
    LEFT JOIN location_applications la ON jp.id = la.job_position_id
    WHERE DATE(la.created_at) BETWEEN :start_date AND :end_date
    GROUP BY jp.id
    ORDER BY application_count DESC
    LIMIT 5
");
$stmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
$top_pos_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Conversion rate
$conversion_rate = $app_stats['total'] > 0 ? round(($app_stats['hired'] / $app_stats['total']) * 100, 2) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - Admin</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .analytics-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
        .date-filter { display: flex; gap: 10px; align-items: center; }
        .date-filter input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .date-filter button { padding: 8px 15px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; border-top: 3px solid #1e3a5f; }
        .stat-number { font-size: 32px; font-weight: bold; color: #1e3a5f; }
        .stat-label { color: #666; font-size: 12px; margin-top: 5px; }
        .stat-subtext { color: #999; font-size: 11px; margin-top: 8px; }
        .chart-container { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .chart-title { font-weight: bold; margin-bottom: 15px; }
        .chart-wrapper { position: relative; height: 300px; }
        .two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        @media (max-width: 768px) {
            .two-column { grid-template-columns: 1fr; }
            .analytics-header { flex-direction: column; align-items: flex-start; }
            .date-filter { flex-direction: column; width: 100%; }
            .date-filter input, .date-filter button { width: 100%; }
        }
        .status-breakdown { background: white; padding: 20px; border-radius: 8px; }
        .status-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .status-item:last-child { border-bottom: none; }
        .status-label { color: #666; }
        .status-value { font-weight: bold; color: #1e3a5f; }
        .top-positions { background: white; padding: 20px; border-radius: 8px; }
        .position-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eee; }
        .position-item:last-child { border-bottom: none; }
        .position-name { color: #333; }
        .position-count { background: #1e3a5f; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Analytics Dashboard</h1>
                <p>Track recruitment metrics and performance</p>
            </div>

            <div class="analytics-header">
                <h3 style="margin: 0;">Date Range</h3>
                <form method="GET" class="date-filter">
                    <input type="date" name="start_date" value="<?php echo $start_date; ?>">
                    <span>to</span>
                    <input type="date" name="end_date" value="<?php echo $end_date; ?>">
                    <button type="submit" class="btn-primary">Filter</button>
                </form>
            </div>

            <!-- Key Metrics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $app_stats['total']; ?></div>
                    <div class="stat-label">Total Applications</div>
                    <div class="stat-subtext">Period: <?php echo date('M d', strtotime($start_date)); ?> - <?php echo date('M d, Y', strtotime($end_date)); ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-number"><?php echo $app_stats['hired']; ?></div>
                    <div class="stat-label">Hired</div>
                    <div class="stat-subtext">Conversion: <?php echo $conversion_rate; ?>%</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number"><?php echo $job_stats['active_jobs']; ?></div>
                    <div class="stat-label">Active Job Positions</div>
                    <div class="stat-subtext">Total: <?php echo $job_stats['total_jobs']; ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-number"><?php echo $newsletter_stats['active_subscribers']; ?></div>
                    <div class="stat-label">Newsletter Subscribers</div>
                    <div class="stat-subtext">Total: <?php echo $newsletter_stats['total_subscribers']; ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-number"><?php echo $inquiries_stats['total_inquiries']; ?></div>
                    <div class="stat-label">Contact Inquiries</div>
                    <div class="stat-subtext">New: <?php echo $inquiries_stats['new_inquiries']; ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-number"><?php echo $app_stats['shortlisted']; ?></div>
                    <div class="stat-label">Shortlisted</div>
                    <div class="stat-subtext">Pending: <?php echo $app_stats['pending']; ?></div>
                </div>
            </div>

            <!-- Charts -->
            <div class="two-column">
                <div class="chart-container">
                    <div class="chart-title">Application Trend</div>
                    <div class="chart-wrapper">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart-title">Application Status Distribution</div>
                    <div class="chart-wrapper">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown & Top Positions -->
            <div class="two-column">
                <div class="status-breakdown">
                    <h3 style="margin-top: 0;">Application Status Breakdown</h3>
                    <div class="status-item">
                        <span class="status-label">Pending Review</span>
                        <span class="status-value"><?php echo $app_stats['pending']; ?></span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Reviewed</span>
                        <span class="status-value"><?php echo $app_stats['reviewed']; ?></span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Shortlisted</span>
                        <span class="status-value"><?php echo $app_stats['shortlisted']; ?></span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Hired</span>
                        <span class="status-value"><?php echo $app_stats['hired']; ?></span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Rejected</span>
                        <span class="status-value"><?php echo $app_stats['rejected']; ?></span>
                    </div>
                </div>

                <div class="top-positions">
                    <h3 style="margin-top: 0;">Top Job Positions</h3>
                    <?php if (empty($top_pos_data)): ?>
                        <p style="color: #999;">No applications yet</p>
                    <?php else: ?>
                        <?php foreach ($top_pos_data as $pos): ?>
                            <div class="position-item">
                                <span class="position-name"><?php echo htmlspecialchars($pos['position_title']); ?></span>
                                <span class="position-count"><?php echo $pos['application_count']; ?> apps</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendData = <?php echo json_encode($trend_data); ?>;
        
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendData.map(d => new Date(d.date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'})),
                datasets: [{
                    label: 'Applications',
                    data: trendData.map(d => d.count),
                    borderColor: '#1e3a5f',
                    backgroundColor: 'rgba(30, 58, 95, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Reviewed', 'Shortlisted', 'Hired', 'Rejected'],
                datasets: [{
                    data: [
                        <?php echo $app_stats['pending']; ?>,
                        <?php echo $app_stats['reviewed']; ?>,
                        <?php echo $app_stats['shortlisted']; ?>,
                        <?php echo $app_stats['hired']; ?>,
                        <?php echo $app_stats['rejected']; ?>
                    ],
                    backgroundColor: ['#fff3cd', '#d1ecf1', '#cfe2ff', '#d4edda', '#f8d7da']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
</body>
</html>
