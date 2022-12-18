<?php
session_start();
include_once('./config.php');
$connect = connect();
$login = false;
$username = "";
$email;
$name = "";
if (!isset($_SESSION['user'])) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}
// lấy thông tin user từ db
$login = true;
$email = $_SESSION['user'];
$sql = "SELECT * FROM users WHERE username='" . $email . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$num_row = mysqli_num_rows($query);
if ($num_row > 0) {
    $data = mysqli_fetch_assoc($query);
    $name = $data['name'];
}
// đăng xuất
if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    unset($_SESSION['user']);
    header('Location: login.php');
    exit();
}
// lấy số lượng user
$num_of_user = 0;
$us = "SELECT * FROM users WHERE role = '1'";
$query_us = mysqli_query($connect, $us);
if ($query_us) {
    $num_of_user = mysqli_num_rows($query_us);
}
// lấy số lượng admin
$sql_select = "SELECT * FROM users";
$run = mysqli_query($connect, $sql_select);
$num_us = mysqli_num_rows($run);
$num_of_admin=$num_us-$num_of_user;
// lấy số lượng file
$num_of_files = 0;
$fl = "SELECT * FROM file";
$query_fl = mysqli_query($connect, $fl);
if ($query_fl) {
$num_of_files = mysqli_num_rows($query_fl);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/styleAdmin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <title>Quản lý dữ liệu-Admin</title>
</head>

<body>
    <header>
        <h>Admin</h>
    </header>
    <div class="container-">
        <nav class="navbar navbar-expand-lg" id="navbar1">
            <div class="container-fluid">
                <img src="./CSS/images/logo.jpg" height="50px" width="50px" style="border-radius: 50px;">
                <a class="navbar-brand" href="index.php" style="padding-left: 50px;color: rgb(66, 72, 116);">Trang Chủ</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <form class="d-flex" role="search" style="width: 60%; padding-left:10%;">
                        <input class="form-control me-2" type="search" placeholder="Tìm kiếm" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Tìm</button>
                    </form>
                    <ul>
                        <li class="nav-item dropdown" id="login">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php
                                if ($name != "") {
                                    echo $name;
                                } else {
                                    echo "User";
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu" id="dropdownLogin">
                                <li><a class="dropdown-item" href="./editInfor.php">Hồ sơ của tôi</a></li>
                                <li><a class="dropdown-item" href="./changePassword.php">Đổi mật khẩu</a></li>

                                <li><a class="dropdown-item" href="indexAdmin.php?dangxuat=1">Đăng xuất</a></li>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <section>
            <nav id="navbar2">

                <div class="recent">
                    <img src="./CSS/images/user.png" width="15%" height="15%">
                    <a class="btn" id="btnRecent" href="listOfUser.php">Quản lý người dùng</a>
                </div>
                <!-- <div class="share">
                    <img src="./CSS/images/share7.png" width="15%" height="15%">
                    <a class="btn" id="btnShare" href="shareUser.php">Đã chia sẻ</a>
                </div> -->
                <div class="recent">
                    <img src="./CSS/images/settings.png" width="15%" height="15%">
                    <a class="btn" id="btnRecent" href="view_report.php">Xem báo cáo</a>
                </div>

                <div class="trash">
                    <a class="btn" id="btnTrash" href="#"></a>
                </div>
                <div class="trash">
                    <a class="btn" id="btnTrash" href="#"></a>
                </div>
                <div class="trash">
                    <a class="btn" id="btnTrash" href="#"></a>
                </div>
                <div class="priority">

                    <a class="btn" id="btnPriority" href="#"></a>
                </div>
                <div class="priority">

                    <a class="btn" id="btnPriority" href="#"></a>
                </div>
                <div class="priority">

                    <a class="btn" id="btnPriority" href="#"></a>
                </div>
                <div class="priority">

                    <a class="btn" id="btnPriority" href="#"></a>
                </div>
            </nav>

            <article id="art2">
                <div class="row">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thư mục</li>
                        </ol>
                    </nav>
                    <div class="row">

                        <div class="col-lg-4 col-m-6 col-sm-6">
                            <div class="counter" id="counterUser">
                                <div class="col-lg-6" style="padding-top: 5%; ">
                                    <i class="fas fa-tasks"></i>
                                    <h3><?php echo $num_of_user ?></h3>
                                    <p>Người dùng</p>
                                </div>
                                <div class="col-lg-6" style="padding-top: 10%;">
                                    <img src="./CSS/images/chart.png" height="50%" width="50%">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-m-6 col-sm-6">
                            <div class="counter " id="counterFile">
                                <div class="col-lg-6" style="padding-top: 5%;">
                                    <i class="fas fa-tasks"></i>
                                    <h3><?php echo $num_of_files ?></h3>
                                    <p>File</p>
                                </div>
                                <div class="col-lg-6" style="padding-top: 10%;">
                                    <img src="./CSS/images/chart.png" height="50%" width="50%">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-m-6 col-sm-6">
                            <div class="counter " id="counterAdmin">
                                <div class="col-lg-6" style="padding-top: 5%;">
                                    <i class="fas fa-tasks"></i>
                                    <h3><?php echo $num_of_admin ?></h3>
                                    <p>Admin</p>
                                </div>
                                <div class="col-lg-6" style="padding-top: 10%;">
                                    <img src="./CSS/images/chart.png" height="50%" width="50%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-top:2%;">
                        <h4>Biểu đồ thống kê</h4>
                        <canvas id="myChart" style="width:100%;max-width:100%"></canvas>
                    </div>
                    <script>
                        var xValues = [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150];
                        var yValues = [7, 8, 8, 9, 9, 9, 10, 11, 14, 14, 15];

                        new Chart("myChart", {
                            type: "line",
                            data: {
                                labels: xValues,
                                datasets: [{
                                    fill: false,
                                    lineTension: 0,
                                    backgroundColor: "rgba(0,0,255,1.0)",
                                    borderColor: "rgba(0,0,255,0.1)",
                                    data: yValues
                                }]
                            },
                            options: {
                                legend: {
                                    display: false
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            min: 6,
                                            max: 16
                                        }
                                    }],
                                }
                            }
                        });
                    </script>
                </div>
            </article>
        </section>
    </div>
    <footer>
        <p>Footer</p>
    </footer>
    <script>
        let popup = document.getElementById("popup");

        function openPopup() {
            popup.classList.add("open-popup");
        }

        function closePopup() {
            popup.classList.remove("open-popup");
        }
    </script>
</body>

</html>