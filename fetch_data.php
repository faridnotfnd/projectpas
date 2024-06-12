<?php
include 'koneksi.php';

// Tentukan jumlah baris per halaman
$limit = 12;

// Dapatkan nomor halaman saat ini dari parameter URL, jika tidak ada set default ke 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Dapatkan nilai pencarian
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$searchParam = "%" . $searchQuery . "%";

// Query untuk mendapatkan total jumlah baris dengan kondisi pencarian
$total_result = $conn->prepare("SELECT COUNT(*) AS total FROM admin WHERE title LIKE ? OR content LIKE ? OR category LIKE ?");
$total_result->bind_param("sss", $searchParam, $searchParam, $searchParam);
$total_result->execute();
$total_result = $total_result->get_result();
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Query untuk mendapatkan data dengan limit dan offset dengan kondisi pencarian
$result = $conn->prepare("SELECT image, title, date, content, category, id FROM admin WHERE title LIKE ? OR content LIKE ? OR category LIKE ? ORDER BY date DESC LIMIT ? OFFSET ?");
$result->bind_param("ssssi", $searchParam, $searchParam, $searchParam, $limit, $offset);
$result->execute();
$result = $result->get_result();

$articles = [];
while ($row = $result->fetch_assoc()) {
    $articles[] = $row;
}

$response = [
    'data' => $articles,
    'total_pages' => $total_pages,
    'current_page' => $page,
];

header('Content-Type: application/json');
echo json_encode($response);
?>
