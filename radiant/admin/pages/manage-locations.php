<?php
session_start();
require_once __DIR__ . '/../config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Check for duplicate location in same city
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM locations WHERE city_id = ? AND LOWER(location_name) = LOWER(?)");
            $stmt->execute([$_POST['city_id'], $_POST['location_name']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                $error = "⚠️ Warning: This location already exists in this city!";
            } else {
                $stmt = $pdo->prepare("INSERT INTO locations (city_id, location_name, address, landmark, status) VALUES (?, ?, ?, ?, 'active')");
                $stmt->execute([$_POST['city_id'], $_POST['location_name'], $_POST['address'], $_POST['landmark']]);
                $success = "Location added successfully!";
            }
        } elseif ($_POST['action'] === 'edit') {
            // Check for duplicate location in same city (excluding current record)
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM locations WHERE city_id = ? AND LOWER(location_name) = LOWER(?) AND id != ?");
            $stmt->execute([$_POST['city_id'], $_POST['location_name'], $_POST['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                $error = "⚠️ Warning: This location already exists in this city!";
            } else {
                $stmt = $pdo->prepare("UPDATE locations SET city_id = ?, location_name = ?, address = ?, landmark = ? WHERE id = ?");
                $stmt->execute([$_POST['city_id'], $_POST['location_name'], $_POST['address'], $_POST['landmark'], $_POST['id']]);
                $success = "Location updated successfully!";
            }
        } elseif ($_POST['action'] === 'toggle') {
            $stmt = $pdo->prepare("UPDATE locations SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $success = "Location status updated!";
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM locations WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $success = "Location deleted successfully!";
        }
    }
}

// Fetch all locations with city info
$stmt = $pdo->query("
    SELECT l.*, c.city_name, c.island, COUNT(jp.id) as job_count
    FROM locations l
    LEFT JOIN cities c ON l.city_id = c.id
    LEFT JOIN job_positions jp ON l.id = jp.location_id
    GROUP BY l.id
    ORDER BY c.city_name ASC, l.location_name ASC
");
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all active cities for dropdown
$stmt = $pdo->query("SELECT id, city_name, island FROM cities WHERE status = 'active' ORDER BY city_name ASC");
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locations | Radiant Force HR</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <link rel="stylesheet" href="../assets/dark-mode.css">
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <header class="dashboard-header">
                <div>
                    <h1>Manage Locations</h1>
                    <p>Add and manage business locations</p>
                </div>
                <button class="btn-primary" onclick="showAddModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Location
                </button>
            </header>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-warning"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Location Name</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($locations)): ?>
                            <tr>
                                <td colspan="4" class="no-data">No locations found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($locations as $location): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($location['location_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($location['city_name']); ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="id" value="<?php echo $location['id']; ?>">
                                            <label class="toggle-switch">
                                                <input type="checkbox" <?php echo $location['status'] === 'active' ? 'checked' : ''; ?> onchange="this.form.submit()">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-edit" onclick='editLocation(<?php echo json_encode($location); ?>)' title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>
                                            <form method="POST" onsubmit="return confirm('Delete this location?');" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $location['id']; ?>">
                                                <button type="submit" class="btn-delete" title="Delete">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add/Edit Modal -->
    <div id="locationModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 600px; margin: 50px auto;">
            <div class="form-container">
                <h2 id="modalTitle">Add Location</h2>
                <form method="POST" class="job-form">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="id" id="locationId">
                    
                    <div class="form-group">
                        <label>City <span class="required">*</span></label>
                        <div class="custom-select-wrapper">
                            <div class="custom-select" id="customCitySelect">
                                <div class="select-header" id="selectHeader">
                                    <div class="select-display" id="selectDisplay">Select City</div>
                                    <input type="text" id="citySearch" class="city-search-input" placeholder="Search city..." onkeyup="filterCities()" onclick="event.stopPropagation()" style="display: none;">
                                </div>
                                <div class="select-options" id="cityOptions" style="display: none;">
                                    <div class="select-option" data-value="">Select City</div>
                                    <?php foreach ($cities as $city): ?>
                                        <div class="select-option" data-value="<?php echo $city['id']; ?>" data-city="<?php echo strtolower(htmlspecialchars($city['city_name'])); ?>" data-island="<?php echo strtolower(htmlspecialchars($city['island'] ?? '')); ?>">
                                            <?php echo htmlspecialchars($city['city_name']); ?>
                                            <?php if ($city['island']): ?>
                                                <span class="island-tag">(<?php echo htmlspecialchars($city['island']); ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <input type="hidden" name="city_id" id="cityId" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Location Name <span class="required">*</span></label>
                        <input type="text" name="location_name" id="locationName" required placeholder="e.g., SM Manila, BGC Office">
                    </div>
                    
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" id="address" rows="2" placeholder="Full address..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Landmark</label>
                        <input type="text" name="landmark" id="landmark" placeholder="e.g., Near City Hall">
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn-submit">Save Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            });
            
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                mobileOverlay.classList.remove('active');
            });
        }

        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Location';
            document.getElementById('formAction').value = 'add';
            document.getElementById('locationId').value = '';
            document.getElementById('cityId').value = '';
            document.getElementById('locationName').value = '';
            document.getElementById('address').value = '';
            document.getElementById('landmark').value = '';
            document.getElementById('locationModal').style.display = 'block';
        }

        function editLocation(location) {
            document.getElementById('modalTitle').textContent = 'Edit Location';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('locationId').value = location.id;
            document.getElementById('cityId').value = location.city_id;
            document.getElementById('locationName').value = location.location_name;
            document.getElementById('address').value = location.address || '';
            document.getElementById('landmark').value = location.landmark || '';
            document.getElementById('locationModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('locationModal').style.display = 'none';
            closeDropdown();
        }
        
        function filterCities() {
            const searchInput = document.getElementById('citySearch').value.toLowerCase();
            const options = document.querySelectorAll('.select-option');
            
            options.forEach(option => {
                const cityName = option.getAttribute('data-city') || '';
                const island = option.getAttribute('data-island') || '';
                const matches = cityName.includes(searchInput) || island.includes(searchInput);
                
                option.style.display = matches ? '' : 'none';
            });
        }
        
        function selectCity(element) {
            const value = element.getAttribute('data-value');
            const text = element.textContent.trim();
            
            document.getElementById('cityId').value = value;
            document.getElementById('selectDisplay').textContent = text || 'Select City';
            
            // Update selected state
            document.querySelectorAll('.select-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            
            // Close dropdown
            closeDropdown();
        }
        
        function openDropdown() {
            const search = document.getElementById('citySearch');
            const options = document.getElementById('cityOptions');
            const display = document.getElementById('selectDisplay');
            
            display.style.display = 'none';
            search.style.display = 'block';
            options.style.display = 'block';
            search.focus();
            filterCities();
        }
        
        function closeDropdown() {
            const search = document.getElementById('citySearch');
            const options = document.getElementById('cityOptions');
            const display = document.getElementById('selectDisplay');
            
            search.style.display = 'none';
            options.style.display = 'none';
            display.style.display = 'block';
            search.value = '';
            filterCities();
        }
        
        // Toggle dropdown on click
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('selectHeader');
            const customSelect = document.getElementById('customCitySelect');
            
            if (header) {
                header.addEventListener('click', function(e) {
                    if (document.getElementById('cityOptions').style.display === 'none') {
                        openDropdown();
                    } else {
                        closeDropdown();
                    }
                });
            }
            
            if (customSelect) {
                customSelect.addEventListener('click', function(e) {
                    if (e.target.classList.contains('select-option')) {
                        selectCity(e.target);
                    }
                });
            }
        });

        window.onclick = function(event) {
            const modal = document.getElementById('locationModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
    <script src="../assets/dark-mode.js"></script>

    <style>
        /* Alert Styles */
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-color: #ffeaa7;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }
        
        /* Toggle Switch Styles */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
            cursor: pointer;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #2c2c2c;
            transition: 0.3s;
            border-radius: 26px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        .toggle-switch input:checked + .toggle-slider {
            background-color: #4CAF50;
        }
        
        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }
        
        .toggle-switch:hover .toggle-slider {
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
        }
        
        /* Custom Select Styles */
        .custom-select-wrapper {
            position: relative;
        }
        
        .custom-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fff;
            overflow: hidden;
        }
        
        .select-header {
            padding: 3px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }
        
        .select-display {
            padding: 4px 6px;
            font-size: 11px;
            color: #333;
            min-height: 20px;
            line-height: 20px;
        }
        
        .city-search-input {
            width: 100%;
            padding: 2px 4px;
            border: none;
            font-size: 10px;
            box-sizing: border-box;
            background: #fff;
            height: 24px;
            line-height: 20px;
        }
        
        .city-search-input:focus {
            outline: none;
            background: #f9f9f9;
        }
        
        .select-options {
            max-height: 120px;
            overflow-y: auto;
        }
        
        .select-option {
            padding: 4px 6px;
            cursor: pointer;
            font-size: 11px;
            transition: background 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .select-option:hover {
            background: #f0f0f0;
        }
        
        .select-option.selected {
            background: #e8f5e9;
            color: #2e7d32;
            font-weight: 500;
        }
        
        .island-tag {
            font-size: 9px;
            color: #999;
            margin-left: 4px;
        }
    </style>
</body>
</html>
