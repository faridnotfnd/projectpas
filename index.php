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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-content">
                <h2>Majalah dinding<br>SMKN 1 BANJAR</h2>
            </div>
            <div class="admin-login">
                <p>
                    <a href="#" data-toggle="modal" data-target="#loginModal">admin login</a>
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

    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="logikalogin.php">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="login">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
