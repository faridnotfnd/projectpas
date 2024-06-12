<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: index.php");
    exit();
}
include 'koneksi.php';

// Tentukan jumlah baris per halaman
$limit = 12;

// Dapatkan nomor halaman saat ini dari parameter URL, jika tidak ada set default ke 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Dapatkan nilai pencarian
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$searchParam = "%" . $searchQuery . "%";

// Dapatkan nilai tanggal
$dateQuery = isset($_GET['date']) ? $_GET['date'] : '';
$dateCondition = !empty($dateQuery) ? "AND date = ?" : "";

// Query untuk mendapatkan total jumlah baris dengan kondisi pencarian
$total_sql = "SELECT COUNT(*) AS total FROM admin WHERE (title LIKE ? OR content LIKE ? OR category LIKE ?) $dateCondition";
$total_result = $conn->prepare($total_sql);

if (!empty($dateQuery)) {
    $total_result->bind_param("ssss", $searchParam, $searchParam, $searchParam, $dateQuery);
} else {
    $total_result->bind_param("sss", $searchParam, $searchParam, $searchParam);
}

$total_result->execute();
$total_result = $total_result->get_result();
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Query untuk mendapatkan data dengan limit dan offset dengan kondisi pencarian
$data_sql = "SELECT image, title, date, content, category, id FROM admin WHERE (title LIKE ? OR content LIKE ? OR category LIKE ?) $dateCondition ORDER BY date DESC LIMIT ? OFFSET ?";
$result = $conn->prepare($data_sql);

if (!empty($dateQuery)) {
    $result->bind_param("sssssi", $searchParam, $searchParam, $searchParam, $dateQuery, $limit, $offset);
} else {
    $result->bind_param("sssii", $searchParam, $searchParam, $searchParam, $limit, $offset);
}

$result->execute();
$result = $result->get_result();
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
            margin: 0 auto;
            padding: 0 15px;
            width: 100%;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
        }

        .description {
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
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
        }

        .search-bar input {
            padding: 10px 30px 10px 10px;
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

        @media screen and (max-width: 767px) {
            .container {
                padding: 0 10px;
                width: 100%;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .table {
                width: 100%;
            }

            .action-column {
                width: auto;
            }

            .search-bar {
                width: 100%;
                padding: 0 10px;
            }

            .search-bar input {
                width: 100%;
            }

            .search-icon svg {
                margin-right: 10px;
            }

            .btn {
                margin-bottom: 5px;
                margin-left: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <main class="main-content">
            <header class="main-header">
                <h1>Admin Panel</h1>
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search..." onkeyup="fetchData(1)">
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="search-svg">
                            <path
                                d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                        </svg>
                    </span>
                </div>
                <a href="addnews.php" class="btn btn-primary">Add</a>
                <a href="#" class="btn btn-secondary" onclick="confirmLogout(event)">Logout</a>
            </header>

            <section class="news-cards">
                <div class="table-responsive">
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
                        <tbody id="newsTable"></tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchData(1);
        });

        function fetchData(page) {
            const searchInput = document.getElementById("searchInput").value;
            fetch(`fetch_data.php?page=${page}&search=${searchInput}`)
                .then(response => response.json())
                .then(data => {
                    const newsTable = document.getElementById("newsTable");
                    newsTable.innerHTML = '';
                    data.data.forEach(item => {
                        const row = document.createElement("tr");

                        const titleCell = document.createElement("td");
                        titleCell.textContent = item.title;
                        titleCell.classList.add("news-title");
                        row.appendChild(titleCell);

                        const imageCell = document.createElement("td");
                        const image = document.createElement("img");
                        image.src = item.image;
                        image.style.width = "100px";
                        imageCell.appendChild(image);
                        row.appendChild(imageCell);

                        const dateCell = document.createElement("td");
                        dateCell.textContent = item.date;
                        row.appendChild(dateCell);

                        const categoryCell = document.createElement("td");
                        categoryCell.textContent = item.category;
                        row.appendChild(categoryCell);

                        const descriptionCell = document.createElement("td");
                        const descriptionDiv = document.createElement("div");
                        descriptionDiv.classList.add("description");
                        descriptionDiv.textContent = item.content;
                        descriptionCell.appendChild(descriptionDiv);
                        row.appendChild(descriptionCell);

                        const actionCell = document.createElement("td");
                        actionCell.classList.add("action-column");

                        const editButton = document.createElement("a");
                        editButton.href = `editnews.php?id=${item.id}`;
                        editButton.classList.add("btn", "btn-warning", "btn-sm", "mr-2");
                        editButton.textContent = "Edit";
                        actionCell.appendChild(editButton);

                        const deleteButton = document.createElement("a");
                        deleteButton.href = `deletenews.php?id=${item.id}`;
                        deleteButton.classList.add("btn", "btn-danger", "btn-sm");
                        deleteButton.textContent = "Delete";
                        deleteButton.onclick = () => confirm('Are you sure you want to delete this item?');
                        actionCell.appendChild(deleteButton);

                        row.appendChild(actionCell);

                        newsTable.appendChild(row);
                    });

                    const pagination = document.getElementById("pagination");
                    pagination.innerHTML = '';

                    if (data.current_page > 1) {
                        const prevPageItem = document.createElement("li");
                        prevPageItem.classList.add("page-item");
                        const prevPageLink = document.createElement("a");
                        prevPageLink.classList.add("page-link");
                        prevPageLink.href = "#";
                        prevPageLink.setAttribute("aria-label", "Previous");
                        prevPageLink.innerHTML = `<span aria-hidden="true">&laquo;</span>`;
                        prevPageLink.onclick = () => fetchData(data.current_page - 1);
                        prevPageItem.appendChild(prevPageLink);
                        pagination.appendChild(prevPageItem);
                    }

                    for (let i = 1; i <= data.total_pages; i++) {
                        const pageItem = document.createElement("li");
                        pageItem.classList.add("page-item");
                        if (i === data.current_page) {
                            pageItem.classList.add("active");
                        }
                        const pageLink = document.createElement("a");
                        pageLink.classList.add("page-link");
                        pageLink.href = "#";
                        pageLink.textContent = i;
                        pageLink.onclick = () => fetchData(i);
                        pageItem.appendChild(pageLink);
                        pagination.appendChild(pageItem);
                    }

                    if (data.current_page < data.total_pages) {
                        const nextPageItem = document.createElement("li");
                        nextPageItem.classList.add("page-item");
                        const nextPageLink = document.createElement("a");
                        nextPageLink.classList.add("page-link");
                        nextPageLink.href = "#";
                        nextPageLink.setAttribute("aria-label", "Next");
                        nextPageLink.innerHTML = `<span aria-hidden="true">&raquo;</span>`;
                        nextPageLink.onclick = () => fetchData(data.current_page + 1);
                        nextPageItem.appendChild(nextPageLink);
                        pagination.appendChild(nextPageItem);
                    }
                });
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