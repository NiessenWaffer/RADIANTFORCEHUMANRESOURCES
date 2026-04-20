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
            $stmt = $pdo->prepare("INSERT INTO cities (city_name, island, status) VALUES (?, ?, 'active')");
            $stmt->execute([$_POST['city_name'], $_POST['island']]);
            $success = "City added successfully!";
        } elseif ($_POST['action'] === 'edit') {
            $stmt = $pdo->prepare("UPDATE cities SET city_name = ?, island = ? WHERE id = ?");
            $stmt->execute([$_POST['city_name'], $_POST['island'], $_POST['id']]);
            $success = "City updated successfully!";
        } elseif ($_POST['action'] === 'toggle') {
            $stmt = $pdo->prepare("UPDATE cities SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $success = "City status updated!";
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM cities WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $success = "City deleted successfully!";
        }
    }
}

// Fetch all cities
$stmt = $pdo->query("
    SELECT c.*, COUNT(DISTINCT l.id) as location_count, COUNT(DISTINCT jp.id) as job_count
    FROM cities c
    LEFT JOIN locations l ON c.id = l.city_id
    LEFT JOIN job_positions jp ON l.id = jp.location_id
    GROUP BY c.id
    ORDER BY c.city_name ASC
");
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cities | Radiant Force HR</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
    <link rel="stylesheet" href="../assets/dark-mode.css">
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-main">
            <?php include '../includes/header.php'; ?>
            
            <div class="admin-content">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="content-section">
                    <div class="section-header">
                        <h2>Cities Management</h2>
                        <button class="btn-primary" onclick="showAddModal()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Add City
                        </button>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>City Name</th>
                                    <th>Island</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($cities)): ?>
                                    <tr>
                                        <td colspan="4" class="no-data">No cities found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($cities as $city): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($city['city_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($city['island'] ?? '-'); ?></td>
                                            <td>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="action" value="toggle">
                                                    <input type="hidden" name="id" value="<?php echo $city['id']; ?>">
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" <?php echo $city['status'] === 'active' ? 'checked' : ''; ?> onchange="this.form.submit()">
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn-edit" onclick='editCity(<?php echo json_encode($city); ?>)' title="Edit">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </button>
                                                    <form method="POST" onsubmit="return confirm('Delete this city? This will also delete all associated locations and jobs.');" style="display: inline;">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $city['id']; ?>">
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
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="cityModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px; margin: 50px auto;">
            <div class="form-container">
                <h2 id="modalTitle">Add City</h2>
                <form method="POST" class="job-form">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="id" id="cityId">
                    
                    <div class="form-group">
                        <label>City Name <span class="required">*</span></label>
                        <input type="text" name="city_name" id="cityName" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Island <span class="required">*</span></label>
                        <select name="island" id="island" required>
                            <option value="">Select Island</option>
                            <option value="Luzon">Luzon</option>
                            <option value="Visayas">Visayas</option>
                            <option value="Mindanao">Mindanao</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn-submit">Save City</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Add City';
            document.getElementById('formAction').value = 'add';
            document.getElementById('cityId').value = '';
            document.getElementById('cityName').value = '';
            document.getElementById('island').value = '';
            document.getElementById('cityModal').style.display = 'block';
        }

        function editCity(city) {
            document.getElementById('modalTitle').textContent = 'Edit City';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('cityId').value = city.id;
            document.getElementById('cityName').value = city.city_name;
            document.getElementById('island').value = city.island || '';
            document.getElementById('cityModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('cityModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('cityModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
    <script src="../assets/dark-mode.js"></script>

    <style>
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
    </style>
</body>
</html>
