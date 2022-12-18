<?php
// thêm file connect vào database
    include_once("./config.php");
    $connect=connect();
    session_start();
// lấy email từ session
    $email=$_SESSION['user'];
    $folder_name;
// xóa thư mục 
    function delFolder($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? delFolder("$dir/$file") : unlink("$dir/$file");
            }
        return rmdir($dir);
    }
// xóa thư mục và file bên trong thư mục đó
    function delFolderAndFileInIt($connect,$selecting_folder,$id_folder) {
        $ok = true;
        $sql_delAllFile="DELETE FROM file WHERE folder='$selecting_folder'";
        $exec_dellAllFile=mysqli_query($connect,$sql_delAllFile);
        if(!$exec_dellAllFile) {
            $ok = false;
        }
        $sql="DELETE FROM folder WHERE id='$id_folder'";
        $exec=mysqli_query($connect,$sql);
        if(!$exec) {
            $ok = false;
        }
        return $ok;
    }

    // recursion to delete folder and files in it
    function delFfDb($connect,$id_del_folder,$selecting_folder) {
        $del_ok = true;
        $sql = "SELECT * FROM folder WHERE parent='$selecting_folder'";
        $exec = mysqli_query($connect,$sql);
        // echo 'num = '.mysqli_num_rows($exec) . "";
        if(mysqli_num_rows($exec) > 0) {
            while ($row = mysqli_fetch_assoc($exec)) {
                $id_del_folder = $row['id'];
                $name_del_folder = $row['name'];
                delFfDb($connect, $id_del_folder, $name_del_folder);
            }
        }
        if(!delFolderAndFileInIt($connect,$selecting_folder,$id_del_folder)) {
            $del_ok = false;
        }
        return $del_ok;
    }

    if($_SESSION['assign_folder'] == '') {
        $folder_name = $email;
    } else {
        $folder_name = $_SESSION['assign_folder'];
    }
    $dir="files/".$email.'/';
    $dir_absolute = $_SERVER['DOCUMENT_ROOT'] . '/files/'.$email.'/';
    // $dir_absolute = $_SERVER['DOCUMENT_ROOT'] . '/source' . '/files/'.$email.'/';
    if(count($_SESSION['path']) > 0) {
        $dir = $dir.join('/', $_SESSION['path']).'/';
        $dir_absolute = $dir_absolute.join('/', $_SESSION['path']).'/';
    }

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $t = date('y-m-d h:i:s');

    // xoa folder vao thung rac
    if(isset($_POST['del_folder_to_trash'])) {
        $id = $_POST['id'];
        $sql = "UPDATE folder SET deleted='1', modify='$t' WHERE id='$id'";
        $query = mysqli_query($connect, $sql);
        if ($query) {
            echo 'Xóa thành công';
        } else {
            echo 'Xảy ra lỗi.Vui lòng thử lại';
        }
    }
    // khoi phuc folder tu thung rac
    else if(isset($_POST['restore_folder'])) {
        $id = $_POST['id'];
        $sql = "UPDATE folder SET deleted='0',modify='$t' WHERE id='$id'";
        $query = mysqli_query($connect, $sql);
        if ($query) {
            echo 'Khôi phục thành công';
        } else {
            echo 'Xảy ra lỗi.Vui lòng thử lại';
        }
    }
    // xu ly xoa vinh vien folder
    else if(isset($_POST['delete_folder_forever'])) {
        $id_folder = $_POST['id'];
        // get folder name
        $del4ever_folder = '';
        $flag = true;

        $sql = "SELECT * FROM folder WHERE id='$id_folder' LIMIT 1";
        $r=mysqli_query($connect,$sql);
        if($r){
            if($num=mysqli_num_rows($r)>0){
                $data=mysqli_fetch_assoc($r);
                $del4ever_folder=$data['name'];
            }
            else {
                $flag = false;
            }
        }
        // echo 'del4ever_folder = ' . $del4ever_folder.'<br>'.'\n';
        // echo 'path = ' . $dir_absolute . $del4ever_folder.'<br>';

        // get usable size
        $use_size=0;
        $sql_us="SELECT * FROM users WHERE username='$email'";
        $run_qr=mysqli_query($connect,$sql_us);
        if($run_qr){
            $d=mysqli_fetch_assoc($run_qr);
            $use_size=$d['use_size'];
        }
        else {
            $flag = false;
        }

        // remove in db
        $id_folder = $_POST['id'];
        if(!delFfDb($connect, $id_folder, $del4ever_folder)) {
            $flag = false;
        }
        
        $sql="DELETE FROM folder WHERE id='$id_folder'";
        $exec=mysqli_query($connect,$sql);
        if(!$exec) {
            $flag = false;
        }

        // remove in local
        if(!delFolder($dir_absolute.$del4ever_folder)) {
            $flag = false;
        }

        $update="UPDATE users SET use_size='$use_size' WHERE username='$email'";
        if(!mysqli_query($connect,$update)) {
            $flag = false;
        }

        if($flag) {
            echo 'Xóa thành công';
        } else {
            echo 'Đã xảy ra sự cố trong quá trình xóa. Vui lòng thử lại';
        }
        // echo 'use_size = '.$use_size.'<br>';
    }
    // xóa file vĩnh viễn từ thùng rác
    else if(isset($_GET['xoa']) && $_GET['xoa'] == 1){
        $id_delete=$_POST['id'];
        $file_name='';
        $file_size=0;
        $use_size=0;
        $sql_us="SELECT * FROM users WHERE username='$email'";
        $run_qr=mysqli_query($connect,$sql_us);
        if($run_qr){
            $d=mysqli_fetch_assoc($run_qr);
            $use_size=$d['use_size'];
        }
        $sql_sele="SELECT * FROM file WHERE id='$id_delete' LIMIT 1";
        $r=mysqli_query($connect,$sql_sele);
        if($r){
            if($num=mysqli_num_rows($r)>0){
                $data=mysqli_fetch_assoc($r);
                $file_name=$data['file_name'];
                $file_size=$data['size'];
            }
        }
        unlink($dir_absolute.$file_name);
        $sql_dele="DELETE FROM file WHERE id='$id_delete'";
        $query_dele=mysqli_query($connect,$sql_dele);
        if($query_dele){
            $new_size=$use_size-$file_size;
            // echo $new_size;
            $update="UPDATE users SET use_size='$new_size' WHERE username='$folder_name'";
            $res=mysqli_query($connect,$update);
            if($res){
                echo 'Xóa thành công';
            }
        }
        else{
            echo 'Đã xảy ra sự cố trong quá trình xóa. Vui lòng thử lại';
        }
    }
    // khôi phục file từ thùng rác
    else if(isset($_GET['khoiphuc']) && $_GET['khoiphuc']==1){
        $id = $_POST['id'];
        $sql = "UPDATE file SET deleted='0',modify='$t' WHERE id='$id'";
        $query = mysqli_query($connect, $sql);
        if ($query) {
            echo 'Khôi phục thành công';
        } else {
            echo 'Xảy ra lỗi.Vui lòng thử lại';
        }
    }
    // chuyển file vào thùng rác
    else {
        $id = $_POST['id'];
        $sql = "UPDATE file SET deleted='1' ,modify='$t' WHERE id='$id'";
        $query = mysqli_query($connect, $sql);
        if ($query) {
            echo 'Xóa thành công';
        } else {
            echo 'Xảy ra lỗi.Vui lòng thử lại';
        }
    }
