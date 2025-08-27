<?php
$servername = "localhost"; // Change this to your actual database host (e.g., for cloud DB like PlanetScale, use the provided host)
$username = "uws1gwyttyg2r";
$password = "k1tdlhq4qpsf";
$dbname = "dbxwpkxbvvx1vz";
 
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
