<?php
// thêm file connect db
include_once("./config.php");
$connect = connect();
session_start();
$err = '';
$ma_bc = "BC";
// lấy và ktra role của người dùng
$role = $_SESSION['role'];
if ($role == 0) {
    header('Location: indexAdmin.php');
    exit();
}
$id_rep = $_SESSION['user'];

if (isset($_GET['baocao'])) {
  $id = $_GET['baocao'];
  $name = '';
  $own = '';

// Hiện thị file cần báo cáo
  $sl = "SELECT * FROM file WHERE id='$id'";
  $que = mysqli_query($connect, $sl);
  if ($que) {
    $d = mysqli_fetch_assoc($que);
    $srcf = $d['image'];
    $name = $d['file_name'];
    $own = $d['username'];
  } else {
    $err = 'Đã xảy ra sự cố trong quá trình báo cáo. Vui lòng thử lại.';
  }
}
// Kiểm tra submit
if (isset($_GET['baocao']) && isset($_POST['submit'])) {
  $type = $_POST['type'];
  if (!isset($type)) {
    $err = "Vui lòng chọn loại vi phạm";
  } else {
    $ma_bc = createMa($connect);
// Thêm vào db
    $ins = "INSERT INTO report(ma_bc,id_file,type, own, who_report) VALUE('" . $ma_bc . "','" . $id . "','" . $type . "','" . $own . "','" . $id_rep . "')";
    $run = mysqli_query($connect, $ins);
    if ($run) {
      $err = "Cảm ơn bạn đã báo cáo hành vi vi phạm cho chúng tôi!";
    } else {
      $err = 'Đã xảy ra sự cố trong quá trình báo cáo. Vui lòng thử lại.';
    }
  }
}
// Tạo mã báo cáo tự động
function createMa($connect)
{
  $ma = '';
  $sl = "SELECT * FROM report";
  $q = mysqli_query($connect, $sl);
  if ($q) {
    $num = mysqli_num_rows($q);
    if ($num < 10) {
      $ma = 'BC00' . ($num + 1);
    } else if ($num >= 10 && $num < 100) {
      $ma = 'BC0' . ($num + 1);
    } else {
      $ma = 'BC' . ($num + 1);
    }
  }
  return $ma;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="./CSS/addUser.css?v=<?php echo time(); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <title>Báo cáo</title>
</head>

<body>

  <body>
    <div class="container">
      <div>
        
          <a href="share_with_me.php"><i class="material-icons">arrow_back</i></a>
      </div>
      <div class="title">Báo cáo vi phạm điều khoản của quản lý tập tin</div>
      <div class="content">
        <form action="report.php?baocao=<?php echo $id ?>" method="POST">
          <div class="user-details">
            <div class="input-box">
              <span class="details" style="font-size: 25px;font-weight: 500;">Tập tin</span>
              <div class="row">
                <div class="col-lg-12 col-md-4">
                  <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px; z-index: 2">
                    <img src="./<?php echo $srcf ?>" class="card-img-top">
                    <div class="card-body">
                      <p class="card-text"><?php echo $name ?></p>
                      <!-- <a href="#" class="btn btn-primary" style="background-color:  rgb(118, 159, 205); border:none;">Xem</a>
                        <a href="#" class="btn btn-primary" style="background-color:  rgb(235, 29, 54); border:none;">Xóa</a> -->
                    </div>
                  </div>
                </div>
                <!-- <input type="text" placeholder="Vui lòng xác nhận mật khẩu" required> -->
              </div>
            </div>
            <div class="input-box">
              <input type="radio" name="type" id="dot-1" value="Thư rác">
              <input type="radio" name="type" id="dot-2" value="Phần mềm độc hại">
              <input type="radio" name="type" id="dot-3" value="Lừa đảo">
              <input type="radio" name="type" id="dot-4" value="Bạo lực">
              <input type="radio" name="type" id="dot-5" value="Thông tin cá nhân và bí mật">
              <input type="radio" name="type" id="dot-6" value="Các hoạt động bất hợp pháp">
              <input type="radio" name="type" id="dot-7" value="Vi phạm bản quyền">
              <input type="radio" name="type" id="dot-8" value="Nguy hiểm cho trẻ em">
              <span class="gender-title" style="font-size: 25px;font-weight: 500;">Chọn loại vi phạm</span>
              <div class="category" style="padding-right:45%;padding-left:5%;display:inline">
                <label for="dot-1" style="margin-top:10%;">
                  <span class="dot one"></span>
                  <span class="gender">Thư rác</span>
                </label>
                <label for="dot-2" style="margin-top:5%;">
                  <span class="dot two"></span>
                  <span class="gender">Phần mềm độc hại</span>
                </label>
                <label for="dot-3" style="margin-top:5%;">
                  <span class="dot three"></span>
                  <span class="gender">Lừa đảo</span>
                </label>
                <label for="dot-4" style="margin-top:5%;">
                  <span class="dot four"></span>
                  <span class="gender">Bạo lực</span>
                </label>
                <label for="dot-5" style="margin-top:5%;">
                  <span class="dot five"></span>
                  <span class="gender">Thông tin cá nhân và bí mật</span>
                </label>
                <label for="dot-6" style="margin-top:5%;">
                  <span class="dot six"></span>
                  <span class="gender">Các hoạt động bất hợp pháp</span>
                </label>
                <label for="dot-7" style="margin-top:5%;">
                  <span class="dot seven"></span>
                  <span class="gender">Vi phạm bản quyền</span>
                </label>
                <label for="dot-8" style="margin-top:5%;">
                  <span class="dot eight"></span>
                  <span class="gender">Nguy hiểm cho trẻ em</span>
                </label>
              </div>
            </div>
          </div>
          <p class="text text-danger"> <?php if (isset($err)) echo $err ?></p>
          <div class="button">
            <input type="submit" value="Báo Cáo" name="submit">
          </div>
        </form>
      </div>
    </div>

  </body>
</body>

</html>