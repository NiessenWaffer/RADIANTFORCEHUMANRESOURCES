<?php
// One-time database initialization script
// Run this only once: http://localhost/radiant/init-database.php

require_once 'radiant/jobs/config.php';

echo "<h2>🗄️ Database Initialization</h2>";

try {
    // Check if database exists and has tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<p>✅ Database already initialized with " . count($tables) . " tables.</p>";
        echo "<p><a href='radiantforcehumanresources.php'>Go to Main Site</a> | <a href='radiant/admin/'>Admin Panel</a> | <a href='db-manager.php'>Database Manager</a></p>";
    } else {
        // Initialize database
        $sqlFile = __DIR__ . '/radiant/database/01_radiant_complete.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $pdo->exec($sql);
            echo "<p>✅ Database initialized successfully!</p>";
            
            // Add cities
            $citiesFile = __DIR__ . '/radiant/database/02_insert_major_cities.sql';
            if (file_exists($citiesFile)) {
                $sql = file_get_contents($citiesFile);
                $pdo->exec($sql);
                echo "<p>✅ Cities data added!</p>";
            }
            
            echo "<p><strong>🎉 Setup Complete!</strong></p>";
            echo "<p><a href='radiantforcehumanresources.php'>Go to Main Site</a> | <a href='radiant/admin/'>Admin Panel</a></p>";
        } else {
            echo "<p>❌ SQL file not found!</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>