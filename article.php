<?php
include 'koneksi.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM admin WHERE id = $id");
$article = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['title']; ?></title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="icon" href="favicon.ico">
    <style>
        .main-content {
            margin-top: -30px;
        }

        .article-detail {
            margin: 0px auto;
            max-width: 800px;
            text-align: left;
        }

        .article-detail h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
        }

        .article-detail .date {
            font-size: 14px;
            color: #757575;
            margin-bottom: 5px;
        }

        .article-detail .author {
            font-size: 14px;
            color: #333;
            margin-bottom: 20px;
        }

        .article-detail img {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .article-content p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }

        .back-to-blog {
            display: inline-block;
            margin: 20px 0;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .back-to-blog:hover {
            text-decoration: underline;
        }

        /* Memperbesar keseluruhan konten utama */
        .main-content {
            font-size: 1.5em; /* Sesuaikan nilai ini untuk memperbesar atau memperkecil */
        }
    </style>
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
            <a href="index.php" class="back-to-blog">&lt; Back to Blog</a>
            <article class="article-detail">
                <h1><?php echo $article['title']; ?></h1>
                <p class="date"><?php echo $article['date']; ?></p>
                <img src="<?php echo $article['image']; ?>" alt="Gambar Berita" class="article-image">
                <div class="article-content">
                    <p><?php echo $article['content']; ?></p>
                </div>
            </article>
        </main>
    </div>
</body>

</html>
