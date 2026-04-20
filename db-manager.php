<!DOCTYPE html>
<html>
<head>
    <title>Database Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .sql-box { width: 100%; height: 100px; margin: 10px 0; }
        .btn { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .btn:hover { background: #005a87; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Database Manager</h1>
        
        <?php
        require_once 'radiant/jobs/config.php';
        
        $message = '';
        
        // Handle SQL execution
        if ($_POST['sql'] ?? false) {
            try {
                $sql = trim($_POST['sql']);
                if (stripos($sql, 'SELECT') === 0) {
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $affected = $pdo->exec($sql);
                    $message = "<div class='success'>✅ Query executed successfully. Affected rows: $affected</div>";
                }
            } catch (PDOException $e) {
                $message = "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
            }
        }
        
        echo $message;
        ?>
        
        <h2>📋 Database Tables</h2>
        <?php
        try {
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<p><strong>Tables in 'dbronnie' database:</strong></p>";
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li><a href='?table=$table'>$table</a></li>";
            }
            echo "</ul>";
        } catch (PDOException $e) {
            echo "<div class='error'>Error loading tables: " . $e->getMessage() . "</div>";
        }
        ?>
        
        <?php if (isset($_GET['table'])): ?>
            <h2>📊 Table: <?= htmlspecialchars($_GET['table']) ?></h2>
            <?php
            try {
                $table = $_GET['table'];
                $stmt = $pdo->query("SELECT * FROM `$table` LIMIT 50");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($rows) {
                    echo "<table>";
                    echo "<tr>";
                    foreach (array_keys($rows[0]) as $column) {
                        echo "<th>" . htmlspecialchars($column) . "</th>";
                    }
                    echo "</tr>";
                    
                    foreach ($rows as $row) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No data found in this table.</p>";
                }
            } catch (PDOException $e) {
                echo "<div class='error'>Error loading table data: " . $e->getMessage() . "</div>";
            }
            ?>
        <?php endif; ?>
        
        <?php if (isset($results)): ?>
            <h2>📊 Query Results</h2>
            <?php if ($results): ?>
                <table>
                    <tr>
                        <?php foreach (array_keys($results[0]) as $column): ?>
                            <th><?= htmlspecialchars($column) ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?= htmlspecialchars($value ?? 'NULL') ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        <?php endif; ?>
        
        <h2>💻 Execute SQL</h2>
        <form method="post">
            <textarea name="sql" class="sql-box" placeholder="Enter your SQL query here...
Examples:
SELECT * FROM job_positions;
INSERT INTO cities (name, state) VALUES ('New York', 'NY');
UPDATE job_positions SET status = 'active' WHERE id = 1;"></textarea>
            <br>
            <button type="submit" class="btn">Execute Query</button>
        </form>
        
        <h2>📚 Quick Actions</h2>
        <div>
            <a href="?sql=SELECT COUNT(*) as total_jobs FROM job_positions" class="btn">Count Jobs</a>
            <a href="?sql=SELECT * FROM admin_users" class="btn">View Admin Users</a>
            <a href="?sql=SELECT * FROM job_applications ORDER BY created_at DESC LIMIT 10" class="btn">Recent Applications</a>
        </div>
    </div>
</body>
</html>