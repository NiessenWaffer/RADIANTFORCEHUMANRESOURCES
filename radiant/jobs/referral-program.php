<?php
$page_title = 'Referral Program - Radiant Force HR';
$base_path = '../';
$is_jobs_folder = true;

require_once __DIR__ . '/../admin/config.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $referrer_name = $_POST['referrer_name'] ?? '';
    $referrer_email = $_POST['referrer_email'] ?? '';
    $referred_candidate_name = $_POST['referred_candidate_name'] ?? '';
    $referred_candidate_email = $_POST['referred_candidate_email'] ?? '';
    $position_id = $_POST['position_id'] ?? null;
    
    if (empty($referrer_name) || empty($referrer_email) || empty($referred_candidate_name) || empty($referred_candidate_email)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO referral_program (referrer_name, referrer_email, referred_candidate_name, referred_candidate_email, position_id) VALUES (:referrer_name, :referrer_email, :referred_candidate_name, :referred_candidate_email, :position_id)");
            $stmt->execute([
                ':referrer_name' => $referrer_name,
                ':referrer_email' => $referrer_email,
                ':referred_candidate_name' => $referred_candidate_name,
                ':referred_candidate_email' => $referred_candidate_email,
                ':position_id' => $position_id
            ]);
            $success = true;
        } catch (PDOException $e) {
            $error = 'Error submitting referral. Please try again.';
        }
    }
}

// Get active positions
$positions_result = $pdo->query("SELECT id, position_title FROM job_positions WHERE status = 'active' ORDER BY position_title");
$positions = $positions_result->fetchAll(PDO::FETCH_ASSOC);

// Set page content
$page_content_file = __DIR__ . '/referral-program-content.php';

// Include layout
require_once __DIR__ . '/../includes/layout.php';
?>
