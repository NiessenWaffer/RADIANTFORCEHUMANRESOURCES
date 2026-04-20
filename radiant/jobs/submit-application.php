<?php
header('Content-Type: application/json');

require_once __DIR__ . '/config.php';

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get form data
$jobPreferred = $_POST['jobPreferred'] ?? '';
$location = $_POST['location'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$middleName = $_POST['middleName'] ?? '';
$height = $_POST['height'] ?? null;
$weight = $_POST['weight'] ?? null;
$age = $_POST['age'] ?? null;
$additionalMessage = $_POST['additionalMessage'] ?? '';

// Validate required fields
if (empty($jobPreferred) || empty($firstName) || empty($lastName)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

// Handle file uploads
$uploadedFiles = [];
$uploadDir = __DIR__ . '/../uploads/';

// Create uploads directory if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['resume']) && !empty($_FILES['resume']['name'][0])) {
    $fileCount = count($_FILES['resume']['name']);
    
    for ($i = 0; $i < $fileCount; $i++) {
        if ($_FILES['resume']['error'][$i] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['resume']['name'][$i];
            $fileTmpName = $_FILES['resume']['tmp_name'][$i];
            $fileSize = $_FILES['resume']['size'][$i];
            
            // Validate file size (10MB max)
            if ($fileSize > 10 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'File size exceeds 10MB limit']);
                exit;
            }
            
            // Generate unique filename
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '_' . time() . '.' . $fileExt;
            $destination = $uploadDir . $uniqueName;
            
            if (move_uploaded_file($fileTmpName, $destination)) {
                $uploadedFiles[] = $uniqueName;
            }
        }
    }
}

// Check if at least one file was uploaded
if (empty($uploadedFiles)) {
    echo json_encode(['success' => false, 'message' => 'Please upload at least one resume/document']);
    exit;
}

// Insert into database
try {
    $stmt = $pdo->prepare("
        INSERT INTO job_applications 
        (job_preferred, location, first_name, last_name, middle_name, height, weight, age, additional_message, resume_files, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'unread')
    ");
    
    $stmt->execute([
        $jobPreferred,
        $location,
        $firstName,
        $lastName,
        $middleName,
        $height,
        $weight,
        $age,
        $additionalMessage,
        json_encode($uploadedFiles)
    ]);
    
    $applicationId = $pdo->lastInsertId();
    echo json_encode([
        'success' => true, 
        'message' => 'Application submitted successfully!',
        'application_id' => $applicationId,
        'files_uploaded' => count($uploadedFiles)
    ]);
} catch (PDOException $e) {
    // Log error for debugging
    error_log("Application submission error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to submit application. Please try again or contact support.',
        'error' => $e->getMessage() // Remove this in production
    ]);
}
?>
