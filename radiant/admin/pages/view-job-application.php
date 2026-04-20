<?php
session_start();
require_once __DIR__ . '/../config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

$id = $_GET['id'] ?? 0;

// Fetch application
$stmt = $pdo->prepare("SELECT * FROM job_applications WHERE id = ?");
$stmt->execute([$id]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$app) {
    header('Location: manage-job-applications.php');
    exit;
}

// Mark as read
if ($app['status'] === 'unread') {
    $stmt = $pdo->prepare("UPDATE job_applications SET status = 'read' WHERE id = ?");
    $stmt->execute([$id]);
}

// Parse resume files
$files = json_decode($app['resume_files'], true);
if (!is_array($files)) {
    $files = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application #<?php echo $app['id']; ?> | Radiant Force HR</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <link rel="stylesheet" href="../assets/dark-mode.css">
    <link rel="stylesheet" href="../assets/application-view.css">
    <link rel="stylesheet" href="../assets/application-view-compact.css?v=<?php echo time(); ?>">
    <style>
        /* Force compact styles */
        .dashboard-header { padding: 10px 0 !important; margin-bottom: 12px !important; }
        .dashboard-header h1 { font-size: 18px !important; margin-bottom: 3px !important; }
        .dashboard-header p { font-size: 12px !important; margin: 0 !important; }
        .content-section { padding: 12px !important; margin-bottom: 10px !important; }
        .section-header { margin-bottom: 8px !important; padding-bottom: 6px !important; }
        .section-header h2 { font-size: 14px !important; margin: 0 !important; font-weight: 600 !important; }
        .info-grid { gap: 8px !important; margin-top: 8px !important; }
        .info-item { padding: 10px !important; border-radius: 6px !important; }
        .info-item label { font-size: 10px !important; margin-bottom: 4px !important; }
        .info-value { font-size: 13px !important; }
        .message-box { padding: 12px !important; margin-top: 8px !important; font-size: 13px !important; line-height: 1.4 !important; }
        .files-grid { gap: 8px !important; margin-top: 8px !important; }
        .file-card { padding: 10px !important; gap: 10px !important; }
        .file-icon { width: 40px !important; height: 40px !important; }
        .file-name { font-size: 12px !important; margin-bottom: 2px !important; }
        .file-meta { font-size: 10px !important; gap: 8px !important; }
        .btn-icon { width: 32px !important; height: 32px !important; }
        .status-form { gap: 10px !important; margin-top: 8px !important; }
        .form-group label { font-size: 12px !important; margin-bottom: 4px !important; }
        .form-select { padding: 6px 8px !important; font-size: 12px !important; }
        .btn-primary, .btn-secondary { padding: 6px 12px !important; font-size: 12px !important; }
        .status-badge { padding: 3px 8px !important; font-size: 10px !important; }
        .content-wrapper { padding: 12px !important; }
        .badge { padding: 2px 6px !important; font-size: 10px !important; margin-left: 4px !important; }
        
        /* Fix Files Section */
        .files-grid { 
            display: grid !important; 
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
            gap: 8px !important; 
        }
        .file-card { 
            display: flex !important; 
            align-items: center !important; 
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 6px !important;
        }
        .file-icon { 
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            border-radius: 6px !important;
        }
        .file-icon svg { 
            width: 20px !important; 
            height: 20px !important; 
            color: white !important;
        }
        .file-ext { 
            position: absolute !important;
            bottom: 2px !important;
            right: 2px !important;
            background: rgba(0,0,0,0.3) !important;
            color: white !important;
            font-size: 8px !important;
            font-weight: 700 !important;
            padding: 1px 3px !important;
            border-radius: 2px !important;
        }
        .file-details { 
            flex: 1 !important; 
            min-width: 0 !important;
        }
        .file-name { 
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }
        .file-meta { 
            display: flex !important; 
            align-items: center !important;
        }
        .file-size { 
            font-weight: 500 !important;
        }
        .file-status { 
            display: flex !important;
            align-items: center !important;
        }
        .btn-icon { 
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: #10b981 !important;
            color: white !important;
            border-radius: 6px !important;
            text-decoration: none !important;
            flex-shrink: 0 !important;
        }
        .btn-icon:hover { 
            background: #059669 !important;
        }
        .btn-icon svg { 
            width: 16px !important; 
            height: 16px !important;
        }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="content-wrapper">
            <!-- Header -->
            <header class="dashboard-header">
                <div>
                    <h1>Application Details</h1>
                    <p>Application #<?php echo $app['id']; ?> - <?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></p>
                </div>
                <div>
                    <a href="manage-job-applications.php" class="btn-secondary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; margin-right: 6px;">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Back to List
                    </a>
                </div>
            </header>

            <!-- Personal Information Card -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Personal Information</h2>
                    <span class="status-badge status-<?php echo $app['status']; ?>">
                        <?php echo ucfirst($app['status']); ?>
                    </span>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>Full Name</label>
                        <div class="info-value">
                            <?php echo htmlspecialchars($app['first_name'] . ' ' . ($app['middle_name'] ? $app['middle_name'] . ' ' : '') . $app['last_name']); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <label>Position Applied</label>
                        <div class="info-value">
                            <strong><?php echo htmlspecialchars($app['job_preferred']); ?></strong>
                        </div>
                    </div>

                    <div class="info-item">
                        <label>Applied Location</label>
                        <div class="info-value">
                            <?php if (!empty($app['location'])): ?>
                                <span style="color: #3b82f6; font-weight: 600;">
                                    📍 <?php echo htmlspecialchars($app['location']); ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #9ca3af;">General (No specific location)</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <label>Age</label>
                        <div class="info-value"><?php echo htmlspecialchars($app['age']); ?> years old</div>
                    </div>

                    <div class="info-item">
                        <label>Height</label>
                        <div class="info-value"><?php echo htmlspecialchars($app['height']); ?> cm</div>
                    </div>

                    <div class="info-item">
                        <label>Weight</label>
                        <div class="info-value"><?php echo htmlspecialchars($app['weight']); ?> kg</div>
                    </div>

                    <div class="info-item">
                        <label>Applied Date</label>
                        <div class="info-value"><?php echo date('F d, Y h:i A', strtotime($app['created_at'])); ?></div>
                    </div>
                </div>
            </div>

            <!-- Cover Letter -->
            <?php if (!empty($app['additional_message'])): ?>
            <div class="content-section">
                <div class="section-header">
                    <h2>Cover Letter / Additional Message</h2>
                </div>
                <div class="message-box">
                    <?php echo nl2br(htmlspecialchars($app['additional_message'])); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Attached Files -->
            <?php if (!empty($files)): ?>
            <div class="content-section">
                <div class="section-header">
                    <h2>Attached Files</h2>
                    <span class="badge badge-info"><?php echo count($files); ?> file(s)</span>
                </div>
                
                <div class="files-grid">
                    <?php foreach ($files as $file): ?>
                        <?php
                        $filePath = '../../uploads/' . $file;
                        $fileExists = file_exists($filePath);
                        $fileSize = $fileExists ? filesize($filePath) : 0;
                        $fileSizeMB = number_format($fileSize / 1024 / 1024, 2);
                        $fileExt = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
                        
                        // Determine file icon color
                        $iconColors = [
                            'PDF' => '#ef4444',
                            'DOC' => '#3b82f6',
                            'DOCX' => '#3b82f6',
                            'TXT' => '#6b7280',
                            'JPG' => '#10b981',
                            'JPEG' => '#10b981',
                            'PNG' => '#10b981'
                        ];
                        $iconColor = $iconColors[$fileExt] ?? '#6b7280';
                        ?>
                        <div class="file-card">
                            <div class="file-icon" style="background: <?php echo $iconColor; ?>;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <span class="file-ext"><?php echo $fileExt; ?></span>
                            </div>
                            <div class="file-details">
                                <div class="file-name" title="<?php echo htmlspecialchars($file); ?>">
                                    <?php echo htmlspecialchars(strlen($file) > 30 ? substr($file, 0, 27) . '...' : $file); ?>
                                </div>
                                <div class="file-meta">
                                    <?php if ($fileExists): ?>
                                        <span class="file-size"><?php echo $fileSizeMB; ?> MB</span>
                                        <span class="file-status" style="color: #10b981;">● Available</span>
                                    <?php else: ?>
                                        <span class="file-status" style="color: #ef4444;">● Not Found</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($fileExists): ?>
                                <a href="../../uploads/<?php echo urlencode($file); ?>" class="btn-icon" download title="Download">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Update Application Status</h2>
                </div>
                
                <form method="POST" action="manage-job-applications.php" class="status-form">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                    
                    <div class="form-group">
                        <label for="status">Change Status:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="unread" <?php echo $app['status'] === 'unread' ? 'selected' : ''; ?>>Unread</option>
                            <option value="read" <?php echo $app['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                            <option value="shortlisted" <?php echo $app['status'] === 'shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                            <option value="rejected" <?php echo $app['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            <option value="hired" <?php echo $app['status'] === 'hired' ? 'selected' : ''; ?>>Hired</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; margin-right: 6px;">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/admin-script.js"></script>
</body>
</html>
