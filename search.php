<?php
include 'koneksi.php';

$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';

if ($searchQuery) {
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT id, image, title, date, content, category FROM admin WHERE title LIKE ? OR content LIKE ? OR category LIKE ?");
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $articles = [];
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
    
    echo json_encode($articles);
}
?>
