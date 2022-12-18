<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="./CSS/style.css?v=<?php echo time(); ?>">

</head>

<body>

    <div class="background"></div>

    <div class="login-page">
        <div class="form">
            <div style="text-align: start">

                <a href="login.php"><i class="material-icons">arrow_back</i></a>
            </div>
            <form class="login-form" id="ForgotPasswordForm" action="" method="POST">
                <h2 class="text-center">Quên mật khẩu</h2>
                <div id="msg" style="color:red;" class="form-message"></div>
                <input id="email" type="email" name="email" class="form-control" placeholder="Your Email" />
                <button id="ResetButton" type="submit" name="submit" value="Reset Password">Đặt lại mật khẩu</button>
            </form>
        </div>
    </div>
<!-- Gửi email đến trang forgot_password.php thông qua ajax -->
    <script type="text/javascript">
        $(document).ready(function() {

            $("#ForgotPasswordForm").on('submit', function(e) {
                e.preventDefault();

                var email = $("#email").val();
                $.ajax({
                    type: "POST",
                    url: "forgot_password_in.php",
                    data: {
                        email: email
                    },
                    success: function(data) {
                        $(".form-message").css('display', 'block');
                        $(".form-message").html(data);
                    }
                })
            })
        })
    </script>
</body>

</html>