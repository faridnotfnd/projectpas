<?php
include 'koneksi.php';

$result = $conn->query("SELECT image, title, date, content FROM admin");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Majalah Dinding SMKN 1 Banjar</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
</head>

<body>
    <header class="navbar">
        <div class="navbar-content">
            <h3>Majalah Dinding</h3>
            <nav class="navbar-nav">
                <a href="login.php">Admin Login</a>
            </nav>
        </div>
    </header>
    <div class="container">
        <main class="main-content">
            <header class="main-header">
                <h1>Majalah Dinding SMKN 1 BANJAR</h1>
                <p>Menampilkan berita terbaru yang ada di sekolah</p>
                <div class="search-bar">
                    <input type="text">
                </div>
            </header>
            <section class="news-cards">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <img src="<?php echo $row['image']; ?>" alt="Gambar Berita">
                        <div class="card-content">
                            <h2><?php echo $row['title']; ?></h2>
                            <p class="date"><?php echo $row['date']; ?></p>
                            <p><?php echo $row['content']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </section>
        </main>
    </div>
</body>

</html>
