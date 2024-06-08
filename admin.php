<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: index.php");
    exit();
}
include 'koneksi.php';

// Query to get data sorted by date in descending order
$result = $conn->query("SELECT image, title, date, content, category, id FROM admin ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="./style/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="favicon.ico">
    <style>
        .container {
            margin-right: 200px;
        }

        .description {
            max-height: 100px;
            /* Set the max height you want */
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Number of lines to show */
            -webkit-box-orient: vertical;
        }

        .action-column {
            width: 150px;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            /* Menjadikan posisi relatif untuk referensi absolut */
        }

        .search-bar input {
            padding: 10px 30px 10px 10px;
            /* Sesuaikan padding untuk memberi ruang bagi ikon */
            font-size: 16px;
            border: 1px solid #aaa;
            border-radius: 10px;
            width: 250px;
            height: 41px;
            outline: none;
        }

        .search-icon {
            position: absolute;
            right: 10px;
        }

        .search-svg {
            cursor: pointer;
            width: 22px;
            height: 22px;
            fill: #aaa;
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <main class="main-content">
            <header class="main-header">
                <h1>Admin Panel</h1>
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchNews()">
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                            class="search-svg"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path
                                d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                        </svg>
                    </span>
                </div>

                <a href="addnews.php" class="btn btn-primary">Add</a>
                <a href="#" class="btn btn-secondary" onclick="confirmLogout(event)">Logout</a>
            </header>

            <section class="news-cards">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th class="action-column">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><img src="<?php echo $row['image']; ?>" alt="News Image" style="width: 100px;"></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['category']; ?></td> <!-- Tambahkan kolom kategori -->
                                <td>
                                    <div class="description"><?php echo $row['content']; ?></div>
                                </td>
                                <td>
                                    <a href="editnews.php?id=<?php echo $row['id']; ?>"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="deletenews.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                </td>
                            </tr>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        function searchNews() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.querySelector(".table-striped");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        function confirmLogout(event) {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'logout.php';
            } else {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>