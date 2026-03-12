<?php
// One-off migration script to ensure password columns can store bcrypt hashes.
// Run this once from browser or CLI while XAMPP is running.

include __DIR__ . '/components/connect.php';

$results = [];
$queries = [
    "ALTER TABLE `users` MODIFY `password` varchar(255) NOT NULL",
    "ALTER TABLE `agents` MODIFY `password` varchar(255) NOT NULL",
    "ALTER TABLE `admins` MODIFY `password` varchar(255) NOT NULL",
];

foreach($queries as $q){
    try{
        $conn->exec($q);
        $results[] = "OK: $q";
    }catch(PDOException $ex){
        $results[] = "SKIP/ERROR: $q -> " . $ex->getMessage();
    }
}

echo "<h2>Migration results</h2>\n<ul>\n";
foreach($results as $r){
    echo "<li>".htmlspecialchars($r, ENT_QUOTES)."</li>\n";
}
echo "</ul>\n";

echo "<p>After this, try registering a user again.</p>";

?>