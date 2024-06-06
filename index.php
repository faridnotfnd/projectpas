<?php
include 'koneksi.php';

$result = $conn->query("SELECT id, image, title, date, content FROM admin"); // Assume there's an 'id' column
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Majalah Dinding</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-tb+H3gDS7ZB6VMTqgrGesFgeJ7pou96+grN69agM/HQ2tRHswYKVNRt7c4xUZYgR8+HZjgi5tDD3zSp/BGfTtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="favicon.ico">
    <style>
        .card:hover {
            background-color: #D3D3D3;
            cursor: pointer;
            transition: .9s ease;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="navbar-content">
            <h3>Majalah Dinding</h3>
            <nav class="navbar-nav">
                <a href="login.php"><img width="32" height="32"
                        src="https://img.icons8.com/pastel-glyph/64/gender-neutral-user.png"
                        alt="gender-neutral-user" /></a>
            </nav>
        </div>
    </header>
    <div class="container">
        <main class="main-content">
            <header class="main-header">
                <h1>Majalah Dinding SMKN 1 BANJAR</h1>
                <p>Menampilkan berita terbaru yang ada di sekolah</p>
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path
                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                    </svg>
                </div>
            </header>

            <section class="news-cards">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card" data-article-id="<?php echo $row['id']; ?>">
                        <a href="article.php?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo $row['image']; ?>" alt="Gambar Berita">
                            <div class="card-content">
                                <div class="card-header">
                                    <span class="category">Category</span> <span class="dot">•</span> <span
                                        class="date"><?php echo $row['date']; ?></span>
                                </div>
                                <h2><?php echo $row['title']; ?></h2>
                                <p><?php echo substr($row['content'], 0, 200); ?>...</p>
                                <a href="#" class="continue-reading">
                                    Continue Reading <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                        viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path
                                            d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" />
                                    </svg>
                                </a>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </section>
        </main>
    </div>
    <script>
        document.querySelectorAll('.continue-reading').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault(); // Mencegah pengalihan ke halaman artikel
                const card = this.closest('.card');
                const articleId = card.dataset.articleId;
                window.location.href = `article.php?id=${articleId}`; // Pengalihan ke halaman artikel
            });
        });
    </script>
</body>

</html>