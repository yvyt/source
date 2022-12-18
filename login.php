<?php
include_once("./config.php");
session_start();
$connect = connect();
$er = "";
$url='/';
// $url='/source/';
// Kiểm tra đăng nhập 
if(isset($_SESSION['user'])) {
    header('Location: index.php');
}
// Kiểm tra trang trước 
if(isset($_SESSION['url'])){
    $url= $_SESSION['url'];
}
// Kiểm tra submit 
if (isset($_POST['submit']) && $_POST['submit'] == 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // kiểm tra dữ liệu đầu vào
    if (empty($email) && empty($password)) {
        $er = "Vui lòng nhập email và mật khẩu của bạn.";
    } else if (empty($email)) {
        $er = "Vui lòng nhập email của bạn.";
    } else if (empty($password)) {
        $er = "Vui lòng nhập mật khẩu của bạn";
    } else if (strlen($password) < 6) {
        $er = "Mật khẩu phải từ 6 chữ ký tự trở lên.";
    } else {
        // lấy password của user từ username tương ứng tại db
        $pw = md5($password);
        $sql = "SELECT * FROM users WHERE username='" . $email . "'";
        $query = mysqli_query($connect, $sql);
        $num = mysqli_num_rows($query);
        if ($num > 0) {
            $data = mysqli_fetch_assoc($query);
            // Đối chiếu mật khẩu
            if ($data['password'] == $pw) {
                // Lưu session user, role
                $_SESSION['user'] = $email;
                $_SESSION['role'] = $data['role'];
                // Kiểm tra role (role=1 -> người dùng, role=0 -> admin) và đưa đến trang chính phù hợp
                if($data['role']==1){
                    // Kiểm tra trang trước dẫn đến trang login
                    if (strpos($url, 'share_with_me.php') !== false) {
                        unlink($_SESSION['url']);
                        header("Location:$url");
                    } else {
                        header("Location:index.php");
                    }
                }
                else{
                    header("Location:indexAdmin.php");
                }
                
            } else {
                $er = "Mật khẩu không chính xác. Vui lòng nhập lại.";
            }
        } else {
            $er = "Tài khoản không tồn tại";
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
    <title>Login Page</title>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- <link type="text/css" rel="stylesheet" href="./CSS/style.css"> -->
    <link rel="stylesheet" href="./CSS/style.css?v=<?php echo time(); ?>">

</head>

<body>
    <div class="background"></div>
    <div class="login-page">
        <div class="form">


            <form class="login-form" action="" method="POST">
                <h2 class="text-center">ĐĂNG NHẬP</h2>
                <input id="email" type="text" name="email" class="form-control" placeholder="Nhập email" />
                <input id="password" type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" />
                <span id="errMessage2">
                    <?php if (isset($er)) {
                        // Hiển thị thông báo
                        echo $er;
                    } ?>
                </span>
                <button id="loginBtn" type="submit" name="submit" value="login">Đăng Nhập</button>
                <p style="text-align: end;" class="message">
                    <a href="forgot_password.php">Quên mật khẩu</a>
                </p>
                <p class="message">
                    Chưa có tài khoản?
                    <a href="signup.php">Đăng kí tại đây</a>
                </p>
            </form>
        </div>
    </div>

    <script src="JS/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>