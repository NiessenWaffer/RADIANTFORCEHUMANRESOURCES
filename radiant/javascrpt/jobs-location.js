// Jobs by Location JavaScript

function searchJobs() {
    const searchInput = document.getElementById('jobSearch');
    const searchTerm = searchInput.value.toLowerCase().trim();
    const jobCards = document.querySelectorAll('.job-card');
    
    jobCards.forEach(card => {
        const jobTitle = card.getAttribute('data-job-title').toLowerCase();
        const department = card.getAttribute('data-department').toLowerCase();
        if (jobTitle.includes(searchTerm) || department.includes(searchTerm)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// Job Details Modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('jobDetailsModal');
    const jobCards = document.querySelectorAll('.job-card[data-job-id]');
    const applyBtn = document.getElementById('applyNowBtn');
    
    // Open job details modal
    jobCards.forEach(card => {
        card.addEventListener('click', function() {
            const jobTitle = this.getAttribute('data-job-title');
            const department = this.getAttribute('data-department');
            const jobType = this.getAttribute('data-job-type');
            const salary = this.getAttribute('data-salary');
            const location = this.getAttribute('data-location');
            const description = this.getAttribute('data-description');
            const requirements = this.getAttribute('data-requirements');
            const slots = this.getAttribute('data-slots');
            
            // Update modal content
            document.getElementById('detailsJobTitle').textContent = jobTitle;
            document.getElementById('detailsDepartment').textContent = department;
            document.getElementById('detailsJobType').textContent = jobType.charAt(0).toUpperCase() + jobType.slice(1);
            document.getElementById('detailsSalary').textContent = salary;
            document.getElementById('detailsLocation').textContent = location;
            document.getElementById('detailsSlots').textContent = slots;
            document.getElementById('detailsDescription').textContent = description;
            document.getElementById('detailsRequirements').textContent = requirements;
            
            // Update logo
            const logoUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(jobTitle)}&background=1e3a5f&color=fff&size=128`;
            document.getElementById('detailsCompanyLogo').src = logoUrl;
            
            modal.style.display = 'block';
        });
    });
    
    // Close modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
    
    // Apply button - open application form modal
    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            const jobTitle = document.getElementById('detailsJobTitle').textContent;
            document.getElementById('modalJobTitle').textContent = jobTitle;
            document.getElementById('jobPreferred').value = jobTitle;
            
            // Close job details modal
            modal.style.display = 'none';
            
            // Open application modal
            document.getElementById('applicationModal').style.display = 'block';
        });
    }
    
    // Search functionality
    const searchInput = document.getElementById('jobSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchJobs();
            } else {
                searchJobs();
            }
        });
    }
});


// Close application modal function
function closeApplicationModal() {
    document.getElementById('applicationModal').style.display = 'none';
    // Reset form
    document.getElementById('applicationForm').reset();
    document.getElementById('fileList').innerHTML = '';
}

// Handle application form submission
document.addEventListener('DOMContentLoaded', function() {
    const applicationForm = document.getElementById('applicationForm');
    if (applicationForm) {
        applicationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            
            // Here you would typically send the data to your server
            // For now, we'll just show a success message
            alert('Application submitted successfully! We will contact you soon.');
            
            closeApplicationModal();
        });
    }
    
    // File upload handling
    const fileInput = document.getElementById('resume');
    const fileList = document.getElementById('fileList');
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            fileList.innerHTML = '';
            
            Array.from(this.files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <div class="file-item-info">
                        <svg class="file-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                            <polyline points="13 2 13 9 20 9"></polyline>
                        </svg>
                        <span class="file-item-name">${file.name}</span>
                        <span class="file-item-size">(${(file.size / 1024).toFixed(1)} KB)</span>
                    </div>
                    <button type="button" class="file-item-remove" onclick="removeFile(${index})">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                `;
                fileList.appendChild(fileItem);
            });
        });
    }
    
    // Character count for cover letter
    const coverLetter = document.getElementById('coverLetter');
    const charCount = document.getElementById('charCount');
    
    if (coverLetter && charCount) {
        coverLetter.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 1000) {
                this.value = this.value.substring(0, 1000);
                charCount.textContent = 1000;
            }
        });
    }
});

function removeFile(index) {
    const fileInput = document.getElementById('resume');
    const dt = new DataTransfer();
    const files = fileInput.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    fileInput.files = dt.files;
    fileInput.dispatchEvent(new Event('change'));
}
