<?php
include 'koneksi.php';

$limit = 12; // Jumlah artikel per halaman
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$categoryQuery = isset($_GET['category']) ? $_GET['category'] : '';

$totalQuery = $searchQuery ?
    "SELECT COUNT(*) AS count FROM admin WHERE title LIKE '%$searchQuery%' OR content LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%'" :
    ($categoryQuery ?
        "SELECT COUNT(*) AS count FROM admin WHERE category = '$categoryQuery'" :
        "SELECT COUNT(*) AS count FROM admin");

$totalResult = $conn->query($totalQuery);
$totalRows = $totalResult->fetch_assoc()['count'];
$totalPages = ceil($totalRows / $limit);

if ($searchQuery) {
    $stmt = $conn->prepare("SELECT id, image, title, date, content, category FROM admin WHERE (title LIKE ? OR content LIKE ? OR category LIKE ?) LIMIT ? OFFSET ?");
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param("ssssi", $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
} elseif ($categoryQuery) {
    $stmt = $conn->prepare("SELECT id, image, title, date, content, category FROM admin WHERE category = ? LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $categoryQuery, $limit, $offset);
} else {
    $stmt = $conn->prepare("SELECT id, image, title, date, content, category FROM admin LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
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
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        * {
            text-decoration: none;
            list-style: none;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .navbar {
            height: 70px;
            width: 100%;
            background-color: #fff;
            color: #333;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .navbar-nav {
            margin-right: 43px;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .navbar h3 {
            margin-left: 40px;
            font-size: 20px;
            color: #2c3e50;
        }

        .navbar nav a {
            font-size: 16px;
            color: #2c3e50;
            margin-left: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 20px;
            padding-top: 100px;
        }

        .main-content {
            width: 100%;
            max-width: 1400px;
            background-color: #fff;
            overflow-y: auto;
        }

        .main-header {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 100px;
            margin-bottom: 10px;
            text-align: center;
        }

        .main-header h1 {
            margin: 0px 0px 20px 0px;
            font-size: 36px;
            font-weight: 700;
            color: #000;
        }

        .main-header p {
            margin: 0px 0px 20px 0px;
            font-size: 16px;
            color: #555;
            margin: 10px 0 20px;
        }

        .search-bar {
            margin: 20px 0px 0px 0px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #aaa;
            border-radius: 17px;
            padding: 10px;
            width: 333px;
            height: 45px;
            background-color: #fff;
        }

        .search-bar input {
            border: none;
            outline: none;
            padding-left: 10px;
            font-size: 16px;
            width: 100%;
            height: 100%;
        }

        .search-bar svg {
            cursor: pointer;
            margin-right: 5px;
            width: 22px;
            height: 22px;
            fill: #aaa;
        }

        .news-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 130px;
        }

        .card {
            background-color: #f6f6f6;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 450px;
            transition: transform 0.2s ease;
        }

        .card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .card-header .category {
            font-weight: 600;
            font-size: 15px;
            color: #343434;
            margin-bottom: -18px;
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 3px;
            font-size: 20px;
            color: #28282B;
        }

        .card .date {
            font-size: 15px;
            color: #343434;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .card p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
            font-weight: 100;
        }

        .card-content p:first-of-type {
            margin-top: 10px;
        }

        .card {
            transition: background-color 0.4s ease-in-out;
        }

        .card:hover {
            background-color: #D3D3D3;
            cursor: pointer;
        }

        .continue-reading {
            margin-top: 10px;
            font-size: 14px;
            color: #36454F;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .icon {
            margin-top: 1.5px;
            width: 10px;
            height: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 50px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 15px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .modal-content label {
            display: block;
            margin: 10px 0 5px;
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 60px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            color: #007BFF;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .pagination a.active {
            background-color: #007BFF;
            color: white;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        #noResultsMessage {
            text-align: center;
            color: #555;
            padding-left: 590px;
            white-space: nowrap;
        }

        /* Add this to your existing CSS file */
        footer {
            margin-top: 40px;
            background-color: #f8f8f8;
            padding: 40px 0;
            font-size: 14px;
            color: #333;
        }

        .footer-container {
            max-width: 1450px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .footer-column {
            flex: 1;
            margin: 10px;
        }

        #about {
            padding-right: 200px;
        }

        .footer-column h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-column ul li {
            margin-bottom: 10px;
        }

        .footer-column ul li a {
            color: #555;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-column ul li a:hover {
            color: #333;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 10px;
        }

        .footer-bottom p {
            margin: 0;
        }

        .footer-icons a {
            margin-left: 10px;
        }

        .footer-icons img {
            width: 20px;
            height: 20px;
        }

        .rigth {
            width: 10px;
            height: 10px;
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="navbar-content">
            <h3>Majalah Dinding</h3>
            <nav class="navbar-nav">
                <a href="#" data-toggle="modal" data-target="#loginModal"><img width="32" height="32"
                        src="https://img.icons8.com/pastel-glyph/64/gender-neutral-user.png" alt="login-admin" /></a>
            </nav>
        </div>
    </header>
    <div class="container">
        <main class="main-content">
            <header class="main-header">
                <h1>Majalah Dinding SMKN 1 BANJAR</h1>
                <p>Menampilkan berita terbaru, informasi kegiatan, dan berbagai artikel menarik dari SMKN 1 Banjar.</p>
                <form action="" method="GET" class="search-bar" id="searchForm">
                    <input type="text" id="searchInput" name="search" placeholder="Search"
                        value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                    </svg>
                </form>
            </header>
            <section class="news-cards" id="newsCards">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="card" data-article-id="<?php echo $row['id']; ?>">
                            <a href="article.php?id=<?php echo $row['id']; ?>">
                                <img src="<?php echo $row['image']; ?>" alt="Gambar Berita">
                                <div class="card-content">
                                    <div class="card-header">
                                        <span class="category"><?php echo $row['category']; ?></span>
                                        <span class="dot"></span> <span class="date"><?php echo $row['date']; ?></span>
                                    </div>
                                    <h2><?php echo $row['title']; ?></h2>
                                    <p><?php echo substr($row['content'], 0, 210); ?>...</p>
                                    <a href="#" class="continue-reading">
                                        Continue Reading <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                            viewBox="0 0 320 512">
                                            <path
                                                d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5 12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" />
                                        </svg>
                                    </a>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p id="noResultsMessage">Tidak ada hasil yang ditemukan.</p>
                <?php endif; ?>
            </section>


            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchQuery; ?>">&laquo; Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo $searchQuery; ?>" <?php if ($i == $page)
                              echo 'class="active"'; ?>><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchQuery; ?>">Next &raquo;</a>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <!-- Modal Login -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="logikalogin.php" method="POST">
                <h2>Login</h2>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
    <!-- Add this at the bottom of your existing HTML file -->
    <footer>
        <div class="footer-container">
            <div class="footer-row">
                <div class="footer-column" id="about">
                    <h4>About</h4>
                    <p>Majalah Dinding SMKN 1 BANJAR adalah platform digital yang menampilkan berita terbaru, kegiatan,
                        dan artikel menarik dari lingkungan sekolah. Kami berkomitmen memberikan informasi yang aktual
                        dan relevan bagi seluruh warga sekolah. Dikelola oleh tim redaksi berdedikasi, kami menyajikan
                        berbagai topik mulai dari kegiatan sekolah hingga tips edukatif. Selamat membaca dan tetap
                        update dengan berita terkini dari sekolah kita!</p>
                </div>
                <div class="footer-column">
                    <h4>Category</h4>
                    <ul>
                        <li><a href="index.php?category=Hal">Hal</a></li>
                        <li><a href="index.php?category=Acara">Acara</a></li>
                        <li><a href="index.php?category=Halaman Penting">Halaman Penting</a></li>
                        <!-- Tambahkan kategori lain di sini -->
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Important Links</h4>
                    <ul>
                        <li><a href="https://smkn1banjar.sch.id/">SMKN 1 BANJAR<svg xmlns="http://www.w3.org/2000/svg"
                                    style="margin-bottom: 1.9px;" class="rigth"
                                    viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path
                                        d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z" />
                                </svg></a> </li>
                        <li><a href="https://www.instagram.com/mk_smknegeri1banjar/">MK SMKN 1 BANJAR<svg
                                    xmlns="http://www.w3.org/2000/svg" class="rigth"
                                    viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path
                                        d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z" />
                                </svg></a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 Majalah Dinding, All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Get the modal
        var modal = document.getElementById("loginModal");

        // Get the button that opens the modal
        var btn = document.querySelector("[data-toggle='modal']");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Ambil ikon SVG
        var searchIcon = document.querySelector('.search-bar svg');

        // Tambahkan event listener untuk menangani klik pada ikon pencarian
        searchIcon.addEventListener('click', function () {
            // Lakukan pencarian saat ikon pencarian diklik
            var form = this.closest('form');
            var searchInput = form.querySelector('input[name="search"]');
            var searchValue = searchInput.value.trim(); // Ambil nilai pencarian dan hapus spasi ekstra

            // Lakukan pencarian hanya jika ada nilai yang dimasukkan
            if (searchValue !== '') {
                form.submit(); // Kirim formulir pencarian
            }
        });

        document.querySelectorAll('.continue-reading').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault(); // Mencegah pengalihan ke halaman artikel
                const card = this.closest('.card');
                const articleId = card.dataset.articleId;
                window.location.href = `article.php?id=${articleId}`; // Pengalihan ke halaman artikel
            });
        });
        $(document).ready(function () {
            $('#searchInput').on('keyup', function () {
                let searchQuery = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: 'index.php',
                    data: { search: searchQuery, page: 1 },
                    success: function (response) {
                        let newsCards = $('#newsCards');
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(response, 'text/html');
                        let newContent = doc.querySelector('#newsCards').innerHTML;
                        let noResultsMessage = doc.querySelector('#noResultsMessage');

                        if (noResultsMessage) {
                            newsCards.html('<p id="noResultsMessage">Tidak ada hasil yang ditemukan.</p>');
                        } else {
                            newsCards.html(newContent);
                        }

                        $('.pagination').html(doc.querySelector('.pagination').innerHTML);
                    }
                });
            });
        });

    </script>
</body>

</html>