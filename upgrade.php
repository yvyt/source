<?php
session_start();
// Thêm file connect db
include_once("./config.php");
$connect = connect();
$username = $_SESSION['user'];
$role = $_SESSION['role'];
// Kiểm tra role
if ($role == 0) {
    header('Location: indexAdmin.php');
    exit();
}
// Lấy thông tin user
$sql = "SELECT * FROM users WHERE username='" . $username . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$name = "";
$password = "";
$phone_us = "";
$gender_us = 0;
$use_size = 0;
$er = "";
$id = 0;
if ($num = mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_array($query)) {
        $id = $row['id'];
        $name = $row['name'];
        $use_size = $row['use_size'];
        $password = $row['password'];
        $phone_us = $row['phone'];
        $gender_us = $row['gender'];
    }
}
$error = '';
// Kiểm tra submit
if (isset($_POST['submit']) && $_POST['submit'] == 'Thanh toán') {
    $goi = $_POST['goi_dung_luong'];
    $gia = (int)$_POST['priceData'];
    $name_card = $_POST['name_card'];
    $number_card = $_POST['number_card'];
    $cvv = $_POST['cvv'];
    $hethan = $_POST['overdate'];
    $dl = 0;
    if ($goi == "0") {
        $dl = 209715200;
    } else if ($goi == "1") {
        $dl = 524288000;
    } else {
        $dl = 1073741824;
    }
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    $today = date("d/m/Y");
    if ($today > $hethan) {
        $error = "Thẻ đã quá hạn để thanh toán.";
    } else {
    // Kiểm tra tài khoản thanh toán
        if ($number_card == '111111' && $cvv == '411') {
            if ($gia > 200000) {
                $error = "Hạn mức thanh toán thẻ không đủ để thanh toán gói cước này. Vui lòng thử lại";
            } else {
                $up = "UPDATE users SET size_page='$dl' WHERE id='$id'";
                $qu = mysqli_query($connect, $up);
                if ($qu) {
                    $error = "Thanh toán thành công";
                } else {
                    $error = mysqli_error($connect);
                }
            }
        } else if ($number_card == '222222' && $cvv == '443') {
            if ($gia > 800000) {
                $error = "Hạn mức thanh toán thẻ không đủ để thanh toán gói cước này. Vui lòng thử lại";
            } else {
                $up = "UPDATE users SET size_page='$dl' WHERE id='$id'";
                $qu = mysqli_query($connect, $up);
                if ($qu) {
                    $error = "Thanh toán thành công";
                }
                else{
                    $error=mysqli_error($connect);
                }
            }
        } else if ($number_card == '333333' && $cvv == '577') {
            if ($gia > 1500000) {
                $error = "Hạn mức thanh toán thẻ không đủ để thanh toán gói cước này. Vui lòng thử lại";
            } else {
                $up = "UPDATE users SET size_page='$dl' WHERE id='$id'";
                $qu = mysqli_query($connect, $up);
                if ($qu) {
                    $error = "Thanh toán thành công";
                } else {
                    $error = mysqli_error($connect);
                }
            }
        } else {
            $error = "Đã xảy ra sự cố trong quá trình thanh toán. Vui lòng thử lại";
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <link rel="stylesheet" href="./CSS/addUser.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <title>Thanh Toán</title>
</head>

<body>

    <body>
        <div class="container">
            <div>
                <a href="index.php"><i class="material-icons">arrow_back</i></a>
            </div>
            <div class="title">Mua dung lượng</div>
            <div class="content">
                <form action="#" method="POST">
                    <div class="user-details">
                        <input type="hidden" name="id_us" value="<?php echo $id ?>">
                        <div class="input-box">
                            <span class="details">Họ và tên</span>
                            <input type="text" placeholder="Vui lòng vào họ và tên" required value="<?php echo $name ?>">
                        </div>
                        <div class="input-box">
                            <span class="details">Số điện thoại</span>
                            <input type="text" placeholder="Vui lòng vào số điện thoại" required value="<?php echo $phone_us ?>">
                        </div>
                        <div class="input-box">
                            <span class="details">Email</span>
                            <input type="text" placeholder="Vui lòng vào email" required value="<?php echo $username ?>">
                        </div>
                        <!-- <div class="input-box">
                            <span class="details">Địa chỉ</span>
                            <input type="text" placeholder="Vui lòng vào địa chỉ" required >
                        </div> -->
                    </div>

                    <span class="details">Gói dung lượng</span>
                    <select class="form-select" aria-label="Default select example" id="goi_dung_luong" name="goi_dung_luong" onclick="showPrice()">
                        
                        <option selected value="0">200MB</option>
                        <option value="1">500MB</option>
                        <option value="2">1GB</option>
                    </select>
                    <p></p>
                    <p>Giá tiền: <span id="price" class="text text-danger">200,000 VND</span> </p>
                    <input type="hidden" id="priceData" name="priceData" value="200000">
                    <span class="gender-title">Phương Thức Thanh Toán</span>
                    <div class="user-details">
                        <div class="input-box">
                            <span class="details">Tên trên thẻ</span>
                            <input type="text" placeholder="Vui lòng vào tên trên thẻ" required name="name_card">
                        </div>
                        <div class="input-box">
                            <span class="details">Số thẻ tín dụng</span>
                            <input type="text" placeholder="Vui lòng vào số thẻ" required name="number_card">
                        </div>
                        <div class="input-box">
                            <span class="details">CVV/CVC</span>
                            <input type="text" placeholder="Vui lòng vào mã CVV/CVC" required name="cvv">
                        </div>
                        <div class="input-box">
                            <span class="details">Tháng hết hạn</span>
                            <input type="text" placeholder="Vui lòng vào tháng hết hạn trên thẻ" required name="overdate">
                        </div>
                    </div>
                    <p class="text text-danger"><?php if (isset($error)) echo $error ?></p>
                    <div class="button">
                        <input type="submit" value="Thanh toán" name="submit">
                    </div>
                </form>
            </div>
        </div>

    </body>
</body>
<script>
    function showPrice() {
        var goi = document.getElementById("goi_dung_luong").value;
        if (goi == 0) {
            document.getElementById("price").textContent = "200,000 VND";
            document.getElementById("priceData").value = "200000";

        } else if (goi == 1) {
            document.getElementById("price").textContent = "800,000 VND";
            document.getElementById("priceData").value = "800000";
        } else {
            document.getElementById("price").textContent = "1,500,000 VND";
            document.getElementById("priceData").value = "1500000";
        }
    }
</script>

</html>