<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <img src="../../imagees/logo.png" alt="Radiant Force HR">
        <span>Admin Panel</span>
    </div>
    
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span>Dashboard</span>
        </a>
        
        <a href="manage-cities.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-cities.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <span>Manage Cities</span>
        </a>
        
        <a href="manage-locations.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-locations.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Manage Locations</span>
        </a>
        
        <a href="manage-job-positions.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-job-positions.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Job Positions</span>
        </a>

        <a href="manage-job-applications.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-job-applications.php' || basename($_SERVER['PHP_SELF']) == 'view-job-application.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span>Applications</span>
        </a>

        <div style="border-top: 1px solid #e0e0e0; margin: 15px 0; padding-top: 15px;">
            <p style="font-size: 11px; color: #999; text-transform: uppercase; margin: 0 15px 10px; font-weight: bold;">Communication</p>
        </div>

        <a href="manage-inquiries.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-inquiries.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span>Contact Inquiries</span>
        </a>

        <a href="manage-newsletter.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-newsletter.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span>Newsletter</span>
        </a>

        <a href="manage-blog.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-blog.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="12" y1="13" x2="8" y2="13"></line>
                <line x1="12" y1="17" x2="8" y2="17"></line>
            </svg>
            <span>Blog Posts</span>
        </a>

        <div style="border-top: 1px solid #e0e0e0; margin: 15px 0; padding-top: 15px;">
            <p style="font-size: 11px; color: #999; text-transform: uppercase; margin: 0 15px 10px; font-weight: bold;">Analytics & SEO</p>
        </div>

        <a href="analytics.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="2" x2="12" y2="22"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <span>Analytics</span>
        </a>

        <a href="manage-seo.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-seo.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <span>SEO Management</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <a href="admin-settings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'admin-settings.php' ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m3.08 3.08l4.24 4.24M1 12h6m6 0h6m-1.78 7.78l-4.24-4.24m-3.08-3.08l-4.24-4.24"></path>
            </svg>
            <span>Settings</span>
        </a>
        <a href="../auth/logout.php" class="logout-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Logout</span>
        </a>
    </div>
</aside>
