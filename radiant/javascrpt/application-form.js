// Get job information from URL parameters (if on separate page)
const urlParams = new URLSearchParams(window.location.search);
const jobTitleParam = urlParams.get('job') || '';
const companyParam = urlParams.get('company') || '';

// Set job information only if elements exist (for separate application page)
const jobTitleElement = document.getElementById('jobTitle');
const jobPreferredInput = document.getElementById('jobPreferred');

if (jobTitleElement && jobTitleParam) {
    jobTitleElement.textContent = companyParam ? `${jobTitleParam} at ${companyParam}` : jobTitleParam;
}

if (jobPreferredInput && jobTitleParam && !jobPreferredInput.value) {
    jobPreferredInput.value = companyParam ? `${jobTitleParam} - ${companyParam}` : jobTitleParam;
}

// Auto-save functionality
const AUTOSAVE_KEY = 'jobApplicationFormData';
let uploadedFiles = [];

// Update file list display
function updateFileList() {
    const fileListContainer = document.getElementById('fileList');
    if (!fileListContainer) return;
    
    fileListContainer.innerHTML = '';
    
    uploadedFiles.forEach((file, index) => {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.innerHTML = `
            <div class="file-item-info">
                <svg class="file-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="file-item-name">${file.name}</span>
                <span class="file-item-size">${fileSize} MB</span>
            </div>
            <button type="button" class="file-item-remove" data-index="${index}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        `;
        fileListContainer.appendChild(fileItem);
    });
    
    // Add remove button listeners
    document.querySelectorAll('.file-item-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            uploadedFiles.splice(index, 1);
            updateFileList();
            
            if (uploadedFiles.length === 0) {
                const resumeInput = document.getElementById('resume');
                if (resumeInput) resumeInput.value = '';
            }
        });
    });
}

// Auto-save form data
function saveFormData() {
    const formData = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        middleName: document.getElementById('middleName').value,
        height: document.getElementById('height').value,
        weight: document.getElementById('weight').value,
        age: document.getElementById('age').value,
        additionalMessage: document.getElementById('additionalMessage').value,
        jobPreferred: document.getElementById('jobPreferred').value,
        location: document.getElementById('jobLocation')?.value || '',
        timestamp: new Date().getTime()
    };
    localStorage.setItem(AUTOSAVE_KEY, JSON.stringify(formData));
}

// Load saved form data
function loadFormData() {
    const savedData = localStorage.getItem(AUTOSAVE_KEY);
    if (savedData) {
        try {
            const formData = JSON.parse(savedData);
            const hoursSinceLastSave = (new Date().getTime() - formData.timestamp) / (1000 * 60 * 60);
            
            if (hoursSinceLastSave < 24) {
                document.getElementById('firstName').value = formData.firstName || '';
                document.getElementById('lastName').value = formData.lastName || '';
                document.getElementById('middleName').value = formData.middleName || '';
                document.getElementById('height').value = formData.height || '';
                document.getElementById('weight').value = formData.weight || '';
                document.getElementById('age').value = formData.age || '';
                document.getElementById('additionalMessage').value = formData.additionalMessage || '';
                
                const charCount = document.getElementById('charCount');
                if (charCount && formData.additionalMessage) {
                    charCount.textContent = formData.additionalMessage.length;
                }
                
                return true;
            }
        } catch (e) {
            console.error('Error loading saved data:', e);
        }
    }
    return false;
}

// Clear saved form data
function clearFormData() {
    localStorage.removeItem(AUTOSAVE_KEY);
    uploadedFiles = [];
}

// Setup auto-save on input change
function setupAutoSave() {
    const formInputs = document.querySelectorAll('input[type="text"], input[type="number"], textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            saveFormData();
        });
    });
}

// Validation functions
function showValidationMessage(input, message) {
    const formGroup = input.closest('.form-group');
    let errorMsg = formGroup.querySelector('.error-message');
    
    if (!errorMsg) {
        errorMsg = document.createElement('small');
        errorMsg.className = 'error-message';
        formGroup.appendChild(errorMsg);
    }
    
    errorMsg.textContent = message;
    input.style.borderColor = '#e74c3c';
}

function clearValidationMessage(input) {
    const formGroup = input.closest('.form-group');
    const errorMsg = formGroup.querySelector('.error-message');
    
    if (errorMsg) {
        errorMsg.remove();
    }
    
    input.style.borderColor = '';
}

// Real-time validation
const firstNameInput = document.getElementById('firstName');
const lastNameInput = document.getElementById('lastName');

if (firstNameInput) {
    firstNameInput.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            showValidationMessage(this, 'First name is required');
        } else {
            clearValidationMessage(this);
        }
    });
}

if (lastNameInput) {
    lastNameInput.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            showValidationMessage(this, 'Last name is required');
        } else {
            clearValidationMessage(this);
        }
    });
}

// Character counter
const additionalMessage = document.getElementById('additionalMessage');
const charCount = document.getElementById('charCount');

if (additionalMessage && charCount) {
    additionalMessage.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 1000) {
            this.value = this.value.substring(0, 1000);
            charCount.textContent = 1000;
        }
        
        if (length > 900) {
            charCount.style.color = '#dc2626';
        } else if (length > 700) {
            charCount.style.color = '#f59e0b';
        } else {
            charCount.style.color = '';
        }
    });
}

// File upload handling
const resumeInput = document.getElementById('resume');
if (resumeInput) {
    resumeInput.addEventListener('change', function() {
    const newFiles = Array.from(this.files);
    
    if (newFiles.length === 0) return;
    
    for (const file of newFiles) {
        if (file.size > 10 * 1024 * 1024) {
            alert(`⚠️ File "${file.name}" is too large. Maximum size is 10MB.`);
            this.value = '';
            return;
        }
        
        const isDuplicate = uploadedFiles.some(f => f.name === file.name && f.size === file.size);
        if (!isDuplicate) {
            uploadedFiles.push(file);
        }
    }
    
    updateFileList();
    
    const uploadDisplay = this.parentElement.querySelector('.file-upload-display');
    uploadDisplay.innerHTML = `
        <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="stroke: #10b981;">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="upload-text" style="color: #10b981; font-weight: 600;">✓ ${uploadedFiles.length} file(s) uploaded</span>
    `;
    uploadDisplay.style.borderColor = '#10b981';
    uploadDisplay.style.background = '#f0fdf4';
    
    clearValidationMessage(this);
    this.value = '';
    });
}

// Form submission
const applicationForm = document.getElementById('applicationForm');

if (applicationForm) {
    applicationForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    let isValid = true;
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const height = document.getElementById('height').value;
    const weight = document.getElementById('weight').value;
    const age = document.getElementById('age').value;
    
    if (!firstName || firstName.trim() === '') {
        showValidationMessage(document.getElementById('firstName'), 'First name is required');
        isValid = false;
    }
    
    if (!lastName || lastName.trim() === '') {
        showValidationMessage(document.getElementById('lastName'), 'Last name is required');
        isValid = false;
    }
    
    if (!height || height.trim() === '') {
        showValidationMessage(document.getElementById('height'), 'Height is required');
        isValid = false;
    }
    
    if (!weight || weight.trim() === '') {
        showValidationMessage(document.getElementById('weight'), 'Weight is required');
        isValid = false;
    }
    
    if (!age || age.trim() === '') {
        showValidationMessage(document.getElementById('age'), 'Age is required');
        isValid = false;
    }
    
    if (uploadedFiles.length === 0) {
        showValidationMessage(document.getElementById('resume'), 'Please upload at least one file');
        isValid = false;
    }
    
    if (!isValid) {
        const errorMessage = document.getElementById('formErrorMessage');
        if (errorMessage) {
            errorMessage.style.display = 'flex';
            errorMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        return;
    }
    
    // Hide error message if form is valid
    const errorMessage = document.getElementById('formErrorMessage');
    if (errorMessage) {
        errorMessage.style.display = 'none';
    }
    
    const submitBtn = applicationForm.querySelector('.btn-submit');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Submitting...';
    submitBtn.disabled = true;
    
    const formData = new FormData(applicationForm);
    formData.delete('resume[]');
    uploadedFiles.forEach(file => {
        formData.append('resume[]', file);
    });
    
    fetch('submit-application.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show beautiful success modal
            showSuccessModal(firstName, lastName, uploadedFiles);
            clearFormData();
        } else {
            showErrorMessage('❌ Error: ' + data.message);
        }
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    })
    .catch(error => {
        console.error('Submission error:', error);
        showErrorMessage('❌ Error submitting application. Please try again or contact support.');
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
    });
}

/**
 * Show success modal with application details
 */
function showSuccessModal(firstName, lastName, files) {
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.3s ease-out;
    `;
    
    // Create modal
    const modal = document.createElement('div');
    modal.className = 'success-modal-content';
    modal.style.cssText = `
        background: white;
        border-radius: 16px;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease-out;
    `;
    
    // Check if mobile
    const isMobile = window.innerWidth <= 480;
    
    // Get job title
    const jobTitle = document.getElementById('jobPreferred').value || 'Position';
    
    // Build file list HTML - Mobile optimized
    const fileListHTML = files.map(file => {
        const fileName = file.name.length > 30 ? file.name.substring(0, 27) + '...' : file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        return `
        <div style="display: flex; align-items: center; gap: ${isMobile ? '6px' : '8px'}; padding: ${isMobile ? '6px' : '8px'}; background: #f0f9ff; border-radius: 6px; margin-bottom: ${isMobile ? '4px' : '6px'};">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: ${isMobile ? '14px' : '16px'}; height: ${isMobile ? '14px' : '16px'}; color: #0369a1; flex-shrink: 0;">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span style="font-size: ${isMobile ? '10px' : '12px'}; color: #0c4a6e; flex: 1; word-break: break-all; line-height: 1.3;" title="${file.name}">${fileName}</span>
            <span style="font-size: ${isMobile ? '9px' : '11px'}; color: #64748b; white-space: nowrap;">${fileSize}MB</span>
        </div>
        `;
    }).join('');
    
    modal.innerHTML = `
        <div style="padding: 24px;">
            <!-- Success Icon -->
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" style="width: 36px; height: 36px;">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Title -->
            <h2 style="text-align: center; color: #1f2937; font-size: 22px; margin: 0 0 8px 0; font-weight: 700;">
                Application Submitted!
            </h2>
            <p style="text-align: center; color: #6b7280; font-size: 14px; margin: 0 0 24px 0;">
                Thank you for applying to our company
            </p>
            
            <!-- Application Details -->
            <div style="background: #f9fafb; border-radius: 12px; padding: ${isMobile ? '12px' : '16px'}; margin-bottom: ${isMobile ? '16px' : '20px'};">
                <div style="font-size: ${isMobile ? '10px' : '12px'}; color: #6b7280; margin-bottom: ${isMobile ? '10px' : '12px'}; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Application Details
                </div>
                
                <div style="display: flex; align-items: start; gap: ${isMobile ? '8px' : '12px'}; margin-bottom: ${isMobile ? '10px' : '12px'};">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: ${isMobile ? '16px' : '18px'}; height: ${isMobile ? '16px' : '18px'}; color: #6b7280; flex-shrink: 0; margin-top: 2px;">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <div style="flex: 1;">
                        <div style="font-size: ${isMobile ? '9px' : '11px'}; color: #9ca3af; margin-bottom: 2px;">Applicant Name</div>
                        <div style="font-size: ${isMobile ? '12px' : '14px'}; color: #1f2937; font-weight: 600;">${firstName} ${lastName}</div>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; gap: ${isMobile ? '8px' : '12px'}; margin-bottom: ${isMobile ? '10px' : '12px'};">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: ${isMobile ? '16px' : '18px'}; height: ${isMobile ? '16px' : '18px'}; color: #6b7280; flex-shrink: 0; margin-top: 2px;">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <div style="flex: 1;">
                        <div style="font-size: ${isMobile ? '9px' : '11px'}; color: #9ca3af; margin-bottom: 2px;">Position Applied</div>
                        <div style="font-size: ${isMobile ? '12px' : '14px'}; color: #1f2937; font-weight: 600; line-height: 1.3;">${jobTitle}</div>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; gap: ${isMobile ? '8px' : '12px'};">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: ${isMobile ? '16px' : '18px'}; height: ${isMobile ? '16px' : '18px'}; color: #6b7280; flex-shrink: 0; margin-top: 2px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <div style="flex: 1;">
                        <div style="font-size: ${isMobile ? '9px' : '11px'}; color: #9ca3af; margin-bottom: ${isMobile ? '4px' : '6px'};">Attached Files (${files.length})</div>
                        ${fileListHTML}
                    </div>
                </div>
            </div>
            
            <!-- What's Next -->
            <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 12px; padding: 16px; margin-bottom: 20px; border: 1px solid #bfdbfe;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #1e40af;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span style="font-size: 13px; color: #1e40af; font-weight: 700;">What Happens Next?</span>
                </div>
                
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <div style="width: 24px; height: 24px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">1</div>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; color: #1e40af; font-weight: 600; margin-bottom: 2px;">Email Confirmation</div>
                        <div style="font-size: 11px; color: #1e40af; line-height: 1.4;">You'll receive a confirmation email shortly</div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <div style="width: 24px; height: 24px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">2</div>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; color: #1e40af; font-weight: 600; margin-bottom: 2px;">Application Review</div>
                        <div style="font-size: 11px; color: #1e40af; line-height: 1.4;">Our HR team will review within 3-5 business days</div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <div style="width: 24px; height: 24px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">3</div>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; color: #1e40af; font-weight: 600; margin-bottom: 2px;">Interview Invitation</div>
                        <div style="font-size: 11px; color: #1e40af; line-height: 1.4;">Qualified candidates will be contacted for interview</div>
                    </div>
                </div>
            </div>
            
            <!-- Important Note -->
            <div style="background: #fef3c7; border-left: 3px solid #f59e0b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                <div style="font-size: 12px; color: #92400e; line-height: 1.5;">
                    <strong>📧 Important:</strong> Please check your email regularly (including spam folder) for updates on your application status.
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="success-modal-buttons" style="display: grid; grid-template-columns: ${isMobile ? '1fr' : '1fr 1fr'}; gap: 10px; margin-bottom: 12px;">
                <button onclick="window.location.href='../../radiantforcehumanresources.php'" style="padding: ${isMobile ? '12px' : '14px'}; background: white; color: #059669; border: 2px solid #10b981; border-radius: 8px; font-size: ${isMobile ? '13px' : '14px'}; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    🏠 Back to Home
                </button>
                <button onclick="closeSuccessModal()" style="padding: ${isMobile ? '12px' : '14px'}; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-size: ${isMobile ? '13px' : '14px'}; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3); transition: all 0.3s;">
                    💼 Browse More Jobs
                </button>
            </div>
            
            <div style="text-align: center;">
                <span style="font-size: 11px; color: #9ca3af;">Good luck with your application! 🍀</span>
            </div>
        </div>
    `;
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    // Close function
    window.closeSuccessModal = function() {
        overlay.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => {
            overlay.remove();
            window.location.href = 'jobs.php';
        }, 300);
    };
    
    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeSuccessModal();
        }
    });
}

/**
 * Show error message
 */
function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #fee2e2;
        border-left: 4px solid #ef4444;
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        max-width: 350px;
        z-index: 100000;
        animation: slideIn 0.3s ease-out;
    `;
    
    errorDiv.innerHTML = `
        <div style="display: flex; align-items: start; gap: 12px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #dc2626; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #991b1b; line-height: 1.5;">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 20px; line-height: 1;">×</button>
        </div>
    `;
    
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => errorDiv.remove(), 300);
    }, 5000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
    
    /* Mobile responsive styles */
    @media (max-width: 480px) {
        .success-modal-content {
            font-size: 12px !important;
        }
        .success-modal-content h2 {
            font-size: 18px !important;
        }
        .success-modal-content p {
            font-size: 12px !important;
        }
        
        /* Stack buttons vertically on mobile */
        .success-modal-buttons {
            grid-template-columns: 1fr !important;
        }
    }
    
    /* Hover effects for buttons */
    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
`;
document.head.appendChild(style);

// Load saved data on page load
loadFormData();
setupAutoSave();
