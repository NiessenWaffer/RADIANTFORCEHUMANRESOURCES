<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$action = $_GET['action'] ?? '';
$subscriber_id = $_GET['id'] ?? '';

// Handle add subscriber
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subscriber'])) {
    $email = $_POST['email'];
    $full_name = $_POST['full_name'] ?? '';
    $subscription_type = $_POST['subscription_type'] ?? 'all';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email, full_name, subscription_type) VALUES (:email, :full_name, :subscription_type)");
        $stmt->execute([':email' => $email, ':full_name' => $full_name, ':subscription_type' => $subscription_type]);
        $success = "Subscriber added successfully";
    } catch (PDOException $e) {
        $error = "Email already subscribed";
    }
}

// Handle delete
if ($action === 'delete' && $subscriber_id) {
    $stmt = $pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = :id");
    $stmt->execute([':id' => $subscriber_id]);
    
    header('Location: manage-newsletter.php');
    exit;
}

// Handle unsubscribe
if ($action === 'unsubscribe' && $subscriber_id) {
    $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = 'unsubscribed' WHERE id = :id");
    $stmt->execute([':id' => $subscriber_id]);
    
    header('Location: manage-newsletter.php');
    exit;
}

// Get all subscribers
$result = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
$subscribers = $result->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats = [];
foreach (['total', 'active', 'unsubscribed'] as $key) {
    $where = $key === 'total' ? '' : "WHERE status = '$key'";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM newsletter_subscribers $where");
    $stats[$key] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Management - Admin</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; border-top: 3px solid #1e3a5f; }
        .stat-number { font-size: 28px; font-weight: bold; color: #1e3a5f; }
        .stat-label { color: #666; font-size: 12px; margin-top: 5px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 500px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-close { cursor: pointer; font-size: 24px; color: #999; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .subscriber-row { display: grid; grid-template-columns: 1fr 1fr 1fr 150px 100px; gap: 15px; align-items: center; padding: 15px; border-bottom: 1px solid #eee; }
        .subscriber-row:hover { background: #f8f9fa; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-active { background: #d4edda; color: #155724; }
        .status-unsubscribed { background: #f8d7da; color: #721c24; }
        .action-buttons { display: flex; gap: 5px; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Newsletter Management</h1>
                <p>Manage newsletter subscribers and campaigns</p>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Total Subscribers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['active']; ?></div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['unsubscribed']; ?></div>
                    <div class="stat-label">Unsubscribed</div>
                </div>
            </div>

            <div style="background: white; border-radius: 8px; overflow: hidden; margin-bottom: 30px;">
                <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0;">Subscribers</h3>
                    <button class="btn-primary" onclick="openModal()">+ Add Subscriber</button>
                </div>
                
                <?php if (empty($subscribers)): ?>
                    <div style="padding: 40px; text-align: center; color: #999;">
                        No subscribers yet
                    </div>
                <?php else: ?>
                    <?php foreach ($subscribers as $subscriber): ?>
                        <div class="subscriber-row">
                            <div>
                                <strong><?php echo htmlspecialchars($subscriber['full_name'] ?? 'N/A'); ?></strong>
                            </div>
                            <div><?php echo htmlspecialchars($subscriber['email']); ?></div>
                            <div><?php echo ucfirst(str_replace('_', ' ', $subscriber['subscription_type'])); ?></div>
                            <div><span class="status-badge status-<?php echo $subscriber['status']; ?>"><?php echo ucfirst($subscriber['status']); ?></span></div>
                            <div class="action-buttons">
                                <?php if ($subscriber['status'] === 'active'): ?>
                                    <a href="?action=unsubscribe&id=<?php echo $subscriber['id']; ?>" class="btn-warning btn-small" onclick="return confirm('Unsubscribe this user?');">Unsub</a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $subscriber['id']; ?>" class="btn-danger btn-small" onclick="return confirm('Delete this subscriber?');">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Add Subscriber Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Subscriber</h3>
                <span class="modal-close" onclick="closeModal()">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="add_subscriber" value="1">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name">
                </div>
                <div class="form-group">
                    <label>Subscription Type</label>
                    <select name="subscription_type">
                        <option value="all">All Updates</option>
                        <option value="jobs">Jobs Only</option>
                        <option value="news">News Only</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;">Add Subscriber</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('addModal').classList.add('active');
        }
        function closeModal() {
            document.getElementById('addModal').classList.remove('active');
        }
        window.onclick = function(event) {
            const modal = document.getElementById('addModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
