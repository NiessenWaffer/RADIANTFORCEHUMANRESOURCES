<?php
require_once __DIR__ . '/config.php';

// Get location_id from URL
$location_id = isset($_GET['location_id']) ? (int)$_GET['location_id'] : 0;

if (!$location_id) {
    header('Location: cities.php');
    exit;
}

// Fetch location and city details
$stmt = $pdo->prepare("
    SELECT l.*, c.city_name, c.region 
    FROM locations l
    JOIN cities c ON l.city_id = c.id
    WHERE l.id = ? AND l.status = 'active'
");
$stmt->execute([$location_id]);
$location = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$location) {
    header('Location: cities.php');
    exit;
}

// Fetch active job positions for this location
$stmt = $pdo->prepare("
    SELECT * FROM job_positions 
    WHERE location_id = ? AND status = 'active' 
    ORDER BY created_at DESC
");
$stmt->execute([$location_id]);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Jobs at <?php echo htmlspecialchars($location['location_name']); ?> | Radiant Force Human Resources</title>
    <link rel="stylesheet" href="../design/styles.css">
    <link rel="stylesheet" href="../design/jobs.css">
    <link rel="stylesheet" href="../design/cities.css">
</head>
<body>
    <!-- Header / Navigation -->
    <header id="header">
        <div class="container">
            <a href="../../radiantforcehumanresources.php" class="logo">
                <img src="../imagees/logo.png" alt="Radiant Force HR Logo">
                <span>Radiant Force Human Resources</span>
            </a>
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav id="nav">
                <a href="../../radiantforcehumanresources.php">Home</a>
                <a href="jobs.php" class="active">Jobs</a>
                <a href="blog.php">Blog</a>
                <a href="contact-form.php">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Jobs Hero -->
    <section class="jobs-hero">
        <div class="container">
            <div class="breadcrumb">
                <a href="cities.php">Cities</a>
                <span>›</span>
                <a href="locations.php?city_id=<?php echo $location['city_id']; ?>"><?php echo htmlspecialchars($location['city_name']); ?></a>
                <span>›</span>
                <span><?php echo htmlspecialchars($location['location_name']); ?></span>
            </div>
            <h1>Jobs at <?php echo htmlspecialchars($location['location_name']); ?></h1>
            <p><?php echo htmlspecialchars($location['city_name']); ?> • <?php echo count($jobs); ?> position<?php echo count($jobs) != 1 ? 's' : ''; ?> available</p>
            
            <!-- Search Bar -->
            <div class="job-search-bar">
                <div class="search-input-group">
                    <input type="text" id="jobSearch" placeholder="Search positions...">
                    <button class="search-btn" onclick="searchJobs()">Search</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Listings Section -->
    <section class="jobs-section">
        <div class="container">
            <!-- Job List -->
            <div class="job-list" id="jobList">
                <?php if (empty($jobs)): ?>
                    <div class="no-jobs-message">
                        <p>No active job postings at this location at the moment.</p>
                        <a href="locations.php?city_id=<?php echo $location['city_id']; ?>" class="btn-primary" style="margin-top: 1rem; display: inline-block;">Back to Locations</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($jobs as $job): ?>
                        <?php
                        $colors = ['1e3a5f', '10b981', '3b82f6', 'ff6b6b', 'f59e0b', '6b7280'];
                        $color = $colors[array_rand($colors)];
                        $position_encoded = urlencode($job['position_title']);
                        ?>
                        <div class="job-card clickable-card" 
                             data-job-id="<?php echo $job['id']; ?>"
                             data-job-title="<?php echo htmlspecialchars($job['position_title']); ?>"
                             data-department="<?php echo htmlspecialchars($job['department'] ?? 'General'); ?>"
                             data-job-type="<?php echo htmlspecialchars($job['job_type']); ?>"
                             data-salary="<?php echo htmlspecialchars($job['salary_range'] ?? 'Competitive'); ?>"
                             data-location="<?php echo htmlspecialchars($location['location_name']); ?>"
                             data-description="<?php echo htmlspecialchars($job['description']); ?>"
                             data-requirements="<?php echo htmlspecialchars($job['requirements']); ?>"
                             data-slots="<?php echo $job['slots_available']; ?>">
                            <div class="job-image">
                                <img src="https://ui-avatars.com/api/?name=<?php echo $position_encoded; ?>&background=<?php echo $color; ?>&color=fff&size=128" alt="<?php echo htmlspecialchars($job['position_title']); ?>">
                            </div>
                            <div class="job-info">
                                <h3><?php echo htmlspecialchars($job['position_title']); ?></h3>
                                <p class="job-company"><?php echo htmlspecialchars($job['department'] ?? 'General'); ?> • <?php echo ucfirst($job['job_type']); ?> • <?php echo $job['slots_available']; ?> slot<?php echo $job['slots_available'] != 1 ? 's' : ''; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php 
    $is_jobs_folder = true; // Set variable for footer paths
    include '../includes/footer.php'; 
    ?>

    <!-- Job Details Modal -->
    <div id="jobDetailsModal" class="modal">
        <div class="modal-content job-details-modal">
            
            <div class="job-details-header">
                <div class="job-header-row-1">
                    <div class="job-details-company-logo">
                        <img id="detailsCompanyLogo" src="" alt="Position Logo">
                    </div>
                    <h3 id="detailsJobTitle"></h3>
                </div>
                <div class="job-header-row-2">
                    <span id="detailsDepartment"></span>
                    <span class="separator">•</span>
                    <span id="detailsJobType"></span>
                </div>
            </div>

            <div class="job-details-meta">
                <div class="job-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <div>
                        <span class="meta-label">Salary Range</span>
                        <span class="meta-value" id="detailsSalary"></span>
                    </div>
                </div>
                <div class="job-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <div>
                        <span class="meta-label">Location</span>
                        <span class="meta-value" id="detailsLocation"></span>
                    </div>
                </div>
                <div class="job-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <div>
                        <span class="meta-label">Slots Available</span>
                        <span class="meta-value" id="detailsSlots"></span>
                    </div>
                </div>
            </div>

            <div class="job-details-body">
                <div class="job-details-section">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Job Description
                    </h3>
                    <div class="job-details-content" id="detailsDescription"></div>
                </div>

                <div class="job-details-section">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        Requirements
                    </h3>
                    <div class="job-details-content" id="detailsRequirements"></div>
                </div>
            </div>

            <div class="job-details-footer">
                <button class="btn-cancel-modal" onclick="document.getElementById('jobDetailsModal').style.display='none'">
                    Cancel
                </button>
                <button class="btn-apply-now" id="applyNowBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                    Apply Now
                </button>
            </div>
        </div>
    </div>

    <!-- Application Form Modal -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-content">
                    <div class="modal-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <polyline points="17 11 19 13 23 9"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h2>Job Application Form</h2>
                        <p class="modal-job-title" id="modalJobTitle"></p>
                    </div>
                </div>
                <span class="close-modal" onclick="closeApplicationModal()">&times;</span>
            </div>
            
            <div class="form-instructions">
                <p><strong>Please complete all required fields marked with <span class="required">*</span></strong></p>
                <p>Your information will be kept confidential and used solely for recruitment purposes.</p>
            </div>
            
            <form id="applicationForm" enctype="multipart/form-data">
                <input type="hidden" id="jobPreferred" name="jobPreferred">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name <span class="required">*</span></label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name <span class="required">*</span></label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="2" placeholder="Your complete address"></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="experience">Work Experience</label>
                    <textarea id="experience" name="experience" rows="4" placeholder="Briefly describe your relevant work experience..."></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="coverLetter">Cover Letter</label>
                    <textarea id="coverLetter" name="coverLetter" rows="5" placeholder="Tell us why you're the perfect fit for this position..."></textarea>
                    <small class="char-count"><span id="charCount">0</span> / 1000 characters</small>
                </div>

                <div class="form-group">
                    <label for="resume">Resume / CV & Documents <span class="required">*</span></label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="resume" name="resume[]" accept="*/*" multiple required>
                        <div class="file-upload-display">
                            <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <span class="upload-text">Click to upload or drag and drop</span>
                        </div>
                    </div>
                    <div id="fileList" class="file-list"></div>
                    <small class="file-info">Upload 1 or more files (Max 10MB each)</small>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeApplicationModal()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; margin-right: 4px;">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Back
                    </button>
                    <button type="submit" class="btn-submit">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../javascrpt/script.js"></script>
    <script src="../javascrpt/jobs-location.js"></script>
</body>
</html>
