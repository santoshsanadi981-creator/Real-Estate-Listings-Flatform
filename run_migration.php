<?php

include 'project backend/components/connect.php';

echo "<h2>Database Migration Runner</h2>";
echo "<hr>";

try {
    // Check if is_admin column already exists
    $check_column = $conn->prepare("SHOW COLUMNS FROM `users` LIKE 'is_admin'");
    $check_column->execute();
    
    if($check_column->rowCount() == 0){
        // Column doesn't exist, so add it
        echo "<p>Adding 'is_admin' column to users table...</p>";
        $conn->exec("ALTER TABLE `users` ADD COLUMN `is_admin` INT DEFAULT 0 AFTER `password`");
        echo "<p style='color: green;'>✓ Column 'is_admin' added successfully!</p>";
        
        // Create index for better performance
        echo "<p>Creating index on 'is_admin' column...</p>";
        $conn->exec("CREATE INDEX `idx_is_admin` ON `users` (`is_admin`)");
        echo "<p style='color: green;'>✓ Index created successfully!</p>";
        
        echo "<hr>";
        echo "<h3 style='color: green;'>✓ Migration Completed Successfully!</h3>";
        echo "<p>The database has been updated. You can now:</p>";
        echo "<ol>";
        echo "<li>Register user accounts</li>";
        echo "<li>Promote users to admin status in the admin panel</li>";
        echo "<li>Admin users can post properties</li>";
        echo "</ol>";
        echo "<p><a href='project%20backend/register.php' style='padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px;'>Go to Registration →</a></p>";
        
    } else {
        echo "<p style='color: green;'>✓ Column 'is_admin' already exists in the database!</p>";
        echo "<p>You can proceed to register users and use the admin panel.</p>";
        echo "<p><a href='project%20backend/register.php' style='padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px;'>Go to Registration →</a></p>";
    }
    
} catch(PDOException $e){
    echo "<p style='color: red;'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please make sure:</p>";
    echo "<ul>";
    echo "<li>Database connection is working</li>";
    echo "<li>You have the correct database selected</li>";
    echo "<li>Your database user has ALTER TABLE permissions</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p>If you encounter any issues, you can manually run this SQL query in phpMyAdmin:</p>";
echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
echo "ALTER TABLE `users` ADD COLUMN `is_admin` INT DEFAULT 0 AFTER `password`;\n";
echo "CREATE INDEX `idx_is_admin` ON `users` (`is_admin`);\n";
echo "</pre>";

?>
