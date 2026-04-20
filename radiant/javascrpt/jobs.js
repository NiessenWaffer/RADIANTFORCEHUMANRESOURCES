// Pagination Variables
const jobsPerPage = 4;
let currentPage = 1;
let currentFilter = 'all';
let currentSearchTerm = '';

// Job Filtering
const filterButtons = document.querySelectorAll('.filter-btn');
const jobCards = document.querySelectorAll('.job-card');

function getFilteredJobs() {
    return Array.from(jobCards).filter(card => {
        const category = card.getAttribute('data-category');
        const title = card.querySelector('h3').textContent.toLowerCase();
        const company = card.querySelector('.job-company').textContent.toLowerCase();
        
        const matchesFilter = currentFilter === 'all' || category === currentFilter;
        const matchesSearch = currentSearchTerm === '' || 
                            title.includes(currentSearchTerm) || 
                            company.includes(currentSearchTerm);
        
        return matchesFilter && matchesSearch;
    });
}

function displayJobs(page = 1) {
    const filteredJobs = getFilteredJobs();
    const totalPages = Math.ceil(filteredJobs.length / jobsPerPage);
    
    // Calculate start and end index
    const startIndex = (page - 1) * jobsPerPage;
    const endIndex = startIndex + jobsPerPage;
    
    // Use DocumentFragment for better performance
    requestAnimationFrame(() => {
        jobCards.forEach(card => {
            card.style.display = 'none';
        });
        
        filteredJobs.slice(startIndex, endIndex).forEach(card => {
            card.style.display = '';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
        });
    });
    
    // Update pagination
    updatePagination(totalPages, page);
    currentPage = page;
}

function updatePagination(totalPages, activePage) {
    const paginationContainer = document.getElementById('jobPagination');
    paginationContainer.innerHTML = '';
    
    if (totalPages <= 1) {
        return;
    }
    
    // Previous Button
    const prevBtn = document.createElement('button');
    prevBtn.classList.add('page-btn', 'page-nav-btn');
    prevBtn.textContent = 'Prev';
    prevBtn.disabled = activePage === 1;
    prevBtn.addEventListener('click', () => {
        if (activePage > 1) {
            displayJobs(activePage - 1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    paginationContainer.appendChild(prevBtn);
    
    // Page Number Buttons
    for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.classList.add('page-btn');
        pageBtn.textContent = i;
        
        if (i === activePage) {
            pageBtn.classList.add('active');
        }
        
        pageBtn.addEventListener('click', () => {
            displayJobs(i);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        paginationContainer.appendChild(pageBtn);
    }
    
    // Next Button
    const nextBtn = document.createElement('button');
    nextBtn.classList.add('page-btn', 'page-nav-btn');
    nextBtn.textContent = 'Next';
    nextBtn.disabled = activePage === totalPages;
    nextBtn.addEventListener('click', () => {
        if (activePage < totalPages) {
            displayJobs(activePage + 1);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    paginationContainer.appendChild(nextBtn);
}

filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        button.classList.add('active');
        
        currentFilter = button.getAttribute('data-filter');
        displayJobs(1);
    });
});

// Job Details Modal
const jobDetailsModal = document.getElementById('jobDetailsModal');
const closeJobDetails = document.getElementById('closeJobDetails');
const applyNowBtn = document.getElementById('applyNowBtn');
const applicationModal = document.getElementById('applicationModal');
let currentJobData = null;

// Function to open job details
function openJobDetails(jobCard) {
    // Get all job data from data attributes
    currentJobData = {
        title: jobCard.getAttribute('data-job-title'),
        company: jobCard.getAttribute('data-company'),
        category: jobCard.getAttribute('data-job-category'),
        jobType: jobCard.getAttribute('data-job-type'),
        salary: jobCard.getAttribute('data-salary'),
        location: jobCard.getAttribute('data-location'),
        description: jobCard.getAttribute('data-description'),
        requirements: jobCard.getAttribute('data-requirements')
    };
    
    // Populate job details modal
    const colors = {
        'tech': '1e3a5f',
        'healthcare': '10b981',
        'finance': '3b82f6',
        'marketing': 'ff6b6b',
        'engineering': 'f59e0b',
        'other': '6b7280'
    };
    const color = colors[currentJobData.category] || colors['other'];
    const companyEncoded = encodeURIComponent(currentJobData.company);
    
    document.getElementById('detailsCompanyLogo').src = 
        `https://ui-avatars.com/api/?name=${companyEncoded}&background=${color}&color=fff&size=128`;
    document.getElementById('detailsJobTitle').textContent = currentJobData.title;
    document.getElementById('detailsCompany').textContent = currentJobData.company;
    document.getElementById('detailsCategory').textContent = 
        currentJobData.category.charAt(0).toUpperCase() + currentJobData.category.slice(1);
    document.getElementById('detailsJobType').textContent = 
        currentJobData.jobType.charAt(0).toUpperCase() + currentJobData.jobType.slice(1);
    document.getElementById('detailsSalary').textContent = currentJobData.salary;
    document.getElementById('detailsLocation').textContent = currentJobData.location;
    document.getElementById('detailsDescription').textContent = currentJobData.description;
    document.getElementById('detailsRequirements').textContent = currentJobData.requirements;
    
    // Show job details modal
    jobDetailsModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Initialize pagination on page load
displayJobs(1);

// Make entire job card clickable (like email) using event delegation
document.addEventListener('DOMContentLoaded', function() {
    const jobList = document.getElementById('jobList');
    if (jobList) {
        jobList.addEventListener('click', function(e) {
            const jobCard = e.target.closest('.job-card');
            if (jobCard && jobCard.classList.contains('clickable-card')) {
                e.preventDefault();
                openJobDetails(jobCard);
            }
        });
    }
});

// Job Search with debounce
const searchInput = document.getElementById('jobSearch');
const searchBtn = document.querySelector('.search-btn');
let searchTimeout;

function searchJobs() {
    currentSearchTerm = searchInput.value.toLowerCase();
    displayJobs(1);
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(searchJobs, 300);
}

searchBtn.addEventListener('click', searchJobs);
searchInput.addEventListener('input', debounceSearch);
searchInput.addEventListener('keyup', (e) => {
    if (e.key === 'Enter') {
        clearTimeout(searchTimeout);
        searchJobs();
    }
});

// Custom confirmation modal
const confirmModal = document.getElementById('confirmModal');
const confirmYes = document.getElementById('confirmYes');
const confirmNo = document.getElementById('confirmNo');
const cancelBtn = document.getElementById('cancelBtn');

function showConfirmModal() {
    confirmModal.classList.add('show');
}

function hideConfirmModal() {
    confirmModal.classList.remove('show');
}

// Handle Cancel button click - show confirmation
if (cancelBtn) {
    cancelBtn.addEventListener('click', (e) => {
        e.preventDefault();
        showConfirmModal();
    });
}

// Handle confirmation Yes button - close application modal
if (confirmYes) {
    confirmYes.addEventListener('click', () => {
        hideConfirmModal();
        applicationModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}

// Handle confirmation No button - stay on form
if (confirmNo) {
    confirmNo.addEventListener('click', () => {
        hideConfirmModal();
    });
}

// Close confirmation modal when clicking outside
if (confirmModal) {
    confirmModal.addEventListener('click', (e) => {
        if (e.target === confirmModal) {
            hideConfirmModal();
        }
    });
}

// Apply Now button - Open application form modal
applyNowBtn.addEventListener('click', () => {
    if (currentJobData) {
        // Close job details modal
        jobDetailsModal.style.display = 'none';
        
        // Open application form modal
        const modalJobTitle = document.getElementById('modalJobTitle');
        const jobPreferredInput = document.getElementById('jobPreferred');
        const jobLocationInput = document.getElementById('jobLocation');
        
        if (modalJobTitle && jobPreferredInput) {
            modalJobTitle.textContent = `Applying for: ${currentJobData.title} at ${currentJobData.company}`;
            jobPreferredInput.value = currentJobData.title;
            
            // Set location if available
            if (jobLocationInput && currentJobData.location) {
                jobLocationInput.value = currentJobData.location;
            }
            
            applicationModal.style.display = 'block';
        }
    }
});

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    if (e.target === jobDetailsModal) {
        jobDetailsModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    if (e.target === applicationModal) {
        applicationModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Add smooth transitions to job cards
jobCards.forEach(card => {
    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
});


// Social Media Share Functions (Optimized)
function shareJob(platform) {
    // Cache DOM queries
    const jobTitle = document.getElementById('detailsJobTitle')?.textContent || 'Job Opportunity';
    const jobLocation = document.getElementById('detailsLocation')?.textContent || '';
    const jobCompany = document.getElementById('detailsCompany')?.textContent || '';
    
    // Get current job ID from modal or card
    const jobId = currentJobData?.id || '';
    
    // Create shareable URL with job ID
    const baseUrl = window.location.origin + window.location.pathname;
    const shareUrl = jobId ? `${baseUrl}?job_id=${jobId}` : window.location.href;
    
    // Create share text
    const shareText = `Check out this job: ${jobTitle}${jobCompany ? ' at ' + jobCompany : ''}${jobLocation ? ' in ' + jobLocation : ''}`;
    
    // Optimized: Use object map instead of switch
    const shareActions = {
        facebook: () => {
            window.open(
                `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`,
                '_blank', 'width=600,height=400,noopener,noreferrer'
            );
            showToast('Opening Facebook...', 'success');
        },
        linkedin: () => {
            window.open(
                `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`,
                '_blank', 'width=600,height=400,noopener,noreferrer'
            );
            showToast('Opening LinkedIn...', 'success');
        },
        whatsapp: () => {
            window.open(
                `https://wa.me/?text=${encodeURIComponent(shareText + '\n\n' + shareUrl)}`,
                '_blank', 'noopener,noreferrer'
            );
            showToast('Opening WhatsApp...', 'success');
        },
        email: () => {
            window.location.href = 
                `mailto:?subject=${encodeURIComponent(jobTitle)}&body=${encodeURIComponent(shareText + '\n\n' + shareUrl)}`;
            showToast('Opening email...', 'success');
        },
        copy: () => {
            navigator.clipboard.writeText(shareUrl)
                .then(() => showToast('Link copied to clipboard!', 'success'))
                .catch(() => showToast('Failed to copy link', 'error'));
        }
    };
    
    shareActions[platform]?.();
}

// Toast Notification (Optimized)
function showToast(message, type = 'success') {
    // Optimized: Remove existing toast efficiently
    document.querySelector('.toast-notification')?.remove();
    
    // Create and append toast
    const toast = Object.assign(document.createElement('div'), {
        className: `toast-notification ${type} show`,
        textContent: message
    });
    document.body.appendChild(toast);
    
    // Auto remove with single timeout
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Job Recommendations (Simple matching based on category)
function showRecommendedJobs(currentJobCategory) {
    const allJobCards = document.querySelectorAll('.job-card');
    const recommendedJobs = [];
    
    allJobCards.forEach(card => {
        const category = card.getAttribute('data-category');
        if (category === currentJobCategory && recommendedJobs.length < 3) {
            recommendedJobs.push({
                title: card.getAttribute('data-job-title'),
                company: card.getAttribute('data-company'),
                location: card.getAttribute('data-location'),
                type: card.getAttribute('data-job-type')
            });
        }
    });
    
    return recommendedJobs;
}

// Quick Apply with Social Media
function quickApplyLinkedIn() {
    showToast('Redirecting to LinkedIn...', 'success');
    // In production, integrate with LinkedIn API
    setTimeout(() => {
        window.open('https://www.linkedin.com', '_blank');
    }, 1000);
}

function quickApplyFacebook() {
    showToast('Redirecting to Facebook...', 'success');
    // In production, integrate with Facebook API
    setTimeout(() => {
        window.open('https://www.facebook.com', '_blank');
    }, 1000);
}

// Save job to localStorage (for returning users)
function saveJobForLater(jobId, jobTitle) {
    let savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
    
    if (!savedJobs.find(job => job.id === jobId)) {
        savedJobs.push({
            id: jobId,
            title: jobTitle,
            savedAt: new Date().toISOString()
        });
        localStorage.setItem('savedJobs', JSON.stringify(savedJobs));
        showToast('Job saved for later!', 'success');
    } else {
        showToast('Job already saved', 'success');
    }
}

// Get saved jobs count
function getSavedJobsCount() {
    const savedJobs = JSON.parse(localStorage.getItem('savedJobs') || '[]');
    return savedJobs.length;
}

// PWA Install Prompt
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    // Show install button
    const installBtn = document.getElementById('installAppBtn');
    if (installBtn) {
        installBtn.style.display = 'block';
    }
});

function installPWA() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                showToast('App installed successfully!', 'success');
            }
            deferredPrompt = null;
        });
    }
}

// Track referral source
function trackReferralSource() {
    const urlParams = new URLSearchParams(window.location.search);
    const referralCode = urlParams.get('ref');
    
    if (referralCode) {
        localStorage.setItem('referralCode', referralCode);
        showToast('Referral code applied!', 'success');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    trackReferralSource();
    
    // Show saved jobs count if any
    const savedCount = getSavedJobsCount();
    if (savedCount > 0) {
        console.log(`You have ${savedCount} saved jobs`);
    }
});


// ============================================
// LINKEDIN INTEGRATION
// ============================================

/**
 * Apply with LinkedIn
 */
function applyWithLinkedIn() {
    showToast('Redirecting to LinkedIn...', 'success');
    
    // In production, this will redirect to LinkedIn OAuth
    setTimeout(() => {
        window.location.href = 'linkedin-auth.php';
    }, 1000);
}

/**
 * Handle LinkedIn callback data
 */
function handleLinkedInData() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('linkedin_auth') === 'success') {
        // LinkedIn data is in session, auto-fill form
        showToast('LinkedIn profile imported successfully!', 'success');
        
        // In production, fetch data from session and fill form
        // This is a placeholder
        setTimeout(() => {
            document.getElementById('firstName').value = 'John';
            document.getElementById('lastName').value = 'Doe';
            document.getElementById('email').value = 'john.doe@example.com';
        }, 500);
    } else if (urlParams.get('linkedin_auth') === 'error') {
        showToast('LinkedIn authentication failed. Please try again.', 'error');
    }
}

/**
 * Scroll to manual form
 */
function scrollToManualForm() {
    const formSection = document.querySelector('#applicationForm');
    if (formSection) {
        formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// ============================================
// AI JOB MATCHING
// ============================================

/**
 * Save user preferences for AI matching
 */
function saveUserPreferences(preferences) {
    localStorage.setItem('userPreferences', JSON.stringify(preferences));
    showToast('Preferences saved! We\'ll show better matches.', 'success');
}

/**
 * Get user preferences
 */
function getUserPreferences() {
    const saved = localStorage.getItem('userPreferences');
    return saved ? JSON.parse(saved) : null;
}

/**
 * Track job views for AI learning - ENHANCED
 */
function trackJobView(jobId, jobCategory, jobType, jobLocation, jobSkills) {
    let viewHistory = JSON.parse(localStorage.getItem('jobViewHistory') || '[]');
    
    // Add new view
    viewHistory.push({
        jobId: jobId,
        category: jobCategory,
        type: jobType,
        viewedAt: new Date().toISOString()
    });
    
    // Keep only last 50 views
    if (viewHistory.length > 50) {
        viewHistory = viewHistory.slice(-50);
    }
    
    localStorage.setItem('jobViewHistory', JSON.stringify(viewHistory));
}

/**
 * Get personalized job recommendations based on history
 */
function getPersonalizedRecommendations() {
    const viewHistory = JSON.parse(localStorage.getItem('jobViewHistory') || '[]');
    
    if (viewHistory.length === 0) {
        return null;
    }
    
    // Analyze viewing patterns
    const categoryCount = {};
    const typeCount = {};
    
    viewHistory.forEach(view => {
        categoryCount[view.category] = (categoryCount[view.category] || 0) + 1;
        typeCount[view.type] = (typeCount[view.type] || 0) + 1;
    });
    
    // Find most viewed category and type
    const preferredCategory = Object.keys(categoryCount).reduce((a, b) => 
        categoryCount[a] > categoryCount[b] ? a : b
    );
    
    const preferredType = Object.keys(typeCount).reduce((a, b) => 
        typeCount[a] > typeCount[b] ? a : b
    );
    
    return {
        preferredCategory,
        preferredType,
        viewCount: viewHistory.length
    };
}

/**
 * Show AI match explanation
 */
function showMatchExplanation(matchScore, reasons) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'block';
    
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 500px;">
            <div style="text-align: center; padding: 30px;">
                <div style="width: 100px; height: 100px; margin: 0 auto 20px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: bold;">
                    ${matchScore}%
                </div>
                <h2 style="color: #2c3e50; margin-bottom: 15px;">AI Match Score</h2>
                <p style="color: #64748b; margin-bottom: 25px;">Here's why this job is a great match for you:</p>
                <ul style="text-align: left; color: #475569; line-height: 1.8;">
                    ${reasons.map(reason => `<li>${reason}</li>`).join('')}
                </ul>
                <button onclick="this.closest('.modal').remove()" style="margin-top: 25px; padding: 12px 30px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Got it!
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

/**
 * Initialize AI features
 */
function initializeAIFeatures() {
    // Track job views when modal opens
    const originalShowJobDetails = window.showJobDetails;
    if (typeof originalShowJobDetails === 'function') {
        window.showJobDetails = function(jobData) {
            trackJobView(jobData.id, jobData.category, jobData.type);
            originalShowJobDetails(jobData);
        };
    }
    
    // Show personalized message if user has viewing history
    const recommendations = getPersonalizedRecommendations();
    if (recommendations && recommendations.viewCount >= 5) {
        console.log(`🤖 AI Notice: We've noticed you're interested in ${recommendations.preferredCategory} jobs. Showing more relevant matches!`);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handleLinkedInData();
    initializeAIFeatures();
});


// ============================================
// RESUME PARSER - AI AUTO-FILL
// ============================================

/**
 * Parse resume and auto-fill form
 */
function parseResumeAndFill(fileInput) {
    const file = fileInput.files[0];
    
    if (!file) {
        showToast('Please select a resume file', 'error');
        return;
    }
    
    // Show loading
    showToast('🤖 AI is analyzing your resume...', 'success');
    
    // Create form data
    const formData = new FormData();
    formData.append('resume', file);
    
    // Send to parser
    fetch('parse-resume-ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Auto-fill form
            autoFillApplicationForm(data.data);
            showToast('✅ Resume parsed! Form auto-filled.', 'success');
            
            // Show what was extracted
            showParseResults(data);
        } else {
            showToast('❌ ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Parse error:', error);
        showToast('Failed to parse resume. Please fill manually.', 'error');
    });
}

/**
 * Auto-fill application form with parsed data
 */
function autoFillApplicationForm(data) {
    // Fill text inputs
    const fields = {
        'firstName': data.firstName,
        'lastName': data.lastName,
        'email': data.email,
        'phone': data.phone,
        'address': data.address,
        'experience': data.experience,
        'coverLetter': data.coverLetter,
        'additionalMessage': data.coverLetter
    };
    
    Object.keys(fields).forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element && fields[fieldId]) {
            element.value = fields[fieldId];
            // Trigger change event
            element.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });
    
    // Highlight filled fields
    Object.keys(fields).forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element && fields[fieldId]) {
            element.style.background = '#f0fdf4';
            element.style.borderColor = '#51cf66';
            setTimeout(() => {
                element.style.background = '';
                element.style.borderColor = '';
            }, 2000);
        }
    });
}

/**
 * Show parse results modal
 */
function showParseResults(data) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'block';
    
    const skillsList = data.skills?.slice(0, 10).join(', ') || 'None detected';
    
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 600px;">
            <div style="padding: 30px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 15px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 40px;">
                        🤖
                    </div>
                    <h2 style="color: #2c3e50; margin-bottom: 10px;">AI Resume Analysis Complete!</h2>
                    <p style="color: #64748b;">Here's what we extracted from your resume:</p>
                </div>
                
                <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #2c3e50;">📧 Contact Info:</strong>
                        <p style="color: #64748b; margin: 5px 0;">${data.data.email || 'Not found'}</p>
                        <p style="color: #64748b; margin: 5px 0;">${data.data.phone || 'Not found'}</p>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #2c3e50;">💼 Experience:</strong>
                        <p style="color: #64748b; margin: 5px 0;">${data.experience_years || 0} years</p>
                    </div>
                    
                    <div>
                        <strong style="color: #2c3e50;">🎯 Skills Detected:</strong>
                        <p style="color: #64748b; margin: 5px 0;">${skillsList}</p>
                    </div>
                </div>
                
                <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; border-left: 4px solid #667eea; margin-bottom: 20px;">
                    <p style="color: #0369a1; font-size: 14px; margin: 0;">
                        ✨ <strong>Pro Tip:</strong> Review the auto-filled information and make any necessary corrections before submitting.
                    </p>
                </div>
                
                <button onclick="this.closest('.modal').remove()" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 16px;">
                    Continue to Application
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close on background click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

/**
 * Initialize resume parser
 */
function initializeResumeParser() {
    const resumeInput = document.getElementById('resume');
    
    if (resumeInput) {
        // Add change event listener
        resumeInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                // Show parse option
                const parseBtn = document.createElement('button');
                parseBtn.type = 'button';
                parseBtn.className = 'btn-parse-resume';
                parseBtn.innerHTML = '🤖 Auto-Fill from Resume';
                parseBtn.style.cssText = 'margin-top: 10px; padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%;';
                
                parseBtn.onclick = () => parseResumeAndFill(resumeInput);
                
                // Remove existing button if any
                const existing = document.querySelector('.btn-parse-resume');
                if (existing) existing.remove();
                
                // Insert after file input
                resumeInput.parentElement.appendChild(parseBtn);
            }
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handleLinkedInData();
    initializeAIFeatures();
    initializeResumeParser(); // Add resume parser
});
