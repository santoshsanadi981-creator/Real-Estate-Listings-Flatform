<?php

include 'project backend/components/connect.php';

echo "<h2>Admin credentials Check</h2>";

// Check if admins table exists and has data
try {
    $check_admins = $conn->prepare("SELECT * FROM `admins`");
    $check_admins->execute();
    
    if($check_admins->rowCount() > 0){
        echo "<p style='color: green;'>✓ Admins table found with " . $check_admins->rowCount() . " admin(s)</p>";
        
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Name</th><th>Password Hash</th></tr>";
        
        while($admin = $check_admins->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
            echo "<td>" . $admin['id'] . "</td>";
            echo "<td>" . $admin['name'] . "</td>";
            echo "<td>" . $admin['password'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test password
        echo "<h3>Password Hash Test:</h3>";
        $test_pass = "111";
        $hashed = sha1($test_pass);
        echo "<p>SHA1('111') = <code>" . $hashed . "</code></p>";
        
        // Check if it matches
        $expected_hash = "6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2";
        if($hashed === $expected_hash){
            echo "<p style='color: green;'>✓ Password hash MATCHES database</p>";
        } else {
            echo "<p style='color: red;'>✗ Password hash DOES NOT match. Expected: " . $expected_hash . "</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Admins table is empty!</p>";
        echo "<p>You need to import home_db.sql file to the database first.</p>";
    }
    
} catch(Exception $e){
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

?>
