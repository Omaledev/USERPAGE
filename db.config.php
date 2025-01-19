<?php
$type = 'mysql';
$dbname = 'axiahub';
$servername = "localhost";
$username = "root";
$password = "";

try {
    global $dbconnection;
    // Create a new PDO instance
    $dbconnection = new PDO("mysql:localhost=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $dbconnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected Successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>

