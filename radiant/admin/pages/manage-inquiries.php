<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$action = $_GET['action'] ?? '';
$inquiry_id = $_GET['id'] ?? '';

// Handle response to inquiry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respond_inquiry'])) {
    $id = $_POST['inquiry_id'];
    $response = $_POST['response'];
    
    $stmt = $pdo->prepare("UPDATE contact_inquiries SET status = 'responded', response = :response, responded_at = NOW() WHERE id = :id");
    $stmt->execute([':response' => $response, ':id' => $id]);
    
    header('Location: manage-inquiries.php');
    exit;
}

// Handle delete
if ($action === 'delete' && $inquiry_id) {
    $stmt = $pdo->prepare("DELETE FROM contact_inquiries WHERE id = :id");
    $stmt->execute([':id' => $inquiry_id]);
    
    header('Location: manage-inquiries.php');
    exit;
}

// Handle status update
if ($action === 'update_status' && $inquiry_id) {
    $status = $_GET['status'] ?? 'read';
    $stmt = $pdo->prepare("UPDATE contact_inquiries SET status = :status WHERE id = :id");
    $stmt->execute([':status' => $status, ':id' => $inquiry_id]);
    
    header('Location: manage-inquiries.php');
    exit;
}

// Get all inquiries
$result = $pdo->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC");
$inquiries = $result->fetchAll(PDO::FETCH_ASSOC);

// Get inquiry details if viewing
$inquiry_detail = null;
if ($action === 'view' && $inquiry_id) {
    $stmt = $pdo->prepare("SELECT * FROM contact_inquiries WHERE id = :id");
    $stmt->execute([':id' => $inquiry_id]);
    $inquiry_detail = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Mark as read
    if ($inquiry_detail && $inquiry_detail['status'] === 'new') {
        $stmt = $pdo->prepare("UPDATE contact_inquiries SET status = 'read' WHERE id = :id");
        $stmt->execute([':id' => $inquiry_id]);
    }
}

// Get statistics
$stats = [];
foreach (['new', 'read', 'responded', 'closed'] as $status) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM contact_inquiries WHERE status = :status");
    $stmt->execute([':status' => $status]);
    $stats[$status] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contact Inquiries - Admin</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <style>
        .inquiry-detail { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .inquiry-detail h3 { margin-top: 0; }
        .inquiry-meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px; }
        .meta-item { background: white; padding: 10px; border-radius: 4px; }
        .meta-label { font-weight: bold; color: #666; font-size: 12px; }
        .meta-value { color: #333; margin-top: 5px; }
        .inquiry-message { background: white; padding: 15px; border-left: 4px solid #1e3a5f; margin-bottom: 20px; }
        .response-form { background: white; padding: 20px; border-radius: 8px; }
        .response-form textarea { width: 100%; min-height: 150px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: Arial; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; border-top: 3px solid #1e3a5f; }
        .stat-number { font-size: 28px; font-weight: bold; color: #1e3a5f; }
        .stat-label { color: #666; font-size: 12px; margin-top: 5px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-new { background: #fff3cd; color: #856404; }
        .status-read { background: #d1ecf1; color: #0c5460; }
        .status-responded { background: #d4edda; color: #155724; }
        .status-closed { background: #e2e3e5; color: #383d41; }
        .inquiry-row { display: grid; grid-template-columns: 1fr 1fr 1fr 150px 100px; gap: 15px; align-items: center; padding: 15px; border-bottom: 1px solid #eee; }
        .inquiry-row:hover { background: #f8f9fa; }
        .inquiry-type { font-size: 12px; color: #666; }
        .action-buttons { display: flex; gap: 5px; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Contact Inquiries</h1>
                <p>Manage and respond to customer inquiries</p>
            </div>

            <?php if ($action === 'view' && $inquiry_detail): ?>
                <div class="inquiry-detail">
                    <a href="manage-inquiries.php" class="btn-secondary" style="margin-bottom: 20px;">← Back to Inquiries</a>
                    
                    <h3><?php echo htmlspecialchars($inquiry_detail['subject']); ?></h3>
                    
                    <div class="inquiry-meta">
                        <div class="meta-item">
                            <div class="meta-label">From</div>
                            <div class="meta-value"><?php echo htmlspecialchars($inquiry_detail['full_name']); ?></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Email</div>
                            <div class="meta-value"><a href="mailto:<?php echo htmlspecialchars($inquiry_detail['email']); ?>"><?php echo htmlspecialchars($inquiry_detail['email']); ?></a></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Phone</div>
                            <div class="meta-value"><?php echo htmlspecialchars($inquiry_detail['phone'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Type</div>
                            <div class="meta-value"><?php echo ucfirst(str_replace('_', ' ', $inquiry_detail['inquiry_type'])); ?></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Status</div>
                            <div class="meta-value"><span class="status-badge status-<?php echo $inquiry_detail['status']; ?>"><?php echo ucfirst($inquiry_detail['status']); ?></span></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Received</div>
                            <div class="meta-value"><?php echo date('M d, Y H:i', strtotime($inquiry_detail['created_at'])); ?></div>
                        </div>
                    </div>

                    <div class="inquiry-message">
                        <strong>Message:</strong>
                        <p><?php echo nl2br(htmlspecialchars($inquiry_detail['message'])); ?></p>
                    </div>

                    <?php if ($inquiry_detail['response']): ?>
                        <div class="inquiry-message" style="border-left-color: #28a745;">
                            <strong>Your Response:</strong>
                            <p><?php echo nl2br(htmlspecialchars($inquiry_detail['response'])); ?></p>
                            <small>Sent: <?php echo date('M d, Y H:i', strtotime($inquiry_detail['responded_at'])); ?></small>
                        </div>
                    <?php else: ?>
                        <div class="response-form">
                            <h4>Send Response</h4>
                            <form method="POST">
                                <input type="hidden" name="inquiry_id" value="<?php echo $inquiry_detail['id']; ?>">
                                <input type="hidden" name="respond_inquiry" value="1">
                                <textarea name="response" placeholder="Type your response here..." required></textarea>
                                <button type="submit" class="btn-primary" style="margin-top: 10px;">Send Response</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['new']; ?></div>
                        <div class="stat-label">New Inquiries</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['read']; ?></div>
                        <div class="stat-label">Read</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['responded']; ?></div>
                        <div class="stat-label">Responded</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['closed']; ?></div>
                        <div class="stat-label">Closed</div>
                    </div>
                </div>

                <div style="background: white; border-radius: 8px; overflow: hidden;">
                    <div style="padding: 20px; border-bottom: 1px solid #eee;">
                        <h3 style="margin: 0;">All Inquiries</h3>
                    </div>
                    
                    <?php if (empty($inquiries)): ?>
                        <div style="padding: 40px; text-align: center; color: #999;">
                            No inquiries yet
                        </div>
                    <?php else: ?>
                        <?php foreach ($inquiries as $inquiry): ?>
                            <div class="inquiry-row">
                                <div>
                                    <strong><?php echo htmlspecialchars($inquiry['subject']); ?></strong>
                                    <div class="inquiry-type"><?php echo htmlspecialchars($inquiry['full_name']); ?></div>
                                </div>
                                <div><?php echo htmlspecialchars($inquiry['email']); ?></div>
                                <div><span class="status-badge status-<?php echo $inquiry['status']; ?>"><?php echo ucfirst($inquiry['status']); ?></span></div>
                                <div><?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?></div>
                                <div class="action-buttons">
                                    <a href="?action=view&id=<?php echo $inquiry['id']; ?>" class="btn-primary btn-small">View</a>
                                    <a href="?action=delete&id=<?php echo $inquiry['id']; ?>" class="btn-danger btn-small" onclick="return confirm('Delete this inquiry?');">Delete</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
