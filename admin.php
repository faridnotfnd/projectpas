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
        }

        .search-bar input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #aaa;
            border-radius: 10px;
            width: 250px;
            height: 41px;
        }
    </style>
</head>

<body>
    <div class="container">
        <main class="main-content">
            <header class="main-header">
                <h1>Admin Panel</h1>
                <div class="search-bar">
                    <span class="search-icon">
                        <input type="text" placeholder="Search...">
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