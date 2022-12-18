<?php
session_start();
// thêm file connect database
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
// lấy thông tin user đang đăng nhập
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
// lấy tất cả user tên hệ thống(admin lẫn người dùng)
$sql_select = "SELECT * FROM users";
$run = mysqli_query($connect, $sql_select);
$num = mysqli_num_rows($run);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="css/styleAdmin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <title>Document</title>
</head>

<body>
  <header>
    <h>Danh Sách Người Dùng</h>
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
            <input class="form-control me-2" type="search" placeholder="Tìm kiếm" id="searchCharacter" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Tìm</button>
          </form>
          <ul>
            <!-- Hiển thị thông tin cá nhân user -->
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
      <!-- Navbar -->
      <nav id="navbar2">

        <div class="recent">
          <img src="./CSS/images/user.png" width="15%" height="15%">
          <a class="btn" id="btnRecent" href="listOfUser.php">Quản lý người dùng</a>
        </div>

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
<!-- Content -->
      <article id="art2">
        <div class="row" id="display_us">
          <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Danh sách người dùng</li>
            </ol>
          </nav>
          <!-- Hiển thị toàn bộ user -->
          <?php
          $user_data = array();
          if ($num > 0) {
            while ($row = mysqli_fetch_array($run)) {
              array_push($user_data, $row);

          ?>
              <div class="col-lg-3 col-md-4" style="margin-bottom:20px">
                <div class="card" style="width: 95%;height: 100%; background-color: rgb(247, 251, 252);border: 2px solid ; z-index: 2;">
                  <?php
                  if ($row['gender'] == 0) {
                  ?>
                    <img src="css/images/girl.png" class="card-img-top">
                  <?php
                  } else {
                  ?>
                    <img src="css/images/user3.png" class="card-img-top">
                  <?php
                  }
                  ?>
                  <div class="card-body">
                    <p class="card-text text text-primary">Tên: <?php echo $row['name'] ?></p>
                    <p class="card-text text text-primary">Email:
                      <?php
                      if (strlen($row['username']) > 20) {
                        echo '<br>'.substr($row['username'], 0, 19) . '...';
                      } else {
                        echo '<br>' . $row['username'];
                      }
                      ?></p>
                    <p class="card-text text text-primary">Vai trò:
                      <?php
                      if ($row['role'] == 0) {
                        echo 'Admin';
                      } else {
                        echo 'Người dùng';
                      }
                      ?></p>
                    <a href="uptoadmin.php?id=<?php echo $row['id']?>" class="btn btn-primary" style="background-color:  green; border:none;">Nâng cấp</a>
                  </div>
                </div>
              </div>
          <?php
            }
          }

          ?>

        </div>
        
      </article>
    </section>
  </div>
  <footer>
    <p>Footer</p>
  </footer>
  <script>
    let popup = document.getElementById("show");

    function openInfo() {
      popup.classList.add("open-popup");
    }

    function closeInfo() {
      popup.classList.remove("open-popup");
    }
// Tìm kiếm user
    $(document).ready(function() {
      $("#searchCharacter").on('input', function() {
        var user_data = <?php echo json_encode($user_data) ?>;
        var char = $("#searchCharacter").val();
        // console.log(user_data[1]['name']);

        var result = user_data.filter(element => element['username'].includes(char));
        var html_result = "";
        for (var i = 0; i < result.length; i++) {
          html_result += "<div class=\"col-lg-3 col-md-4\" style=\"margin-bottom:20px\">" +
            "<div class=\"card\" style=\"width: 95%;height: 100%; background-color: rgb(247, 251, 252);border: 2px solid ; z-index: 2;\">";
          if (result[i]['gender'] == 0) {
            html_result += "<img src=\"css/images/girl.png\" class=\"card-img-top\">";
          } else {
            html_result += "<img src=\"css/images/user3.png\" class=\"card-img-top\">";
          }
          html_result += "<div class=\"card-body\">" +
            "<p class=\"card-text text text-primary\">Tên: " + result[i]['name'] + "</p>" +
            "<p class=\"card-text text text-primary\">Email:";
          if (result[i]['username'].length > 20) {
            html_result += result[i]['username'].substr(0, 19);
          } else {
            html_result += result[i]['username'];
          }
          html_result += "</p>" +
            "<p class=\"card-text text text-primary\">Vai trò:";
          if (result[i]['role'] == 0) {
            html_result += "Admin";
          } else {
            html_result += "Người dùng";
          }
          html_result += "</p>" +
            "<a href=\"uptoadmin.php?id="+result[i]['id']+"\" class=\"btn btn-primary\" style=\"background-color:  green; border:none;\">Nâng cấp</a>" +
            "</div>" +
            "</div>" +
            "</div>";
        }

        document.getElementById("display_us").innerHTML = html_result;
      })
    });

    // function UpToAdmin(id) {

    //   var del = confirm("Bạn có chắc chắn nâng cấp người dùng trở thành quản trị viên hệ thống?");
    //   var form_data = new FormData();
    //   form_data.append("id", id);
    //   if (del == true) {
    //     console.log(id);
    //     $.ajax({
    //       url: "uptoadmin.php",
    //       type: "POST",
    //       dataType: 'script',
    //       cache: false,
    //       contentType: false,
    //       processData: false,
    //       data: form_data,
    //       success: function(dat2) {
    //         alert(dat2);
    //       }
    //     });
    //   } else {

    //   }
    //   return del;
    // }
  </script>
</body>

</html>