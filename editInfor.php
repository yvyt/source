<?php
session_start();
// thêm file connect db
include_once("./config.php");
$connect = connect();
// lấy username và role từ session
$username = $_SESSION['user'];
$role = $_SESSION['role'];
$sql = "SELECT * FROM users WHERE username='" . $username . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$name = "";
$password = "";
$phone_us = "";
$gender_us = 0;
$use_size = 0;
$er = "";
// lấy dữ liệu người dùng từ db
if ($num = mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_array($query)) {
    $name = $row['name'];
    $use_size = $row['use_size'];
    $password = $row['password'];
    $phone_us = $row['phone'];
    $gender_us = $row['gender'];
  }
}
// kiểm tra submit form
if (isset($_POST['submit']) && $_POST['submit'] == "Lưu thay đổi") {
  $phone = $_POST['phone'];
  $gender = $_POST['gender'];
// kiểm tra thông tin đầu vào
  if ($phone == '' && $gender == '') {
    $er = "Vui lòng đầy đủ thông tin";
  } else if ($phone == '') {
    $er = "Vui lòng nhập số điện thoại";
  } else if ($gender == '') {
    $er = "Vui lòng chọn giới tính";
  } else {
// update thông tin người dùng lên db
    $sql_up = "UPDATE users SET phone = '$phone',gender='$gender' WHERE username = '$username'";
    $query_up = mysqli_query($connect, $sql_up);
    if ($query_up) {
      $er = 'Cập nhật thành công';
    } else {
      echo mysqli_errno($connect);
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/changePassword.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <title>Chỉnh sửa thông tin cá nhân của User</title>
</head>

<body style="display: block;">
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
    <div class="title">Chỉnh sửa thông tin cá nhân</div>
    <!-- Hiển thị thông tin người dùng -->
    <div class="content">
      <form action="#" method="POST">
        <div class="user-details">
          <div class="input-box">
            <span class="details">Họ và tên</span>
            <input type="text" placeholder="Vui lòng nhập họ và tên" required value="<?php echo $name ?>">
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="text" placeholder="Vui lòng nhập email" required value="<?php echo $username ?>">
          </div>
          <div class=" input-box">
            <span class="details">Số điện thoại</span>
            <input type="text" placeholder="Vui lòng nhập số điện thoại" required name="phone" value="<?php echo $phone_us ?>">
          </div>
        </div>
        <div class="gender-details">
          <?php
          if ($gender_us == 0) {
          ?>
            <input type="radio" name="gender" id="dot-1" value="1">
            <input type="radio" name="gender" id="dot-2" value="0" checked>
          <?php
          } else {
          ?>
            <input type="radio" name="gender" id="dot-1" value="1" checked>
            <input type="radio" name="gender" id="dot-2" value="0">
          <?php
          }
          ?>

          <span class="gender-title">Giới tính</span>
          <div class="category" style="padding-right:45%;padding-left:5%;">
            <label for="dot-1">
              <span class="dot one"></span>
              <span class="gender">Nam</span>
            </label>
            <label for="dot-2">
              <span class="dot two"></span>
              <span class="gender">Nữ</span>
            </label>
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
        <div class="button">
          <input type="submit" value="Lưu thay đổi" name="submit">
        </div>
      </form>
    </div>
  </div>

</body>

</html>