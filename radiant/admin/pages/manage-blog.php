<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$action = $_GET['action'] ?? '';
$post_id = $_GET['id'] ?? '';

// Handle add/edit post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_post'])) {
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $content = $_POST['content'];
    $excerpt = $_POST['excerpt'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    
    if ($action === 'edit' && $post_id) {
        $stmt = $pdo->prepare("UPDATE blog_posts SET title = :title, slug = :slug, content = :content, excerpt = :excerpt, category = :category, status = :status, updated_at = NOW() WHERE id = :id");
        $stmt->execute([':title' => $title, ':slug' => $slug, ':content' => $content, ':excerpt' => $excerpt, ':category' => $category, ':status' => $status, ':id' => $post_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, content, excerpt, category, status, published_at) VALUES (:title, :slug, :content, :excerpt, :category, :status, NOW())");
        $stmt->execute([':title' => $title, ':slug' => $slug, ':content' => $content, ':excerpt' => $excerpt, ':category' => $category, ':status' => $status]);
    }
    
    header('Location: manage-blog.php');
    exit;
}

// Handle delete
if ($action === 'delete' && $post_id) {
    $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = :id");
    $stmt->execute([':id' => $post_id]);
    
    header('Location: manage-blog.php');
    exit;
}

// Get post for editing
$post = null;
if ($action === 'edit' && $post_id) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = :id");
    $stmt->execute([':id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all posts
$result = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
$posts = $result->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats = [];
foreach (['total', 'published', 'draft'] as $key) {
    $where = $key === 'total' ? '' : "WHERE status = '$key'";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM blog_posts $where");
    $stats[$key] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management - Admin</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; border-top: 3px solid #1e3a5f; }
        .stat-number { font-size: 28px; font-weight: bold; color: #1e3a5f; }
        .stat-label { color: #666; font-size: 12px; margin-top: 5px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: Arial; }
        .form-group textarea { min-height: 300px; }
        .post-row { display: grid; grid-template-columns: 2fr 1fr 1fr 150px 100px; gap: 15px; align-items: center; padding: 15px; border-bottom: 1px solid #eee; }
        .post-row:hover { background: #f8f9fa; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-published { background: #d4edda; color: #155724; }
        .status-draft { background: #fff3cd; color: #856404; }
        .status-archived { background: #e2e3e5; color: #383d41; }
        .action-buttons { display: flex; gap: 5px; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
        .form-container { background: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; }
        .form-row { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Blog Management</h1>
                <p>Create and manage blog posts</p>
            </div>

            <?php if ($action === 'edit' || $action === 'new'): ?>
                <div class="form-container">
                    <a href="manage-blog.php" class="btn-secondary" style="margin-bottom: 20px;">← Back to Posts</a>
                    
                    <h3><?php echo $action === 'edit' ? 'Edit Post' : 'New Post'; ?></h3>
                    
                    <form method="POST">
                        <input type="hidden" name="save_post" value="1">
                        
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="category" value="<?php echo htmlspecialchars($post['category'] ?? ''); ?>" placeholder="e.g., HR Tips, Industry News">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status">
                                    <option value="draft" <?php echo ($post['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                                    <option value="archived" <?php echo ($post['status'] ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Excerpt</label>
                            <textarea name="excerpt" placeholder="Brief summary of the post..." style="min-height: 80px;"><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Content *</label>
                            <textarea name="content" required><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn-primary">Save Post</button>
                            <a href="manage-blog.php" class="btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['total']; ?></div>
                        <div class="stat-label">Total Posts</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['published']; ?></div>
                        <div class="stat-label">Published</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['draft']; ?></div>
                        <div class="stat-label">Drafts</div>
                    </div>
                </div>

                <div style="background: white; border-radius: 8px; overflow: hidden;">
                    <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0;">Blog Posts</h3>
                        <a href="?action=new" class="btn-primary">+ New Post</a>
                    </div>
                    
                    <?php if (empty($posts)): ?>
                        <div style="padding: 40px; text-align: center; color: #999;">
                            No blog posts yet
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="post-row">
                                <div>
                                    <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                    <div style="font-size: 12px; color: #666;"><?php echo htmlspecialchars($post['category'] ?? 'Uncategorized'); ?></div>
                                </div>
                                <div><?php echo $post['views']; ?> views</div>
                                <div><?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
                                <div><span class="status-badge status-<?php echo $post['status']; ?>"><?php echo ucfirst($post['status']); ?></span></div>
                                <div class="action-buttons">
                                    <a href="?action=edit&id=<?php echo $post['id']; ?>" class="btn-primary btn-small">Edit</a>
                                    <a href="?action=delete&id=<?php echo $post['id']; ?>" class="btn-danger btn-small" onclick="return confirm('Delete this post?');">Delete</a>
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
