<?php
$servername = "localhost";
$username = "root";
$password = "225009777";
$dbname = "projectpas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
