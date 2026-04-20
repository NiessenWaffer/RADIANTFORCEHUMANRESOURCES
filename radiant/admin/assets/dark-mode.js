// Dark Mode Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if dark mode preference is saved
    const darkModeEnabled = localStorage.getItem('darkMode') === 'true';
    
    if (darkModeEnabled) {
        document.body.classList.add('dark-mode');
    }
    
    // Create and add settings menu
    const settingsContainer = document.createElement('div');
    settingsContainer.className = 'settings-container';
    settingsContainer.innerHTML = `
        <button class="settings-toggle" title="Settings">⚙️</button>
        <div class="settings-menu">
            <button class="settings-item" onclick="toggleDarkMode()">
                <span class="settings-icon">${darkModeEnabled ? '☀️' : '🌙'}</span>
                <span>Dark Mode</span>
            </button>
            <a href="admin-settings.php" class="settings-item">
                <span class="settings-icon">👤</span>
                <span>Admin Settings</span>
            </a>
            <button class="settings-item" onclick="openPrivacyModal()">
                <span class="settings-icon">🔒</span>
                <span>Privacy</span>
            </button>
            <button class="settings-item" onclick="openPasswordModal()">
                <span class="settings-icon">🔑</span>
                <span>Change Password</span>
            </button>
            <hr class="settings-divider">
            <a href="../auth/logout.php" class="settings-item logout">
                <span class="settings-icon">🚪</span>
                <span>Logout</span>
            </a>
        </div>
    `;
    document.body.appendChild(settingsContainer);
    
    // Settings toggle functionality
    const settingsToggle = document.querySelector('.settings-toggle');
    const settingsMenu = document.querySelector('.settings-menu');
    
    settingsToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        settingsMenu.classList.toggle('active');
    });
    
    document.addEventListener('click', function() {
        settingsMenu.classList.remove('active');
    });
});

function toggleDarkMode() {
    const body = document.body;
    const settingsIcon = document.querySelector('.settings-item:first-child .settings-icon');
    
    body.classList.toggle('dark-mode');
    
    const isDarkMode = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
    
    // Update button icon
    settingsIcon.innerHTML = isDarkMode ? '☀️' : '🌙';
}

function openPasswordModal() {
    window.location.href = 'admin-settings.php?tab=password';
}

function openPrivacyModal() {
    window.location.href = 'admin-settings.php?tab=privacy';
}
