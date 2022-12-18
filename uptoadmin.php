<?php
    include_once("./config.php");
    $connect=connect();
    $prefix=getPrefix();
    session_start();
    $id=$_GET['id'];
    $sql="UPDATE users SET role='0' WHERE id='$id'";
    $query=mysqli_query($connect,$sql);
    if($query){
        echo "<script> alert('Nâng cấp thành công!');window.location.href='listOfUser.php'; </script>";
    }
    else{
        echo "<script> alert('Đã xảy ra lỗi trong quá trình nâng cấp!');window.location.href='listOfUser.php'; </script>";
    }
?>