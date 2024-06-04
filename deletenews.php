<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Menghapus berita dari database
    $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman admin
        header("Location: admin.php");
        exit();
    } else {
        // Jika terjadi kesalahan, tampilkan pesan kesalahan dan arahkan kembali ke halaman admin
        echo "Failed to delete news.";
        header("refresh:2; url=admin.php"); // Redirect ke halaman admin setelah 2 detik
        exit();
    }
} else {
    // Jika tidak ada id berita yang diberikan, arahkan kembali ke halaman admin
    header("Location: admin.php");
    exit();
}
?>