<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('vendor/autoload.php');
// Thêm file connect db
include_once("./config.php");
session_start();
$connect = connect();
$err = "";
// Kiểm tra session
if(isset($_SESSION['user'])) {
    header('Location: index.php');
}
// Kiểm tra submit
if (isset($_POST['submit']) && $_POST['submit'] == 'signup') {
    $email_register = $_POST['email_register'];
    $name_register = $_POST['name'];
    $pw_register = $_POST['password_register'];
    $pw_again = $_POST['retype_password'];
    // tạo token
    $token = md5(random_int(100000, 999999));
    // Kiểm tra dữ liệu đầu vào
    if (empty($email_register) && empty($name_register) && empty($pw_register) && ($pw_again)) {
        $err = "Vui lòng nhập đầy đủ thông tin";
    } else if (empty($email_register)) {
        $err = "Vui lòng nhập email";
    } else if (empty($name_register)) {
        $err = "Vui lòng nhập họ tên";
    } else if (empty($pw_register)) {
        $err = "Vui lòng nhập mật khẩu";
    } else if (strlen($pw_register) < 6) {
        $err = "Mật khẩu phải có độ dài từ 6 ký tự trở lên.";
    } else if (empty($pw_again)) {
        $err = "Vui lòng nhập lại mật khẩu";
    } else if ($pw_register !== $pw_again) {
        $err = "Mật khẩu và nhập lại mật khẩu không trùng khớp. Vui lòng thử lại.";
    } else {
        // Kiểm tra email đăng ký có tồn tại không
        if (check_exist_email($connect, $email_register)) {
            $err = "Email này đã tồn tại.Vui lòng thử lại";
        } else {
            // thêm vào table user
            $password_register = md5($_POST['password_register']);
            // Gửi mail xác nhận danh tính
            if (sendToken($name_register, $email_register, $token)) {
                $sql_add = "INSERT INTO users(username,password,role,size_page,use_size,name,token) VALUES('" . $email_register . "','" . $password_register . "','1','104857600 ','0','" . $name_register . "','" . $token . "')";
                $query = mysqli_query($connect, $sql_add);
                // header("Location:login.php");
                if ($query) {

                    
                    $err = "Đăng ký thành công.Vui lòng kiểm tra lại email để xác nhận danh tính.";

                }
                else{
                    echo "Error: " . mysqli_error($connect);
                }
            }

        }
    }

}
// set up mail
function sendToken($name, $email, $token)
{
    $prefix=getPrefix();
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8'; //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = 'vytuong2903@gmail.com'; //SMTP username
        $mail->Password = 'dylbprsjmoxllict'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('vytuong2903@gmail.com', 'File Manager');
        $mail->addAddress($email, 'Receiver'); //Add a recipient

        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'Xác nhận danh tính';
        $mail->Body = "<p>Xin chào, '" . $name . "'! Tôi là quản trị viên trang web File Manager,</p>
        <p>Cảm ơn bạn đã sử dụng trang web của chúng tôi!</p>
        <p>Click vào<a href ='$prefix/verify_user.php?email=$email&token=$token'> đây</a> để đăng nhập!</p>";
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo $e;
        return false;
    }
}
// Kiểm tra mail tồn tại hay chưa
function check_exist_email($connect, $email_register)
{
    $sql_email = "SELECT * FROM users WHERE username='" . $email_register . "'";
    $query_email = mysqli_query($connect, $sql_email);
    $num_row = mysqli_num_rows($query_email);
    if ($num_row > 0) {
        return true;
    } else {
        return false;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head></head>
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
            <form action="" method="POST">
                <h2 class="text-center">ĐĂNG KÍ</h2>
                <input id="nameSignUp" type="text" placeholder="Họ tên" name="name" />
                <input id="emailSignUp" type="email" placeholder="Địa chỉ email" name="email_register" />
                <input id="passwordSignUp" type="password" placeholder="Mật khẩu" name="password_register" />
                <input id="passwordRetype" type="password" placeholder="Nhập lại mật khẩu" name="retype_password" />
                <span id="errMessage1">
                    <?php if (!empty($err))
                    echo $err; ?>
                </span>
                <button id="signupBtn" type="submit" name="submit" value="signup">Đăng Kí</button>
                <p class="message">
                    Đã có tài khoản?
                    <a href="login.php">Đăng Nhập</a>
                </p>
            </form>

        </div>
    </div>
    <script src="./JS/index.js"></script>
</body>

</html>