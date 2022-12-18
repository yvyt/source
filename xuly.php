<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once('vendor/autoload.php');
// Connect db
include_once("./config.php");
    $connect=connect();
    $prefix=getPrefix();
    // Gửi mail cảnh báo
    if(isset($_GET['sendmail']) && isset($_GET['id']) && isset($_GET['id_bc'])){
        $email=$_GET['sendmail'];
        $id_bc=$_GET['id_bc'];
        $name="";
        $id=$_GET['id'];
        $file_name;
        $get_name="SELECT * FROM users WHERE username='$email'";
        $run=mysqli_query($connect,$get_name);
        if($run){
            if(mysqli_num_rows($run)>0){
                $data=mysqli_fetch_assoc($run);
                $name=$data['name'];
            }
        }
        else{
            echo mysqli_error($connect);
        }
        $get_file="SELECT * FROM file WHERE id='$id'";
        $r=mysqli_query($connect,$get_file);
        if($r){
            if (mysqli_num_rows($run) > 0) {
                $d = mysqli_fetch_assoc($r);
                $file_name = $d['file_name'];
            }
        } else {
            echo mysqli_error($connect);
        }
        if(sendMail($name,$email,$file_name,1)){
            $up = "UPDATE report SET xuly='1' WHERE id='$id_bc'";
            $sq = mysqli_query($connect, $up);
            if ($sq) {
            echo "<script>
                    alert('Gửi mail cảnh báo thành công!');
                    window.location.href='view_report.php';
                    </script>";
            } else {
            echo "<script>
                    alert('Đã xảy ra lỗi trong quá trình gửi mail!');
                    window.location.href='view_report.php';
                    </script>";
            }
            
        }
        else{
            echo "<script>
                    alert('Đã xảy ra lỗi trong quá trình gửi mail!');
                    window.location.href='view_report.php';
                    </script>";
        }

    }
    // Gỡ file
if(isset($_GET["blockfile"]) && isset($_GET['id']) && isset($_GET['id_bc'])){
    $email = $_GET['blockfile'];
    $id_delete = $_GET['id'];
    $file_name = '';
    $id_bc=$_GET['id_bc'];
    $file_size = 0;
    $use_size = 0;
    $name = "";
    $folder_parent = "";
    $sql_us = "SELECT * FROM users WHERE username='$email'";
    $run_qr = mysqli_query($connect, $sql_us);
    if ($run_qr) {
        $d = mysqli_fetch_assoc($run_qr);
        $use_size = $d['use_size'];
        $name = $d['name'];
    }
    $sql_sele = "SELECT * FROM file WHERE id='$id_delete' LIMIT 1";
    $r = mysqli_query($connect, $sql_sele);
    if ($r) {
        if ($num = mysqli_num_rows($r) > 0) {
            $data = mysqli_fetch_assoc($r);
            $folder_parent = $data['folder'];
            $file_name = $data['file_name'];
            $file_size = $data['size'];
        }
    }
    
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $email . '/';
    // $dir = $_SERVER['DOCUMENT_ROOT'] . '/source' . '/files/' . $email . '/';
    if ($folder_parent == "") {
        $n = $dir . $file_name;
        unlink($n);
    }
    
    else{
        $n=$dir.$folder_parent.'/';
        $get_parent="SELECT * FROM folder WHERE name='$folder_parent'";
        $sql_folder=mysqli_query($connect,$get_parent);
        if($sql_folder){
            if(mysqli_num_rows($sql_folder)>0){
                $da=mysqli_fetch_assoc($sql_folder);
                if($da['parent']==""){
                    $n=$n.$file_name;
                    unlink($n);
                }
                
            }
        }
        else{
            mysqli_error($connect);
        }
    }
    $sql_dele = "DELETE FROM file WHERE id='$id_delete'";
    $query_dele = mysqli_query($connect, $sql_dele);
    if ($query_dele) {
        $new_size = $use_size - $file_size;
        echo $new_size;
        $update = "UPDATE users SET use_size='$new_size' WHERE username='$email'";
        $res = mysqli_query($connect, $update);
        if ($res) {
            if (sendMail($name, $email, $file_name, 2)) {
                $up = "UPDATE report SET xuly='1' WHERE id='$id_bc'";
                $sq = mysqli_query($connect, $up);
                if ($sq) {
                    echo "<script>
                    alert('Gỡ tài liệu thành công!');
                    
                    </script>";
                } else {
                    echo "<script>
                    alert('Đã xảy ra lỗi vui lòng thử lại!');
                    window.location.href='view_report.php';
                    </script>";
                }
            }
        }
    } else {
        echo "<script>
                    alert('Đã xảy ra lỗi trong quá trình gửi mail!');
                    window.location.href='view_report.php';
                    </script>";
    }
}
// Gỡ tài khoản
if(isset($_GET['blockuser']) && isset($_GET['id']) && isset($_GET['id_bc'])){
    $email = $_GET['blockuser'];
    $id_bc = $_GET['id_bc'];
    $name = "";
    $id = $_GET['id'];
    $file_name;
    $get_name = "SELECT * FROM users WHERE username='$email'";
    $run = mysqli_query($connect, $get_name);
    if ($run) {
        if (mysqli_num_rows($run) > 0) {
            $data = mysqli_fetch_assoc($run);
            $name = $data['name'];
        }
    } else {
        echo mysqli_error($connect);
    }
    $get_file = "SELECT * FROM file WHERE id='$id'";
    $r = mysqli_query($connect, $get_file);
    if ($r) {
        if (mysqli_num_rows($run) > 0) {
            $d = mysqli_fetch_assoc($r);
            $file_name = $d['file_name'];
        }
    } else {
        echo mysqli_error($connect);
    }
    echo $email;
    $sql_dele = "DELETE FROM users WHERE username='$email'";
    $de=mysqli_query($connect,$sql_dele);
    if($de){
        $de_fo="DELETE FROM folder WHERE username='$email'";
        $run_fo=mysqli_query($connect,$de_fo);
        if($run_fo){
            $de_f = "DELETE FROM file WHERE username='$email'";
            $run_defi = mysqli_query($connect, $de_f);
            if($run_defi){
                if (sendMail($name, $email, $file_name, 3)) {
                    $up = "UPDATE report SET xuly='1' WHERE id='$id_bc'";
                    $sq = mysqli_query($connect, $up);
                    if ($sq) {
                        echo "<script>
                    alert('Gỡ tài khoản thành công!');
                    window.location.href='view_report.php';
                    </script>";
                    } else {
                        echo "<script>
                    alert('Đã xảy ra lỗi vui lòng thử lại!');
                    window.location.href='view_report.php';
                    </script>";
                    }
                }
            }
        }
        
        else{
            echo "<script>
                    alert('Đã xảy ra lỗi vui lòng thử lại!');
                    window.location.href='view_report.php';
                    </script>";
        }
    }
    else{
        echo "<script>
                    alert('Đã xảy ra lỗi vui lòng thử lại!');
                    window.location.href='view_report.php';
                    </script>";
    }
    
}
// Gửi mail
function sendMail($name, $email,$file_name,$type)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8'; 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'vytuong2903@gmail.com'; 
        $mail->Password = 'dylbprsjmoxllict'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

        $mail->setFrom('vytuong2903@gmail.com', 'File Manager');
        $mail->addAddress($email, 'Receiver'); 

        $mail->isHTML(true); 
        $mail->Subject = 'Cảnh báo vi phạm';
        $mail->Body = "<p>Xin chào, '" . $name . "'! Tôi là quản trị viên trang web File Manager,</p>".getMess($type,$file_name);   
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo $e;
        return false;
    }
}
function getMess($i,$file_name){
    if ($i==1){
        return
        "<p>Chúng tôi nhận thấy tập tin " . $file_name . " của bạn không tuân thủ quy định của trang web!</p>
        <p>Vì vậy, chúng tôi gửi mail nhắc nhở đối với lần vi phạm đầu tiên. Chúng tôi chỉ nhắc nhở một lần.Khi chúng tôi phát hiện nội dung của bạn vi phạm Nguyên tắc cộng đồng một lần nữa, chúng tôi sẽ gỡ tài liệu hoặc gỡ bỏ tài khoản của bạn tại hệ thống.</p>" .
        "<p>Trân trọng,</p>";
    }
    else if($i==2){
        return
        "<p>Chúng tôi nhận thấy tập tin " . $file_name . " của bạn không tuân thủ quy định của trang web lần hai!</p>
         <p>Chúng tôi chỉ nhắc nhở một lần.Vì vậy, chúng tôi đã tiến hành gỡ tập tin ".$file_name. " ra khỏi hệ thống.</p>
         Khi chúng tôi phát hiện nội dung của bạn vi phạm Nguyên tắc cộng đồng một lần nữa, chúng tôi sẽ gỡ bỏ tài khoản của bạn tại hệ thống.
         <p>Trân trọng,</p>";
    }
    else{
        return
        "<p>Chúng tôi nhận thấy tập tin " . $file_name . " của bạn không tuân thủ quy định của trang web lần hai!</p>
         <p>Chúng tôi chỉ nhắc nhở một lần.Vì vậy, chúng tôi đã tiến hành gỡ bỏ tài khoản của bạn tại hệ thống.
         <p>Trân trọng,</p>";
    }
}
?>