<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mengambil nama file gambar dari database
    $stmt_select = $conn->prepare("SELECT image FROM admin WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $stmt_select->bind_result($image);
    $stmt_select->fetch();

    // Bebaskan hasil dan tutup statement untuk menghindari masalah perintah keluar dari sinkronisasi
    $stmt_select->free_result();
    $stmt_select->close();

    // Menghapus berita dari database
    $stmt_delete = $conn->prepare("DELETE FROM admin WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        // Jika penghapusan berhasil, hapus juga file gambar dari direktori uploads
        if (unlink($image)) {
            // Jika penghapusan file berhasil, arahkan kembali ke halaman admin
            header("Location: admin.php");
            exit();
        } else {
            // Jika gagal menghapus file gambar, tampilkan pesan kesalahan
            echo "Failed to delete image file.";
            header("refresh:2; url=admin.php"); // Redirect ke halaman admin setelah 2 detik
            exit();
        }
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