<?php
$page_title = 'Locations | Radiant Force Human Resources';
$base_path = '../';
$is_jobs_folder = true;

require_once __DIR__ . '/config.php';

// Get city_id from URL
$city_id = isset($_GET['city_id']) ? (int)$_GET['city_id'] : 0;

if (!$city_id) {
    header('Location: cities.php');
    exit;
}

// Fetch city details
$stmt = $pdo->prepare("SELECT * FROM cities WHERE id = ? AND status = 'active'");
$stmt->execute([$city_id]);
$city = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$city) {
    header('Location: cities.php');
    exit;
}

// Fetch active locations with job count
$stmt = $pdo->prepare("
    SELECT l.*, COUNT(jp.id) as job_count 
    FROM locations l
    LEFT JOIN job_positions jp ON l.id = jp.location_id AND jp.status = 'active'
    WHERE l.city_id = ? AND l.status = 'active'
    GROUP BY l.id
    ORDER BY l.location_name ASC
");
$stmt->execute([$city_id]);
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set page content
$page_content_file = __DIR__ . '/locations-content.php';

// Include layout
require_once __DIR__ . '/../includes/layout.php';
?>
