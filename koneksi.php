<?php
$db = 'projectpas';
$conn = new mysqli('localhost', 'root', '', $db);
if ($conn->connect_error) {
    die('koneksi Error: ');
}

?>