<?php
$page_title = 'Testimonials - Radiant Force HR';
$base_path = '../';
$is_jobs_folder = true;

require_once __DIR__ . '/../admin/config.php';

// Get all active testimonials
$result = $pdo->query("SELECT * FROM testimonials WHERE status = 'active' ORDER BY created_at DESC");
$testimonials = $result->fetchAll(PDO::FETCH_ASSOC);

// Set page content
$page_content_file = __DIR__ . '/testimonials-content.php';

// Include layout
require_once __DIR__ . '/../includes/layout.php';
?>
