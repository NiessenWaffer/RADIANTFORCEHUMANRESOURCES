<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/resume-parser.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$file = $_FILES['resume'];
$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];

// Validate file type
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Please upload PDF, DOCX, or TXT']);
    exit;
}

// Validate file size (5MB max)
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum 5MB']);
    exit;
}

// Create temp directory if not exists
$tempDir = __DIR__ . '/../uploads/temp/';
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true);
}

// Save uploaded file temporarily
$tempFile = $tempDir . uniqid() . '_' . basename($file['name']);
if (!move_uploaded_file($file['tmp_name'], $tempFile)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    exit;
}

try {
    // Parse resume
    $parser = new ResumeParser();
    $parsedData = $parser->parseResume($tempFile);
    
    // Get form data
    $formData = $parser->getFormData($parsedData);
    
    // Delete temp file
    unlink($tempFile);
    
    // Return parsed data
    echo json_encode([
        'success' => true,
        'message' => 'Resume parsed successfully!',
        'data' => $formData,
        'skills' => $parsedData['skills'] ?? [],
        'experience_years' => $parsedData['experience']['years'] ?? 0
    ]);
    
} catch (Exception $e) {
    // Delete temp file on error
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Error parsing resume: ' . $e->getMessage()
    ]);
}
?>
