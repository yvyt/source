<?php
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $t = date('y-m-d h:i:s');
    // Thêm file connect db
    include_once("./config.php");
    $connect=connect();
    // kiểm tra url và thêm file vào mục quan trọng
    if(isset($_GET['id'])){
        $id=$_GET['id'];
        // Cập nhật thuộc tính priority
        $sql= "UPDATE file SET priority='1',modify='$t' WHERE id='$id'";
        $run=mysqli_query($connect,$sql);
        if($run){
            header("Location:priority.php");
        }
        else{
            echo mysqli_error($connect);
        }
    }
    // Kiểm tra url và loại bỏ file khỏi mục quan trọng
    if(isset($_GET['huy'])){
        $id = $_GET['huy'];
        // set priority=0
        $sql = "UPDATE file SET priority='0',modify='$t' WHERE id='$id'";
        $run = mysqli_query($connect, $sql);
        if ($run) {
            header("Location:priority.php");
        } else {
            echo mysqli_error($connect);
        }
    }
    // Đặt priority cho folder
    if(isset($_GET['id_folder'])){
        $id = $_GET['id_folder'];
        $sql = "UPDATE folder SET priority='1',modify='$t' WHERE id='$id'";
        $run = mysqli_query($connect, $sql);
        if ($run) {
            header("Location:priority.php");
        } else {
            echo mysqli_error($connect);
        }
    }
    // Hủy priority của folder
    if (isset($_GET['folder_huy'])) {
        $id = $_GET['folder_huy'];
        $sql = "UPDATE folder SET priority='0',modify='$t' WHERE id='$id'";
        $run = mysqli_query($connect, $sql);
        if ($run) {
            header("Location:priority.php");
        } else {
            echo mysqli_error($connect);
        }
    }
?>