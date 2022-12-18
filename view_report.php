<?php
session_start();
// Thêm file connect db
include_once('./config.php');
$connect = connect();
$login = false;
$username = "";
$email;
$name = "";
// kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}
// Lấy thông tin user
$login = true;
$email = $_SESSION['user'];
$sql = "SELECT * FROM users WHERE username='" . $email . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$num_row = mysqli_num_rows($query);
if ($num_row > 0) {
    $data = mysqli_fetch_assoc($query);
    $name = $data['name'];
}
// Đăng xuất
if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    unset($_SESSION['user']);
    header('Location: login.php');
    exit();
}
// Lấy tất cả các report từ người dùng
$select = "SELECT * FROM report WHERE xuly='0'";
$run = mysqli_query($connect, $select);
if ($run) {
    $num = mysqli_num_rows($run);
} else {
    $num = 0;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="./CSS/style_rep.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <title>Quản lý dữ liệu-Báo cáo</title>
</head>

<body>
    <header>
        <h>Danh sách tập tin vi phạm</h>
    </header>
    <div class="container-">
        <nav class="navbar navbar-expand-lg" id="navbar1">
            <div class="container-fluid">
                <img src="./CSS/images/logo.jpg" height="50px" width="50px" style="border-radius: 50px;">
                <a class="navbar-brand" href="indexAdmin.php" style="padding-left: 50px;color: rgb(66, 72, 116);">Trang Chủ</a>
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
                    <a class="btn" id="btnRecent" href="view_report.php">Báo cáo</a>
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
                            <li class="breadcrumb-item"><a href="indexAdmin.php">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Báo cáo vi phạm</li>
                        </ol>
                    </nav>
                    <div class="container" id="containerTable">
                        <table class="neumorphic border">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã báo cáo</th>
                                    <th>Tên tập tin</th>
                                    <th>Người dùng vi phạm</th>
                                    <th>Nội dung vi phạm</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Hiển thị report -->
                                <?php
                                $i = 1;
                                while ($row = mysqli_fetch_array($run)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $i ?></th>
                                        <td><?php echo $row['ma_bc'] ?></td>
                                        <td><?php
                                            $q = "SELECT * FROM file WHERE id='" . $row['id_file'] . "' LIMIT 1";
                                            $r = mysqli_query($connect, $q);
                                            if ($r) {
                                                if (
                                                    mysqli_num_rows($r) > 0
                                                ) {
                                                    $data = mysqli_fetch_assoc($r);
                                                    $name_f = $data['file_name'];
                                                }
                                            }
                                            echo $name_f;
                                            ?></td>
                                        <td><?php echo $row['own'] ?></td>
                                        <td><?php echo $row['type'] ?></td>
                                        <td>
                                            <p><a style="display:block" class="btn btn-info" href="xuly.php?sendmail=<?php echo $row['own'] ?>&id=<?php echo $row['id_file'] ?>&id_bc=<?php echo $row['id'] ?>" id="sendmail">Cảnh báo</a></p>
                                            <p><a style="display:block" class="btn btn-secondary" href="xuly.php?blockfile=<?php echo $row['own'] ?>&id=<?php echo $row['id_file'] ?>&id_bc=<?php echo $row['id'] ?>">Gỡ tài liệu</a></p>
                                            <p><a style="display:block" class="btn btn-danger" href="xuly.php?blockuser=<?php echo $row['own'] ?>&id=<?php echo $row['id_file'] ?>&id_bc=<?php echo $row['id'] ?>">Gỡ tài khoản</a></p>
                                        </td>
                                    </tr>
                                <?php
                                    $i++;
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
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