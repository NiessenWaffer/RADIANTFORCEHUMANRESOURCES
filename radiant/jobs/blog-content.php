<?php
require_once __DIR__ . '/../admin/config.php';

$page = $_GET['page'] ?? 1;
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$limit = 9;
$offset = ($page - 1) * $limit;

// Build query
$where_parts = ["status = 'published'"];
$params = [];

if ($category) {
    $where_parts[] = "category = :category";
    $params[':category'] = $category;
}
if ($search) {
    $where_parts[] = "(title LIKE :search OR excerpt LIKE :search OR content LIKE :search)";
    $params[':search'] = "%$search%";
}

$where = implode(" AND ", $where_parts);

// Get total posts
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blog_posts WHERE $where");
$stmt->execute($params);
$total_posts = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
$total_pages = ceil($total_posts / $limit);

// Get posts
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE $where ORDER BY published_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories
$result = $pdo->query("SELECT DISTINCT category FROM blog_posts WHERE status = 'published' AND category IS NOT NULL ORDER BY category");
$categories = $result->fetchAll(PDO::FETCH_ASSOC);

// Update view count
if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    $stmt = $pdo->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = :id");
    $stmt->execute([':id' => $post_id]);
}
?>

<style>
    .blog-hero { background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 73, 94, 0.85) 100%), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80') center/cover no-repeat; color: white; padding: 80px 20px; text-align: center; }
    .blog-hero h1 { font-size: 42px; margin-bottom: 15px; font-weight: 700; }
    .blog-hero p { font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto; }
    @media (max-width: 768px) {
        .blog-hero { padding: 40px 20px; }
        .blog-hero h1 { font-size: 32px; margin-bottom: 10px; }
        .blog-hero p { font-size: 15px; }
    }
    @media (max-width: 480px) {
        .blog-hero { padding: 25px 15px; }
        .blog-hero h1 { font-size: 24px; margin-bottom: 8px; }
        .blog-hero p { font-size: 13px; }
    }
    .blog-container { max-width: 1200px; margin: 0 auto; padding: 60px 20px; }
    @media (max-width: 768px) {
        .blog-container { padding: 40px 20px; }
    }
    @media (max-width: 480px) {
        .blog-container { padding: 25px 15px; }
    }
    .blog-controls { display: flex; gap: 20px; margin-bottom: 40px; flex-wrap: wrap; align-items: center; }
    .search-box { flex: 1; min-width: 250px; display: flex; }
    .search-box input { flex: 1; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 6px 0 0 6px; font-family: 'Inter', sans-serif; }
    .search-box button { padding: 12px 25px; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border: none; border-radius: 0 6px 6px 0; cursor: pointer; font-weight: 600; }
    .category-filter { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 30px; }
    .category-btn { padding: 10px 18px; border: 2px solid #e2e8f0; background: white; border-radius: 6px; cursor: pointer; transition: all 0.3s; color: #475569; font-weight: 500; }
    .category-btn:hover { border-color: #5dd3e0; color: #2c3e50; }
    .category-btn.active { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-color: #2c3e50; }
    .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; margin-bottom: 40px; }
    .blog-card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s; border: 1px solid #e2e8f0; }
    .blog-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.12); }
    .blog-card-image { width: 100%; height: 200px; background: linear-gradient(135deg, #2c3e50 0%, #5dd3e0 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; }
    .blog-card-content { padding: 20px; }
    .blog-card-category { display: inline-block; background: #f0fdf4; color: #166534; padding: 6px 14px; border-radius: 20px; font-size: 12px; margin-bottom: 10px; font-weight: 600; }
    .blog-card-title { color: #2c3e50; margin: 10px 0; font-size: 18px; font-weight: 700; line-height: 1.4; }
    .blog-card-excerpt { color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 12px; }
    .blog-card-meta { display: flex; justify-content: space-between; font-size: 12px; color: #94a3b8; margin-bottom: 12px; }
    .blog-card-link { color: #5dd3e0; text-decoration: none; font-weight: 600; display: inline-block; }
    .blog-card-link:hover { color: #2c3e50; }
    .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 40px; flex-wrap: wrap; }
    .pagination a, .pagination span { padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 6px; text-decoration: none; color: #2c3e50; font-weight: 500; transition: all 0.3s; font-size: 14px; }
    .pagination a:hover { background: #f8fafc; border-color: #5dd3e0; }
    .pagination .active { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-color: #2c3e50; }
    .no-posts { text-align: center; padding: 60px 20px; }
    .no-posts h3 { color: #2c3e50; font-size: 24px; margin-bottom: 10px; }
    .no-posts p { color: #94a3b8; }
    @media (max-width: 768px) {
        .blog-grid { grid-template-columns: 1fr; gap: 20px; }
        .blog-controls { flex-direction: column; align-items: stretch; gap: 15px; margin-bottom: 25px; }
        .search-box { flex-direction: column; }
        .search-box input { border-radius: 6px; font-size: 13px; padding: 10px; }
        .search-box button { border-radius: 6px; margin-top: 8px; font-size: 13px; padding: 10px; }
        .category-filter { margin-bottom: 20px; gap: 8px; }
        .category-btn { padding: 8px 14px; font-size: 12px; }
        .blog-card-image { height: 150px; }
        .blog-card-content { padding: 15px; }
        .blog-card-title { font-size: 16px; margin: 8px 0; }
        .blog-card-excerpt { font-size: 13px; margin-bottom: 10px; }
        .blog-card-meta { font-size: 11px; }
        .pagination a, .pagination span { padding: 8px 12px; font-size: 12px; }
        .no-posts { padding: 40px 20px; }
        .no-posts h3 { font-size: 20px; }
    }
    @media (max-width: 480px) {
        .blog-controls { gap: 10px; margin-bottom: 20px; }
        .search-box input { font-size: 12px; padding: 8px; }
        .search-box button { font-size: 12px; padding: 8px; }
        .category-filter { margin-bottom: 15px; gap: 6px; }
        .category-btn { padding: 6px 12px; font-size: 11px; }
        .blog-grid { gap: 15px; margin-bottom: 30px; }
        .blog-card-image { height: 120px; font-size: 36px; }
        .blog-card-content { padding: 12px; }
        .blog-card-category { font-size: 11px; padding: 4px 10px; margin-bottom: 8px; }
        .blog-card-title { font-size: 15px; margin: 6px 0; }
        .blog-card-excerpt { font-size: 12px; margin-bottom: 8px; }
        .blog-card-meta { font-size: 10px; }
        .pagination a, .pagination span { padding: 6px 10px; font-size: 11px; }
        .no-posts { padding: 30px 15px; }
        .no-posts h3 { font-size: 18px; }
    }
</style>

<div class="blog-hero">
    <h1>HR Insights & News</h1>
    <p>Stay updated with the latest recruitment trends, HR tips, and industry insights</p>
</div>

<div class="blog-container">
    <div class="blog-controls">
        <div class="search-box">
            <form method="GET" style="display: flex;">
                <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" style="padding: 12px 20px; background: #1e3a5f; color: white; border: none; border-radius: 0 4px 4px 0; cursor: pointer;">Search</button>
            </form>
        </div>
    </div>

    <?php if (!empty($categories)): ?>
        <div class="category-filter">
            <a href="blog.php" class="category-btn <?php echo empty($category) ? 'active' : ''; ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="?category=<?php echo urlencode($cat['category']); ?>" class="category-btn <?php echo $category === $cat['category'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat['category']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($posts)): ?>
        <div class="no-posts">
            <h3>No posts found</h3>
            <p>Check back soon for new content</p>
        </div>
    <?php else: ?>
        <div class="blog-grid">
            <?php foreach ($posts as $post): ?>
                <div class="blog-card">
                    <div class="blog-card-image">
                        📰 Blog Post
                    </div>
                    <div class="blog-card-content">
                        <?php if ($post['category']): ?>
                            <div class="blog-card-category"><?php echo htmlspecialchars($post['category']); ?></div>
                        <?php endif; ?>
                        <h3 class="blog-card-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="blog-card-excerpt"><?php echo htmlspecialchars(substr($post['excerpt'] ?? $post['content'], 0, 150)); ?>...</p>
                        <div class="blog-card-meta">
                            <span><?php echo date('M d, Y', strtotime($post['published_at'])); ?></span>
                            <span><?php echo $post['views']; ?> views</span>
                        </div>
                        <a href="blog-post.php?id=<?php echo $post['id']; ?>" class="blog-card-link">Read More →</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1<?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">« First</a>
                    <a href="?page=<?php echo $page - 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">‹ Prev</a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <?php if ($i === $page): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next ›</a>
                    <a href="?page=<?php echo $total_pages; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Last »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
