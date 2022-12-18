<?php
session_start();
// thêm file connect database
include_once("./config.php");
$connect = connect();
// lấy username và role từ session
$username = $_SESSION['user'];
$role = $_SESSION['role'];
// truy xuất và lấy tên người dùng từ username
$sql = "SELECT * FROM users WHERE username='" . $username . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$name = "";
$password = "";
$er = "";
// lấy password cũ của người dùng
if ($num = mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_array($query)) {
    $name = $row['name'];
    $use_size = $row['use_size'];
    $password = $row['password'];
  }
}
// lấy dữ liệu mật khẩu cũ và mật khẩu mới từ form
if (isset($_POST['submit']) && $_POST['submit'] == 'Lưu thay đổi') {
  $old = md5($_POST['old_pass']);
  $new = $_POST['new_pass'];
  $new_again = $_POST['new_pass_again'];
// kiểm tra dữ liệu đầu vào
  if (empty($old) || empty($new) || empty($new_again)) {
    $er = "Vui lòng nhập đầy đủ thông tin";
  } else if (strlen($new) < 6 || strlen($new_again) < 6) {
    $er = "Mật khẩu phải từ 6 chữ ký tự trở lên.";
  } else {
    if ($password !== $old) {
      $er = "Mật khẩu cũ không chính xác. Vui lòng nhập lại";
    } else if ($password === md5($new)) {
      $er = "Mật khẩu cũ và mật khẩu mới trùng nhau. Vui lòng nhập lại";
    } else {
      // cập nhật mật khẩu mới lên database
      if ($new == $new_again) {
        $hashed = md5($new);
        $query = "UPDATE users SET password = '$hashed' WHERE username = '$username'";
        $res = mysqli_query($connect, $query);
        if ($res) {
          $er = "Mật khẩu đã cập nhật thành công";
        }
      } else {
        $er = "Mật khẩu không khớp";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/changePassword.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <title>Đổi mật khẩu</title>

</head>

<body style="display:block">
  <div>

    <?php
    if ($role == 0) {
    ?>
      <a href="indexAdmin.php"><i class="material-icons">arrow_back</i></a>
    <?php
    } else {
    ?>
      <a href="index.php"><i class="material-icons">arrow_back</i></a>
    <?php
    }
    ?>
  </div>
  <div class="container">
    <div class="title">Đổi mật khẩu</div>
    <div class="content">
      <form action="#" method="POST">
        <div class="user-details">
          <div class="input-box">
            <span class="details">Mật khẩu cũ</span>
            <input type="password" name="old_pass" placeholder="Vui lòng nhập mật khẩu cũ" required>
          </div>
          <div class="input-box">
            <span class="details">Mật khẩu mới</span>
            <input type="password" name="new_pass" placeholder="Vui lòng nhập mật khẩu mới" required>
          </div>
          <div class="input-box">
            <span class="details">Nhập lại mật khẩu mới</span>
            <input type="password" name="new_pass_again" placeholder="Vui lòng xác nhận lại mật khẩu mới" required>
          </div>
        </div>
        <p class="text text-danger">
          <?php
          // hiển thị thông báo 
          if (isset($er)) {
            if ($er == 'Mật khẩu đã cập nhật thành công') {
              echo $er;
            } else {
              echo $er;
            }
          }

          ?>
        </p>
        <div>
          <div class="button">
            <input type="submit" value="Lưu thay đổi" name="submit" width="50%">
          </div>
        </div>
      </form>
    </div>
  </div>
</body>

</html>