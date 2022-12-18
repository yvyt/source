<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password Page</title>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- <link type="text/css" rel="stylesheet" href="./CSS/style.css"> -->
    <link rel="stylesheet" href="./CSS/style.css?v=<?php echo time(); ?>">

</head>

<body>
    <?php
    // Thêm file connect db
    include("./config.php");
    $conn = connect();
    // Kiểm tra token
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $query = "SELECT * FROM forgot_password WHERE token = '$token'";
        $r = mysqli_query($conn, $query);

        if (mysqli_num_rows($r) > 0) {
            $row = mysqli_fetch_array($r);
            $email = $row['username'];
        }
    }
    ?>
    <div class="background"></div>
    <div class="login-page">
        <div class="form">
            <form class="login-form" id="ResetPasswordForm">
                <h2 class="text-center">Đổi mật khẩu</h2>
                <div id="msg" style="color:green; margin-bottom: 12px;" class="form-message"></div>
                <input id="email" type="email" name="email" class="form-control" placeholder="Your Email"
                    value="<?php echo $email; ?>" />
                <input id="password" type="password" name="password" class="form-control" placeholder="Password" />
                <input id="confirmpassword" type="password" name="confirmpassword" class="form-control"
                    placeholder="Confirm Password" />
                <button id="ResetButton" type="submit" name="submit" value="Reset Password">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
<!-- Gửi dữ liệu đến trang xử lý -->
    <script type="text/javascript">
        $(document).ready(function () {

            $("#ResetPasswordForm").on('submit', function (e) {
                e.preventDefault();

                var email = $("#email").val();
                var password = $("#password").val();
                var confirmpassword = $("#confirmpassword").val();

                $.ajax({
                    type: "POST",
                    url: "reset_password.php",
                    data: { email: email, password: password, confirmpassword: confirmpassword },
                    success: function (data) {
                        $(".form-message").css('display', 'block');
                        $(".form-message").html(data);
                        $("#ResetPasswordForm")[0].reset();
                    }
                });
            })
        });
    </script>
</body>

</html>