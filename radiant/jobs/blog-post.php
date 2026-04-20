<?php
$page_title = 'Blog Post - Radiant Force HR';
$base_path = '../';
$is_jobs_folder = true;

require_once __DIR__ . '/../admin/config.php';

$post_id = $_GET['id'] ?? 0;

if (!$post_id) {
    header('Location: blog.php');
    exit;
}

// Get post
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = :id AND status = 'published'");
$stmt->execute([':id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: blog.php');
    exit;
}

// Get related posts
$stmt = $pdo->prepare("
    SELECT * FROM blog_posts 
    WHERE status = 'published' 
    AND id != :id 
    AND category = :category
    ORDER BY published_at DESC
    LIMIT 3
");
$stmt->execute([':id' => $post_id, ':category' => $post['category']]);
$related_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set page content
$page_content_file = __DIR__ . '/blog-post-content.php';

// Include layout
require_once __DIR__ . '/../includes/layout.php';
?>
