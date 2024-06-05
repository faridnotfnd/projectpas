<?php
include 'koneksi.php';

$result = $conn->query("SELECT image, title, date, content FROM admin");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Majalah dinding Smkn 1 Banjar</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-content">
                <h2>Majalah dinding<br>SMKN 1 BANJAR</h2>
            </div>
            <div class="admin-login">
                <p>
                    <a href="login.php">admin login</a>
                </p>
            </div>
        </aside>
        <main class="main-content">
            <header class="main-header">
                <h1>Daily News</h1>
                <input type="text" placeholder="Search the cards...">
            </header>
            <section class="news-cards">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <h2><?php echo $row['title']; ?></h2>
                        <img src="<?php echo $row['image']; ?>" alt="News Image">
                        <p>Date: <?php echo $row['date']; ?></p>
                        <p><?php echo $row['content']; ?></p>
                    </div>
                <?php endwhile; ?>
            </section>
        </main>
        
    </div>
</body>

</html>