<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}
include 'koneksi.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $content = $_POST['content'];

    // Check if a new image is selected
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/"; // Specify the directory where you want to store the uploaded files
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // If upload is successful, use the new image path
            $image = $targetFile;
        } else {
            echo "<script>alert('Failed to upload image.'); window.location.href = 'editnews.php?id=$id';</script>";
            exit();
        }
    } else {
        // If no new image is uploaded, use the existing image
        $stmt = $conn->prepare("SELECT image FROM admin WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $image = $row['image'];
    }

    // Update the news in the database
    $stmt = $conn->prepare("UPDATE admin SET title = ?, image = ?, date = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $image, $date, $content, $id);

    if ($stmt->execute()) {
        echo "<script>window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>window.location.href = 'editnews.php?id=$id';</script>";
    }
} else {
    // Fetch the news to be edited
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $news = $result->fetch_assoc(); // Store the fetched news in the $news variable
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 100px;
        }
        img {
            max-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Berita</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Judul Acara:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Gambar:</label><br>
                <!-- Display the existing image -->
                <img id="currentImage" src="<?php echo htmlspecialchars($news['image']); ?>" alt="Current Image"><br><br>
                <!-- Input for selecting a new image -->
                <input type="file" id="image" name="image" onchange="previewImage(event)">
            </div>
            <div class="form-group">
                <label for="date">Tanggal:</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($news['date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Isi:</label>
                <textarea class="form-control" id="content" name="content" required><?php echo htmlspecialchars($news['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('currentImage');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
