<?php
$page_title = 'Select City | Radiant Force Human Resources';
$base_path = '../';
$is_jobs_folder = true;

// Fetch active cities with job count
require_once __DIR__ . '/config.php';

$stmt = $pdo->query("
    SELECT c.*, COUNT(DISTINCT jp.id) as job_count 
    FROM cities c
    LEFT JOIN locations l ON c.id = l.city_id AND l.status = 'active'
    LEFT JOIN job_positions jp ON l.id = jp.location_id AND jp.status = 'active'
    WHERE c.status = 'active'
    GROUP BY c.id
    ORDER BY c.city_name ASC
");
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Categorize cities by island
$luzon_cities = [];
$visayas_cities = [];
$mindanao_cities = [];

foreach ($cities as $city) {
    $island = $city['island'] ?? 'Luzon';
    if ($island === 'Luzon') {
        $luzon_cities[] = $city;
    } elseif ($island === 'Visayas') {
        $visayas_cities[] = $city;
    } elseif ($island === 'Mindanao') {
        $mindanao_cities[] = $city;
    }
}

// Set page content
$page_content_file = __DIR__ . '/cities-content.php';

// Include layout
require_once __DIR__ . '/../includes/layout.php';
?>
