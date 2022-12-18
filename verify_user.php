<h2 style="text-align: center;">Nhấn <a href="./login.php">vào đây để trở lại trang đăng nhập</a></h2>
<?php
session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>