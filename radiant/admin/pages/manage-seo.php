<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$action = $_GET['action'] ?? '';
$seo_id = $_GET['id'] ?? '';

// Handle add/edit SEO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_seo'])) {
    $page_url = $_POST['page_url'];
    $page_title = $_POST['page_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keywords = $_POST['meta_keywords'];
    $og_title = $_POST['og_title'];
    $og_description = $_POST['og_description'];
    $canonical_url = $_POST['canonical_url'];
    
    if ($action === 'edit' && $seo_id) {
        $stmt = $pdo->prepare("UPDATE seo_meta SET page_title = :page_title, meta_description = :meta_description, meta_keywords = :meta_keywords, og_title = :og_title, og_description = :og_description, canonical_url = :canonical_url, updated_at = NOW() WHERE id = :id");
        $stmt->execute([':page_title' => $page_title, ':meta_description' => $meta_description, ':meta_keywords' => $meta_keywords, ':og_title' => $og_title, ':og_description' => $og_description, ':canonical_url' => $canonical_url, ':id' => $seo_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO seo_meta (page_url, page_title, meta_description, meta_keywords, og_title, og_description, canonical_url) VALUES (:page_url, :page_title, :meta_description, :meta_keywords, :og_title, :og_description, :canonical_url)");
        $stmt->execute([':page_url' => $page_url, ':page_title' => $page_title, ':meta_description' => $meta_description, ':meta_keywords' => $meta_keywords, ':og_title' => $og_title, ':og_description' => $og_description, ':canonical_url' => $canonical_url]);
    }
    
    header('Location: manage-seo.php');
    exit;
}

// Handle delete
if ($action === 'delete' && $seo_id) {
    $stmt = $pdo->prepare("DELETE FROM seo_meta WHERE id = :id");
    $stmt->execute([':id' => $seo_id]);
    
    header('Location: manage-seo.php');
    exit;
}

// Get SEO for editing
$seo = null;
if ($action === 'edit' && $seo_id) {
    $stmt = $pdo->prepare("SELECT * FROM seo_meta WHERE id = :id");
    $stmt->execute([':id' => $seo_id]);
    $seo = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all SEO entries
$result = $pdo->query("SELECT * FROM seo_meta ORDER BY page_url ASC");
$seo_entries = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO Management - Admin</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .form-group textarea { min-height: 80px; }
        .form-container { background: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .seo-row { display: grid; grid-template-columns: 2fr 1fr 1fr 100px; gap: 15px; align-items: center; padding: 15px; border-bottom: 1px solid #eee; }
        .seo-row:hover { background: #f8f9fa; }
        .action-buttons { display: flex; gap: 5px; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
        .char-count { font-size: 12px; color: #999; margin-top: 5px; }
        .char-warning { color: #dc3545; }
        .char-good { color: #28a745; }
        @media (max-width: 768px) {
            .form-row { grid-template-columns: 1fr; }
            .seo-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>SEO Management</h1>
                <p>Optimize pages for search engines</p>
            </div>

            <?php if ($action === 'edit' || $action === 'new'): ?>
                <div class="form-container">
                    <a href="manage-seo.php" class="btn-secondary" style="margin-bottom: 20px;">← Back to SEO</a>
                    
                    <h3><?php echo $action === 'edit' ? 'Edit SEO' : 'Add New Page'; ?></h3>
                    
                    <form method="POST">
                        <input type="hidden" name="save_seo" value="1">
                        
                        <?php if ($action === 'new'): ?>
                            <div class="form-group">
                                <label>Page URL *</label>
                                <input type="text" name="page_url" value="<?php echo htmlspecialchars($seo['page_url'] ?? ''); ?>" placeholder="/page-name" required>
                                <small style="color: #666;">e.g., /services, /about, /blog/post-title</small>
                            </div>
                        <?php endif; ?>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Page Title *</label>
                                <input type="text" name="page_title" value="<?php echo htmlspecialchars($seo['page_title'] ?? ''); ?>" maxlength="60" required>
                                <div class="char-count"><span id="title-count">0</span>/60 characters</div>
                            </div>
                            <div class="form-group">
                                <label>Canonical URL</label>
                                <input type="text" name="canonical_url" value="<?php echo htmlspecialchars($seo['canonical_url'] ?? ''); ?>" placeholder="https://example.com/page">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Meta Description *</label>
                            <textarea name="meta_description" maxlength="160" required><?php echo htmlspecialchars($seo['meta_description'] ?? ''); ?></textarea>
                            <div class="char-count"><span id="desc-count">0</span>/160 characters</div>
                        </div>

                        <div class="form-group">
                            <label>Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="<?php echo htmlspecialchars($seo['meta_keywords'] ?? ''); ?>" placeholder="keyword1, keyword2, keyword3">
                            <small style="color: #666;">Separate keywords with commas</small>
                        </div>

                        <hr style="margin: 30px 0;">

                        <h4>Open Graph (Social Media)</h4>

                        <div class="form-row">
                            <div class="form-group">
                                <label>OG Title</label>
                                <input type="text" name="og_title" value="<?php echo htmlspecialchars($seo['og_title'] ?? ''); ?>" placeholder="Title for social sharing">
                            </div>
                            <div class="form-group">
                                <label>OG Description</label>
                                <input type="text" name="og_description" value="<?php echo htmlspecialchars($seo['og_description'] ?? ''); ?>" placeholder="Description for social sharing">
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn-primary">Save SEO</button>
                            <a href="manage-seo.php" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div style="background: white; border-radius: 8px; overflow: hidden;">
                    <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0;">SEO Pages</h3>
                        <a href="?action=new" class="btn-primary">+ Add Page</a>
                    </div>
                    
                    <?php if (empty($seo_entries)): ?>
                        <div style="padding: 40px; text-align: center; color: #999;">
                            No SEO entries yet
                        </div>
                    <?php else: ?>
                        <?php foreach ($seo_entries as $entry): ?>
                            <div class="seo-row">
                                <div>
                                    <strong><?php echo htmlspecialchars($entry['page_url']); ?></strong>
                                    <div style="font-size: 12px; color: #666;"><?php echo htmlspecialchars(substr($entry['page_title'], 0, 50)); ?></div>
                                </div>
                                <div style="font-size: 12px; color: #666;">
                                    Title: <span class="<?php echo strlen($entry['page_title']) > 60 ? 'char-warning' : 'char-good'; ?>"><?php echo strlen($entry['page_title']); ?>/60</span>
                                </div>
                                <div style="font-size: 12px; color: #666;">
                                    Desc: <span class="<?php echo strlen($entry['meta_description']) > 160 ? 'char-warning' : 'char-good'; ?>"><?php echo strlen($entry['meta_description']); ?>/160</span>
                                </div>
                                <div class="action-buttons">
                                    <a href="?action=edit&id=<?php echo $entry['id']; ?>" class="btn-primary btn-small">Edit</a>
                                    <a href="?action=delete&id=<?php echo $entry['id']; ?>" class="btn-danger btn-small" onclick="return confirm('Delete this SEO entry?');">Delete</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        document.getElementById('title-count')?.parentElement.addEventListener('input', function(e) {
            const input = e.target.closest('.form-group').querySelector('input[name="page_title"]');
            document.getElementById('title-count').textContent = input.value.length;
        });
        
        document.getElementById('desc-count')?.parentElement.addEventListener('input', function(e) {
            const textarea = e.target.closest('.form-group').querySelector('textarea[name="meta_description"]');
            document.getElementById('desc-count').textContent = textarea.value.length;
        });

        // Initialize counts
        const titleInput = document.querySelector('input[name="page_title"]');
        const descInput = document.querySelector('textarea[name="meta_description"]');
        if (titleInput) document.getElementById('title-count').textContent = titleInput.value.length;
        if (descInput) document.getElementById('desc-count').textContent = descInput.value.length;
    </script>
</body>
</html>
