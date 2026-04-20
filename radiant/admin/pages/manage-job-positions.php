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
            $location_ids = isset($_POST['location_ids']) ? $_POST['location_ids'] : [];
            if (!empty($location_ids)) {
                $duplicates = [];
                $added = 0;
                
                foreach ($location_ids as $location_id) {
                    // Check if this position already exists in this location
                    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM job_positions WHERE location_id = ? AND LOWER(position_title) = LOWER(?)");
                    $stmt->execute([$location_id, $_POST['position_title']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($result['count'] > 0) {
                        // Get location name for warning
                        $stmt = $pdo->prepare("SELECT l.location_name FROM locations l WHERE l.id = ?");
                        $stmt->execute([$location_id]);
                        $loc = $stmt->fetch(PDO::FETCH_ASSOC);
                        $duplicates[] = $loc['location_name'];
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO job_positions (location_id, position_title, department, job_type, description, requirements, salary_range, slots_available, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')");
                        $stmt->execute([$location_id, $_POST['position_title'], $_POST['department'], $_POST['job_type'], $_POST['description'], $_POST['requirements'], $_POST['salary_range'], $_POST['slots_available']]);
                        $added++;
                    }
                }
                
                if (!empty($duplicates)) {
                    $warning = "⚠️ Warning: Position '" . htmlspecialchars($_POST['position_title']) . "' already exists in: " . implode(", ", $duplicates) . ". Added to " . $added . " location(s).";
                } else {
                    $success = "Job position added to " . $added . " location(s) successfully!";
                }
            } else {
                $error = "Please select at least one location!";
            }
        } elseif ($_POST['action'] === 'edit') {
            $stmt = $pdo->prepare("UPDATE job_positions SET location_id = ?, position_title = ?, department = ?, job_type = ?, description = ?, requirements = ?, salary_range = ?, slots_available = ? WHERE id = ?");
            $stmt->execute([$_POST['location_id'], $_POST['position_title'], $_POST['department'], $_POST['job_type'], $_POST['description'], $_POST['requirements'], $_POST['salary_range'], $_POST['slots_available'], $_POST['id']]);
            $success = "Job position updated successfully!";
        } elseif ($_POST['action'] === 'toggle') {
            $stmt = $pdo->prepare("UPDATE job_positions SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $success = "Job position status updated!";
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM job_positions WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $success = "Job position deleted successfully!";
        }
    }
}

// Fetch all active locations
$stmt = $pdo->query("SELECT l.*, c.city_name FROM locations l LEFT JOIN cities c ON l.city_id = c.id WHERE l.status = 'active' ORDER BY c.city_name, l.location_name");
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all job positions with location info
$stmt = $pdo->query("
    SELECT jp.*, l.location_name, c.city_name, COUNT(la.id) as application_count
    FROM job_positions jp
    LEFT JOIN locations l ON jp.location_id = l.id
    LEFT JOIN cities c ON l.city_id = c.id
    LEFT JOIN location_applications la ON jp.id = la.job_position_id
    GROUP BY jp.id
    ORDER BY c.city_name, l.location_name, jp.position_title
");
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Job Positions | Radiant Force HR</title>
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
                    <h1>Manage Job Positions</h1>
                    <p>Add and manage job positions at locations</p>
                </div>
                <button class="btn-primary" onclick="showAddModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Job Position
                </button>
            </header>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (isset($warning)): ?>
                <div class="alert alert-warning"><?php echo $warning; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Position Title</th>
                            <th>Location</th>
                            <th>City</th>
                            <th>Type</th>
                            <th>Slots</th>
                            <th>Applications</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($positions)): ?>
                            <tr>
                                <td colspan="8" class="no-data">No job positions found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($positions as $position): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($position['position_title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($position['location_name'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($position['city_name'] ?? '-'); ?></td>
                                    <td><?php echo ucfirst($position['job_type']); ?></td>
                                    <td><?php echo $position['slots_available']; ?></td>
                                    <td><?php echo $position['application_count']; ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="id" value="<?php echo $position['id']; ?>">
                                            <label class="toggle-switch">
                                                <input type="checkbox" <?php echo $position['status'] === 'active' ? 'checked' : ''; ?> onchange="this.form.submit()">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-edit" onclick='editPosition(<?php echo json_encode($position); ?>)' title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>
                                            <form method="POST" onsubmit="return confirm('Delete this job position?');" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $position['id']; ?>">
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
    <div id="positionModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 700px; margin: 50px auto;">
            <div class="form-container">
                <h2 id="modalTitle">Add Job Position</h2>
                <form method="POST" class="job-form">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="id" id="positionId">
                    
                    <div class="form-group">
                        <label>Locations <span class="required">*</span></label>
                        <div class="custom-select-wrapper">
                            <button type="button" class="btn-toggle-locations" id="locationsHeader" onclick="toggleLocationsPanel(event)">
                                <span id="locationsToggleText">Select Locations</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <div class="locations-search-header" id="locationsSearchHeader" style="display: none; padding: 2px; border-bottom: 1px solid #ddd;">
                                <input type="text" id="locationsSearch" class="locations-search-input" placeholder="Search locations..." onkeyup="filterLocations()" onclick="event.stopPropagation()">
                            </div>
                            <div class="locations-checkbox-group" id="locationsCheckboxGroup" style="display: none;">
                            <?php foreach ($locations as $location): ?>
                                <label class="checkbox-label" data-location="<?php echo strtolower(htmlspecialchars($location['location_name'])); ?>" data-city="<?php echo strtolower(htmlspecialchars($location['city_name'])); ?>">
                                    <input type="checkbox" name="location_ids[]" value="<?php echo $location['id']; ?>" class="location-checkbox" onchange="updateLocationsText()">
                                    <span><?php echo htmlspecialchars($location['location_name']); ?></span>
                                </label>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        
                        <div class="form-group">
                            <label>Position Title <span class="required">*</span></label>
                            <input type="text" name="position_title" id="positionTitle" required placeholder="e.g., Cashier, Sales Associate">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Department</label>
                            <input type="text" name="department" id="department" placeholder="e.g., Retail, Operations">
                        </div>
                        
                        <div class="form-group">
                            <label>Job Type</label>
                            <select name="job_type" id="jobType">
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="contract">Contract</option>
                                <option value="temporary">Temporary</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Salary Range</label>
                            <input type="text" name="salary_range" id="salaryRange" placeholder="e.g., ₱15,000 - ₱18,000">
                        </div>
                        
                        <div class="form-group">
                            <label>Slots Available</label>
                            <input type="number" name="slots_available" id="slotsAvailable" value="1" min="1">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="Job description..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Requirements</label>
                        <textarea name="requirements" id="requirements" rows="3" placeholder="Job requirements..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn-submit" onclick="return validateLocations()">Save Position</button>
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
            document.getElementById('modalTitle').textContent = 'Add Job Position';
            document.getElementById('formAction').value = 'add';
            document.getElementById('positionId').value = '';
            document.querySelectorAll('.location-checkbox').forEach(cb => cb.checked = false);
            closeLocationsPanel();
            document.getElementById('locationsToggleText').textContent = 'Select Locations';
            document.getElementById('positionTitle').value = '';
            document.getElementById('department').value = '';
            document.getElementById('jobType').value = 'full-time';
            document.getElementById('salaryRange').value = '';
            document.getElementById('slotsAvailable').value = '1';
            document.getElementById('description').value = '';
            document.getElementById('requirements').value = '';
            document.getElementById('positionModal').style.display = 'block';
        }

        function editPosition(position) {
            document.getElementById('modalTitle').textContent = 'Edit Job Position';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('positionId').value = position.id;
            document.getElementById('locationId').value = position.location_id;
            document.getElementById('positionTitle').value = position.position_title;
            document.getElementById('department').value = position.department || '';
            document.getElementById('jobType').value = position.job_type;
            document.getElementById('salaryRange').value = position.salary_range || '';
            document.getElementById('slotsAvailable').value = position.slots_available;
            document.getElementById('description').value = position.description || '';
            document.getElementById('requirements').value = position.requirements || '';
            document.getElementById('positionModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('positionModal').style.display = 'none';
        }
        
        function validateLocations() {
            const checkboxes = document.querySelectorAll('.location-checkbox');
            const isChecked = Array.from(checkboxes).some(cb => cb.checked);
            
            if (!isChecked) {
                alert('Please select at least one location!');
                return false;
            }
            return true;
        }
        
        function toggleLocationsPanel(event) {
            event.preventDefault();
            const panel = document.getElementById('locationsCheckboxGroup');
            const searchHeader = document.getElementById('locationsSearchHeader');
            const btn = document.getElementById('locationsHeader');
            const search = document.getElementById('locationsSearch');
            
            if (panel.style.display === 'none') {
                panel.style.display = 'flex';
                searchHeader.style.display = 'block';
                btn.classList.add('active');
                search.focus();
                filterLocations();
            } else {
                panel.style.display = 'none';
                searchHeader.style.display = 'none';
                btn.classList.remove('active');
                search.value = '';
                filterLocations();
            }
        }
        
        function filterLocations() {
            const searchInput = document.getElementById('locationsSearch').value.toLowerCase();
            const labels = document.querySelectorAll('.checkbox-label');
            
            labels.forEach(label => {
                const location = label.getAttribute('data-location') || '';
                const city = label.getAttribute('data-city') || '';
                const matches = location.includes(searchInput) || city.includes(searchInput);
                
                label.style.display = matches ? '' : 'none';
            });
        }
        
        function updateLocationsText() {
            const checkboxes = document.querySelectorAll('.location-checkbox:checked');
            const text = document.getElementById('locationsToggleText');
            
            if (checkboxes.length === 0) {
                text.textContent = 'Select Locations';
            } else if (checkboxes.length === 1) {
                text.textContent = '1 location selected';
            } else {
                text.textContent = checkboxes.length + ' locations selected';
            }
        }
        
        function closeLocationsPanel() {
            const panel = document.getElementById('locationsCheckboxGroup');
            const searchHeader = document.getElementById('locationsSearchHeader');
            const btn = document.getElementById('locationsHeader');
            const search = document.getElementById('locationsSearch');
            panel.style.display = 'none';
            searchHeader.style.display = 'none';
            btn.classList.remove('active');
            search.value = '';
            filterLocations();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('positionModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>

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
        
        /* Toggle Button */
        .btn-toggle-locations {
            width: 100%;
            padding: 4px 6px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px 6px 0 0;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #333;
            transition: all 0.2s;
            height: 24px;
            line-height: 16px;
        }
        
        .btn-toggle-locations:hover {
            background: #f0f0f0;
            border-color: #999;
        }
        
        .btn-toggle-locations svg {
            transition: transform 0.2s;
            flex-shrink: 0;
        }
        
        .btn-toggle-locations.active svg {
            transform: rotate(180deg);
        }
        
        /* Locations Search */
        .locations-search-header {
            background: #fff;
            border: 1px solid #ddd;
            border-top: none;
        }
        
        .locations-search-input {
            width: 100%;
            padding: 2px 4px;
            border: none;
            font-size: 10px;
            box-sizing: border-box;
            background: #fff;
            height: 24px;
            line-height: 20px;
        }
        
        .locations-search-input:focus {
            outline: none;
            background: #f9f9f9;
        }
        
        /* Checkbox Styles */
        .locations-checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 1px;
            padding: 2px;
            background: #f5f5f5;
            border-radius: 0 0 6px 6px;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 100px;
            overflow-y: auto;
        }
        

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 3px;
            cursor: pointer;
            padding: 2px 3px;
            border-radius: 2px;
            transition: background 0.2s;
            user-select: none;
            font-size: 10px;
        }
        
        .checkbox-label:hover {
            background: #e8e8e8;
        }
        
        .checkbox-label input[type="checkbox"] {
            cursor: pointer;
            width: 12px;
            height: 12px;
            accent-color: #4CAF50;
            flex-shrink: 0;
        }
        
        .checkbox-label span {
            font-size: 10px;
            color: #333;
        }
    </style>
</body>
</html>
