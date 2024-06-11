<?php
include 'koneksi.php';

$limit = 12;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$searchParam = "%" . $searchQuery . "%";

$total_result = $conn->prepare("SELECT COUNT(*) AS total FROM admin WHERE title LIKE ? OR content LIKE ? OR category LIKE ?");
$total_result->bind_param("sss", $searchParam, $searchParam, $searchParam);
$total_result->execute();
$total_result = $total_result->get_result();
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$result = $conn->prepare("SELECT image, title, date, content, category, id FROM admin WHERE title LIKE ? OR content LIKE ? OR category LIKE ? ORDER BY inserted_at ASC LIMIT ? OFFSET ?");
$result->bind_param("ssssi", $searchParam, $searchParam, $searchParam, $limit, $offset);
$result->execute();
$result = $result->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$response = [
    'data' => $data,
    'current_page' => $page,
    'total_pages' => $total_pages
];

header('Content-Type: application/json');
echo json_encode($response);
?>