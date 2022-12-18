<?php
// Thêm file connect db
include_once('./config.php');
$conn = connect();
// kiêm tra dữ liệu đầu vào
if (isset($_POST['email']) || ($_POST['password']) || ($_POST['confirmpassword'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    if (empty($password) || empty($confirmpassword)) {
        echo "Chưa nhập mật khẩu";
    } else if (strlen($password) < 6 || strlen($confirmpassword) < 6) {
        echo "Mật khẩu phải từ 6 chữ ký tự trở lên.";
    } else {
        if ($password == $confirmpassword) {
            // Cập nhật mật khẩu
            $hashed = md5($password);

            $query = "UPDATE users SET password = '$hashed' WHERE username = '$email'";

            $res = mysqli_query($conn, $query);

            echo "Mật khẩu đã cập nhật thành công. Click vào <a href='login.php'>đây</a> để đăng nhập lại";

            $query_dlt = "DELETE FROM forgot_password WHERE username = '$email'";
            $res_dlt = mysqli_query($conn, $query_dlt);
        } else {
            echo "Mật khẩu không khớp";
        }
    }
}
?>