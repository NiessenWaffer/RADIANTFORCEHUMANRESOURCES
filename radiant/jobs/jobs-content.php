<?php
// Database connection
require_once __DIR__ . '/config.php';

// Get location_id from URL if provided
$location_id = isset($_GET['location_id']) ? (int)$_GET['location_id'] : 0;
$location = null;
$city = null;

// If location_id is provided, fetch location and city details
if ($location_id) {
    $stmt = $pdo->prepare("
        SELECT l.*, c.city_name 
        FROM locations l
        LEFT JOIN cities c ON l.city_id = c.id
        WHERE l.id = ? AND l.status = 'active'
    ");
    $stmt->execute([$location_id]);
    $location = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($location) {
        $city = ['city_name' => $location['city_name'], 'id' => $location['city_id']];
    }
}

// Fetch jobs based on location_id
if ($location_id && $location) {
    // Fetch jobs for specific location
    $stmt = $pdo->prepare("
        SELECT jp.id, jp.position_title as title, '' as company, jp.department as category, 
               jp.job_type, jp.salary_range, ? as location, jp.description, jp.requirements
        FROM job_positions jp
        WHERE jp.location_id = ? AND jp.status = 'active'
        ORDER BY jp.created_at DESC
    ");
    $stmt->execute([$location['location_name'], $location_id]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch all active jobs (general jobs table)
    $stmt = $pdo->query("SELECT id, title, company, category, job_type, salary_range, location, description, requirements FROM jobs WHERE status = 'active' ORDER BY created_at DESC");
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<link rel="stylesheet" href="../design/jobs.css">

<!-- Jobs Hero -->
<section class="jobs-hero">
    <div class="container">
        <?php if ($location): ?>
            <div class="breadcrumb">
                <a href="cities.php">Cities</a>
                <span>›</span>
                <a href="locations.php?city_id=<?php echo $city['id']; ?>"><?php echo htmlspecialchars($city['city_name']); ?></a>
                <span>›</span>
                <span><?php echo htmlspecialchars($location['location_name']); ?></span>
            </div>
            <h1>Jobs at <?php echo htmlspecialchars($location['location_name']); ?></h1>
            <p><?php echo htmlspecialchars($city['city_name']); ?> • <?php echo count($jobs); ?> position<?php echo count($jobs) != 1 ? 's' : ''; ?> available</p>
        <?php else: ?>
            <h1>Find Your Dream Job</h1>
            <p>Explore exciting career opportunities with our partner companies</p>
        <?php endif; ?>
        
        <!-- Search Bar -->
        <div class="job-search-bar">
            <div class="search-input-group">
                <input type="text" id="jobSearch" placeholder="Job title, keywords, or company">
                <button class="search-btn">Search</button>
            </div>
        </div>
    </div>
</section>

<!-- Job Listings Section -->
<section class="jobs-section">
    <div class="container">
        <!-- Job Filters -->
        <div class="job-filters">
            <button class="filter-btn active" data-filter="all">All Jobs</button>
            <button class="filter-btn" data-filter="tech">Technology</button>
            <button class="filter-btn" data-filter="healthcare">Healthcare</button>
            <button class="filter-btn" data-filter="finance">Finance</button>
            <button class="filter-btn" data-filter="marketing">Marketing</button>
            <button class="filter-btn" data-filter="engineering">Engineering</button>
        </div>

        <!-- Job List -->
        <div class="job-list" id="jobList">
            <?php if (empty($jobs)): ?>
                <div class="no-jobs-message">
                    <p>No active job postings at the moment. Please check back later!</p>
                </div>
            <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                    <?php
                    // Generate color based on category
                    $colors = [
                        'tech' => '1e3a5f',
                        'healthcare' => '10b981',
                        'finance' => '3b82f6',
                        'marketing' => 'ff6b6b',
                        'engineering' => 'f59e0b',
                        'other' => '6b7280'
                    ];
                    $color = $colors[$job['category']] ?? $colors['other'];
                    $company_encoded = urlencode($job['company']);
                    ?>
                    <div class="job-card clickable-card" 
                         data-category="<?php echo htmlspecialchars($job['category']); ?>" 
                         data-type="<?php echo htmlspecialchars($job['job_type']); ?>"
                         data-job-id="<?php echo $job['id']; ?>"
                         data-job-title="<?php echo htmlspecialchars($job['title']); ?>"
                         data-company="<?php echo htmlspecialchars($job['company']); ?>"
                         data-job-category="<?php echo htmlspecialchars($job['category']); ?>"
                         data-job-type="<?php echo htmlspecialchars($job['job_type']); ?>"
                         data-salary="<?php echo htmlspecialchars($job['salary_range']); ?>"
                         data-location="<?php echo htmlspecialchars($job['location']); ?>"
                         data-description="<?php echo htmlspecialchars($job['description']); ?>"
                         data-requirements="<?php echo htmlspecialchars($job['requirements']); ?>">
                        <div class="job-image">
                            <img src="https://ui-avatars.com/api/?name=<?php echo $company_encoded; ?>&background=<?php echo $color; ?>&color=fff&size=128" alt="<?php echo htmlspecialchars($job['company']); ?>">
                        </div>
                        <div class="job-info">
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p class="job-company"><?php echo htmlspecialchars($job['company']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="job-pagination" id="jobPagination">
            <!-- Pagination buttons will be generated by JavaScript -->
        </div>
    </div>
</section>

<!-- Featured Jobs Section -->
<section class="recommended-jobs-section">
    <div class="container">
        <div style="text-align: center; margin-bottom: 20px;">
            <span style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 15px;">
                ⭐ FEATURED JOBS
            </span>
        </div>
        <h2>Latest Job Opportunities</h2>
        <p>Check out these recently posted positions</p>
        <div class="recommended-jobs-grid">
            <?php
            // Get recent jobs
            $stmt = $pdo->query("SELECT id, title, company, category, job_type, location, salary_range FROM jobs WHERE status = 'active' ORDER BY created_at DESC LIMIT 3");
            $recommended = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($recommended as $job):
            ?>
            <div class="recommended-job-card" onclick="window.location.href='?job_id=<?php echo $job['id']; ?>'" style="cursor: pointer;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <h3 style="margin: 0; flex: 1;"><?php echo htmlspecialchars($job['title']); ?></h3>
                    <span class="match-badge" style="background: #51cf66; margin-left: 10px;">
                        New
                    </span>
                </div>
                <div class="job-meta">
                    <?php echo htmlspecialchars($job['company']); ?> • <?php echo htmlspecialchars($job['location']); ?>
                </div>
                <div class="job-meta">
                    <?php echo ucfirst($job['job_type']); ?> • <?php echo htmlspecialchars($job['category']); ?>
                </div>
                <?php if (!empty($job['salary_range'])): ?>
                <div style="margin-top: 12px; padding: 10px; background: #f0f9ff; border-radius: 6px; font-size: 12px; color: #0369a1;">
                    <strong>Salary:</strong> <?php echo htmlspecialchars($job['salary_range']); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <p>Are you sure you want to close?</p>
        <div class="confirm-modal-buttons">
            <button id="confirmYes" class="confirm-btn confirm-yes">Yes</button>
            <button id="confirmNo" class="confirm-btn confirm-no">No</button>
        </div>
    </div>
</div>

<!-- Job Details Modal -->
<div id="jobDetailsModal" class="modal">
    <div class="modal-content job-details-modal">
        
        <div class="job-details-header">
            <div class="job-header-row-1">
                <div class="job-details-company-logo">
                    <img id="detailsCompanyLogo" src="" alt="Company Logo">
                </div>
                <h3 id="detailsJobTitle"></h3>
            </div>
            <div class="job-header-row-2">
                <span id="detailsCompany"></span>
                <span class="separator">•</span>
                <span id="detailsJobType"></span>
            </div>
        </div>

        <div class="job-details-meta">
            <div class="job-meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 7h-9M14 17H5M17 12H3M20 17h-3"></path>
                </svg>
                <div>
                    <span class="meta-label">Category</span>
                    <span class="meta-value" id="detailsCategory"></span>
                </div>
            </div>
            <div class="job-meta-item">
                <div class="peso-icon">₱</div>
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

        <!-- Share Job Section -->
        <div class="job-share-section">
            <h4>Share this job</h4>
            <div class="share-buttons">
                <button class="share-btn share-facebook" onclick="shareJob('facebook')" title="Share on Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Facebook
                </button>
                <button class="share-btn share-linkedin" onclick="shareJob('linkedin')" title="Share on LinkedIn">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    LinkedIn
                </button>
                <button class="share-btn share-whatsapp" onclick="shareJob('whatsapp')" title="Share on WhatsApp">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    WhatsApp
                </button>
                <button class="share-btn share-email" onclick="shareJob('email')" title="Share via Email">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Email
                </button>
                <button class="share-btn share-copy" onclick="shareJob('copy')" title="Copy Link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    Copy Link
                </button>
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
            <span class="close-modal">&times;</span>
        </div>
        
        <div class="form-error-message" id="formErrorMessage" style="display: none;">
            <span class="error-text">Please complete all required fields marked with <span class="required">*</span></span>
            <button type="button" class="close-error-btn" onclick="document.getElementById('formErrorMessage').style.display='none'">&times;</button>
        </div>
        
        <form id="applicationForm" enctype="multipart/form-data">
            <input type="hidden" id="jobPreferred" name="jobPreferred">
            <input type="hidden" id="jobLocation" name="location">
            
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
                <div class="form-group full-width">
                    <label for="middleName">Middle Name</label>
                    <input type="text" id="middleName" name="middleName">
                </div>
            </div>

            <div class="form-row form-row-three">
                <div class="form-group">
                    <label for="height">Height (cm) <span class="required">*</span></label>
                    <input type="number" id="height" name="height" min="0" step="0.1" required>
                </div>
                <div class="form-group">
                    <label for="weight">Weight (kg) <span class="required">*</span></label>
                    <input type="number" id="weight" name="weight" min="0" step="0.1" required>
                </div>
                <div class="form-group">
                    <label for="age">Age <span class="required">*</span></label>
                    <input type="number" id="age" name="age" min="18" max="100" required>
                </div>
            </div>

            <div class="form-group full-width">
                <label for="additionalMessage">Additional Message / Cover Letter</label>
                <textarea id="additionalMessage" name="additionalMessage" rows="5" placeholder="Tell us why you're the perfect fit for this position. Include your relevant experience, skills, and what makes you stand out..."></textarea>
                <small class="char-count"><span id="charCount">0</span> / 1000 characters</small>
            </div>

            <div class="form-group">
                <label for="resume">Resume / CV & Documents <span class="required">*</span></label>
                <div class="file-upload-wrapper">
                    <input type="file" id="resume" name="resume[]" accept="*/*" multiple>
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
                <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                <button type="submit" class="btn-submit">Submit Application</button>
            </div>
        </form>
    </div>
</div>

<script src="../javascrpt/jobs.js"></script>
<script src="../javascrpt/application-form.js"></script>
