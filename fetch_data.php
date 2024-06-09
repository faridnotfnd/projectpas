<?php
session_start();
include 'koneksi.php';

// Tentukan jumlah baris per halaman
$limit = 10;

// Dapatkan nomor halaman saat ini dari parameter URL, jika tidak ada set default ke 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Dapatkan kata kunci pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mendapatkan total jumlah baris
$total_query = "SELECT COUNT(*) AS total FROM admin";
if ($search) {
    $total_query .= " WHERE title LIKE '%$search%'";
}
$total_result = $conn->query($total_query);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Query untuk mendapatkan data dengan limit dan offset
$data_query = "SELECT image, title, date, content, category, id FROM admin";
if ($search) {
    $data_query .= " WHERE title LIKE '%$search%'";
}
$data_query .= " ORDER BY inserted_at ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($data_query);

// Kembalikan data dalam format JSON
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'data' => $data,
    'total_pages' => $total_pages,
    'current_page' => $page
]);
?>
