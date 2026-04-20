<?php
$page_title = 'FAQs - Radiant Force HR';
$base_path = '../';
$is_jobs_folder = true;

require_once __DIR__ . '/../admin/config.php';

$category = $_GET['category'] ?? '';

// Get all active FAQs
$where = "WHERE status = 'active'";
if ($category) {
    $where .= " AND category = '" . $pdo->quote($category) . "'";
}

$result = $pdo->query("SELECT * FROM faqs $where ORDER BY order_by ASC, created_at DESC");
$faqs = $result->fetchAll(PDO::FETCH_ASSOC);

// Get categories
$categories_result = $pdo->query("SELECT DISTINCT category FROM faqs WHERE status = 'active' AND category IS NOT NULL AND category != '' ORDER BY category");
$categories = $categories_result->fetchAll(PDO::FETCH_ASSOC);

// Set page content
$page_content_file = __DIR__ . '/faqs-content.php';

// Include layout
require_once __DIR__ . '/../includes/layout.php';
?>
