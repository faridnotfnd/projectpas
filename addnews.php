<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $category = $_POST['category'];
    $content = $_POST['content'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File bukan gambar.'); window.location.href = 'addnews.php';</script>";
        exit();
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<script>alert('Maaf, file gambar sudah ada.'); window.location.href = 'addnews.php';</script>";
        exit();
    }

    // Check file size
    if ($_FILES["image"]["size"] > 5000000) { // 5MB
        echo "<script>alert('Maaf, ukuran file terlalu besar.'); window.location.href = 'addnews.php';</script>";
        exit();
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") {
        echo "<script>alert('Maaf, hanya file JPG, JPEG, PNG, GIF & WEBP yang diperbolehkan.'); window.location.href = 'addnews.php';</script>";
        exit();
    }

    // Move uploaded file to designated directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert new record into database
        $stmt = $conn->prepare("INSERT INTO admin (title, image, date, category, content) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $target_file, $date, $category, $content);

        if ($stmt->execute()) {
            echo "<script>window.location.href = 'admin.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan'); window.location.href = 'addnews.php';</script>";
        }
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengunggah gambar'); window.location.href = 'addnews.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Berita</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="favicon.ico">
    <style>
        img {
            max-width: 200px;
        }

        .back-to-blog {
            color: #343434;
            font-size: 18px;
            font-weight: 600;
        }

        a:hover {
            color: #343434;
            text-decoration: none;
        }

        .icon {
            margin: 20px 0 23px 20px;
            width: 17px;
            height: 17px;
        }

        .icon,
        .back-to-blog:hover {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <a href="admin.php" class="back-to-blog"><svg xmlns="http://www.w3.org/2000/svg" class="icon" ;
            viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" />
        </svg>Back</a>
    <div class="container">
        <h1 class="text">Tambah Berita</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Judul Berita:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="image">Gambar:</label><br>
                <img id="preview" src="#" alt="Image Preview" style="display: none;"><br><br>
                <input type="file" id="image" name="image" required onchange="previewImage(event)">
            </div>
            <div class="form-group">
                <label for="date">Tanggal:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="category">Kategori:</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="form-group">
                <label for="content">Isi:</label>
                <textarea class="form-control" id="content" name="content" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>

</html>
