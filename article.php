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
            color: #343434;
            font-size: 18px;
            font-weight: 600;
        }

        a:hover {
            color: #343434;
            text-decoration: none;
        }

        .icon {
            margin: 20px 0 -4px 20px;
            width: 20px;
            height: 20px;
        }

        .icon,
        .back-to-blog:hover {
            cursor: pointer;
        }

        .main-content {
            font-size: 1.5em;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="navbar-content">
            <h3>Majalah Dinding</h3>
        </div>
    </header>
    <div class="container">
        <main class="main-content">
            <a href="index.php" class="back-to-blog"><svg xmlns="http://www.w3.org/2000/svg" class="icon" ;
                    viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path
                        d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" />
                </svg>Back</a>
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