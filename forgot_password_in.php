<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('vendor/autoload.php');
// thêm connect file db
include_once("./config.php");

$conn = connect();
// lấy dữ liệu input email
if (isset($_POST['email'])) {

    $email = $_POST['email'];
// lấy dữ liệu người dùng từ email
    $query = "SELECT * FROM users WHERE username = '$email'";

    $r = mysqli_query($conn, $query);
// kiểm tra đầu vào
    if (empty($email)) {
        echo "Vui lòng nhập email";
    } else {
        if (mysqli_num_rows($r) > 0) {
            // thông báo đã gửi mail để đặt lại mật khẩu
            echo "Hãy kiểm tra email của bạn để lấy lại mật khẩu";
            $token = uniqid(md5(time()));
            // thêm token vào table forgot_password
            $insert_query = "INSERT INTO forgot_password(username, token) VALUES ('$email','$token')";
            $res = mysqli_query($conn, $insert_query);
            // gửi mail thông báo cho người dùng
            sendResetMail($email, $token);
        } else {
            echo "Email chưa được đăng ký tài khoản. Vui lòng nhập lại";
        }
    }
}
// setup gửi mail
function sendResetMail($email, $token)
{
    $prefix = getPrefix();
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
        $mail->Subject = 'Reset Password';
        $mail->Body = "<p>Xin chào, Tôi là quản trị viên trang web File Manager,</p>
        <p>Cảm ơn bạn đã sử dụng trang web của chúng tôi!</p>
        <p>Click vào<a href ='$prefix/reset.php?token=$token'> đây</a> để đặt lại mật khẩu</p>";
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo $e;
        return false;
    }
}
?>