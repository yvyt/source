<?php
    include_once("./config.php");
    $connect = connect();
    session_start();

    // $_SESSION['assign_path'] = array();

    // set path to move between folders in index page
    if(isset($_SESSION['path'])) {
        if(count($_SESSION['path']) != 0) {
            $_SESSION['assign_path'] = $_SESSION['path'];
        } else {
            $_SESSION['assign_path'] = array();
        }
    }
    // set path to move between folders in priority page
    if(isset($_SESSION['path_pri'])) {
        if(count($_SESSION['path_pri']) != 0) {
            $_SESSION['assign_path_pri'] = $_SESSION['path_pri'];
        } else {
            $_SESSION['assign_path_pri'] = array();
        }
    }
    // update path after moving to others in index page
    if(isset($_POST['change_path'])) {
        $cp = $_POST['change_path'];
        if($cp != '') {
            // array_push($_SESSION['assign_path'], $cp);
            // array_pop($_SESSION['assign_path']);
            if(!in_array($cp, $_SESSION['assign_path'])) {
                array_push($_SESSION['assign_path'], $cp);
            }
            else {
                $index = array_search($cp, $_SESSION['assign_path']);
                if($index != (count($_SESSION['assign_path']) - 1)) {
                    array_splice($_SESSION['assign_path'],  $index+1);
                }
            }
        }
        else {
            array_splice($_SESSION['assign_path'], 0);
        }
        
        $_SESSION['assign_folder'] = $cp;
        /* $t = 0;
        foreach ($_SESSION['assign_path'] as $key) {
            echo $t.' - '.$key.'<br>';
        }
        echo '<br>';
        echo $_SESSION['assign_folder']; */
        echo json_encode(array('folder' => $_SESSION['assign_folder'], 'path' => $_SESSION['assign_path']));
    }
    // update path after moving to others in priority page
    else if(isset($_POST['change_path_pri'])) {
        $cp = $_POST['change_path_pri'];
        if($cp != '') {
            if(!in_array($cp, $_SESSION['assign_path_pri'])) {
                array_push($_SESSION['assign_path_pri'], $cp);
            }
            else {
                $index = array_search($cp, $_SESSION['assign_path_pri']);
                if($index != (count($_SESSION['assign_path_pri']) - 1)) {
                    array_splice($_SESSION['assign_path_pri'],  $index+1);
                }
            }
        }
        else {
            array_splice($_SESSION['assign_path_pri'], 0);
        }
        
        $_SESSION['assign_folder_pri'] = $cp;
        echo json_encode(array('folder' => $_SESSION['assign_folder_pri'], 'path' => $_SESSION['assign_path_pri']));
    }
    // get data of a file and return in json type
    else if(isset($_POST['get_detail'])) {
        $id = $_POST['id'];
        $query = "SELECT * FROM file WHERE id = '$id'";
        $exec = mysqli_query($connect, $query);
        $num = mysqli_num_rows($exec);
        if ($num != 0) {
            while ($row = mysqli_fetch_assoc($exec)) {
                $output['file_name'] = $row['file_name'];
                $output['type'] = $row['type'];
                $output['size'] = $row['size'];
            }
            echo json_encode(array('data' => $output));
        }
    }

    // create new folder
    else if(isset($_POST['username']) && isset($_POST['name']) && isset($_POST['parent'])) {
        $username = $_POST['username'];
        $name = $_POST['name'];
        $parent = $_POST['parent'];
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $time = date('y-m-d h:i:s');

        // not allow to create same name in all folder
        $insert_ok = true;
        $check_exist = "SELECT * FROM folder WHERE name = '$name'";
        $check_exec =  mysqli_query($connect, $check_exist);
        if(mysqli_num_rows($check_exec) != 0) {
            $insert_ok = false;
            $output = "Thư mục ".$name." đã tồn tại trong hệ thống. Vui lòng thử lại với tên khác!";
            echo json_encode(array('data' => $output));
        }
        
        if($insert_ok) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/'.$username.'/';
            // $dir = $_SERVER['DOCUMENT_ROOT'] . '/source' . '/files/'.$username.'/';
            $dir = $dir.join('/', $_SESSION['assign_path']);
            mkdir($dir.'/'.$_POST['name'], 0777, true);

            if($parent == '') {
                $query = "INSERT INTO folder(username,name,date_create,modify) VALUE('" . $username . "','" . $name . "','" . $time . "','" . $time . "')";
            }
            else {
                $query = "INSERT INTO folder(username,name,parent,date_create,modify) VALUE('" . $username . "','" . $name . "','" . $parent . "','" . $time . "','" . $time . "')";
            }
            $exec = mysqli_query($connect, $query);
            if($exec) {
                $output = "Tạo thành công thư mục ".$name."!";
                echo json_encode(array('data' => $output));
            }
            else {
                $output = "Tạo không thành công thư mục ".$name;
                echo json_encode(array('data' => $output))."!";
            }
        }
    }
?>