<?php
    include_once("./config.php");
    $connect = connect();
    session_start();

    // rename all files, folder exist in a folder when rename it
    function renameAllChildFolder($connect, $old_folder_name, $new_folder_name) {
        $ok = true;

        $q2 = "SELECT * FROM file WHERE folder = '$old_folder_name'";
        $exec2 = mysqli_query($connect, $q2);
        if (mysqli_num_rows($exec2) != 0) {
            $new_link = '';
            while ($row = mysqli_fetch_assoc($exec2)) {
                $array = explode("/",$row['image']);
                for ($i=0; $i < count($array)-1; $i++) { 
                    if($array[$i] == $old_folder_name) {
                        $array[$i] = $new_folder_name;
                    }
                }
                $new_link = $new_link . join('/', $array);

                $q3 = "UPDATE file SET image = '$new_link', folder = '$new_folder_name' WHERE folder = '$old_folder_name'";
                $exec3 = mysqli_query($connect, $q3);
                if(!$exec3) {
                    $ok = false;
                }
            }
        }

        $q1 = "UPDATE folder SET parent = '$new_folder_name' WHERE parent = '$old_folder_name'";
        $exec1 = mysqli_query($connect, $q1);
        if(!$exec1) {
            $ok = false;
        }
        
        return $ok;
    }

    // rename file
    if(isset($_POST['usernameFI']) && isset($_POST['new_nameFI']) && isset($_POST['old_nameFI']) && isset($_POST['idFI'])) {
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/'.$_POST['usernameFI'].'/';
        // $dir = $_SERVER['DOCUMENT_ROOT'] . '/source' . '/files/'.$_POST['usernameFI'].'/';
        $dir_sql = 'files/'.$_POST['usernameFI'].'/';

        $folder_check_exist = '';
        if (count($_SESSION['path']) > 0) {
            $dir = $dir.join('/', $_SESSION['path']).'/';
            $dir_sql = $dir_sql.join('/', $_SESSION['path']).'/';

            $folder_check_exist = $_SESSION['path'][count($_SESSION['path']) - 1];
        }
        $id = $_POST["idFI"];
        $new_name = $_POST['new_nameFI'];
        $old_name = $_POST['old_nameFI'];

        // not allow new name has the same name with a file in the same folder
        $update_ok = true;
        $check_exist;
        if($folder_check_exist == '') {
            $check_exist = "SELECT * FROM file WHERE file_name = '$new_name' and file_name != '$old_name' and folder is NULL";
        } else {
            $check_exist = "SELECT * FROM file WHERE file_name = '$new_name' and file_name != '$old_name' and folder = '$folder_check_exist'";
        }
        $check_exec = mysqli_query($connect, $check_exist);
        if(mysqli_num_rows($check_exec) != 0) {
            $update_ok = false;
            $output = "Tập tin ".$new_name." đã tồn tại trong thư mục hiện tại.\nVui lòng thử lại với tên khác!";
            echo json_encode(array('message' => $output));
        }

        if($update_ok) {
            // echo $dir. $new_name.'<br>';
            // echo $dir. $old_name.'<br>';
            rename($dir.$old_name.'', $dir.$new_name.'');
            $new_image = $dir_sql.$new_name;
    
            $q;
            if($_POST['isImg'] == '1') {
                $q = "UPDATE file SET file_name = '$new_name', image = '$new_image' WHERE id = '$id'";
            } else {
                $q = "UPDATE file SET file_name = '$new_name' WHERE id = '$id'";
            }
            if(mysqli_query($connect, $q)) {
                $output = "Đổi tên tập tin thành công!";
                echo json_encode(array('message' => $output));
            } else {
                $output = "Đổi tên tập tin không thành công!";
                echo json_encode(array('message' => $output));
            }
        }
    }
    // rename folder
    else if (isset($_POST['usernameFO']) && isset($_POST['new_nameFO']) && isset($_POST['old_nameFO']) && isset($_POST['idFO'])) {
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/'.$_POST['usernameFO'].'/';
        // $dir = $_SERVER['DOCUMENT_ROOT'] . '/source' . '/files/'.$_POST['usernameFO'].'/';
        if (count($_SESSION['path']) > 0) {
            $dir = $dir.join('/', $_SESSION['path']).'/';
        }
        $id = $_POST["idFO"];
        $new_name = $_POST['new_nameFO'];
        $old_name = $_POST['old_nameFO'];

        // not allow new name has the same name with other folders 
        $update_ok = true;
        $check_exist = "SELECT * FROM folder WHERE name = '$new_name' and name != '$old_name'";
        $check_exec =  mysqli_query($connect, $check_exist);
        if(mysqli_num_rows($check_exec) != 0) {
            $update_ok = false;
            $output = "Thư mục ".$new_name." đã tồn tại trong hệ thống.\nVui lòng thử lại với tên khác!";
            echo json_encode(array('message' => $output));
        }

        if($update_ok) {
            // echo $dir . $new_name.'<br>';
            // echo $dir . $old_name.'<br>';
            rename($dir.$old_name.'', $dir.$new_name.'');
    
            foreach ($_SESSION['path'] as $key) {
                if($key == $old_name) {
                    $key = $new_name;
                }
            }
    
            $changeOthers = renameAllChildFolder($connect, $old_name, $new_name);
    
            $q = "UPDATE folder SET name = '$new_name' WHERE id = '$id'";
            if(mysqli_query($connect, $q) && $changeOthers) {
                $output = "Đổi tên thư mục thành công!";
                echo json_encode(array('message' => $output));
            } else {
                $output = "Đổi tên thư mục không thành công!";
                echo json_encode(array('message' => $output));
            }
        }
    }
?>