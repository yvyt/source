<?php
    include_once("./config.php");
    $connect = connect();
    $filename=$_POST['filename'];
    $folder_container = $_POST['folder'];

    session_start();
    $folder=$_SESSION['user'];
    if (!file_exists('files/' . $folder)) {
        mkdir("files/" . $folder, 0777, true);
    }
    $dir_sql = 'files/'. $folder.'/';
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/'.$folder.'/';
    // $dir = $_SERVER['DOCUMENT_ROOT'] . '/source' . '/files/'.$folder.'/';
    if(count($_SESSION['assign_path']) > 0) {
        $dir = $dir.join('/', $_SESSION['assign_path']).'/';
        $dir_sql = $dir_sql.join('/', $_SESSION['assign_path']).'/';
    }

    $uploadOk = 1;
    $target_file=$dir.basename($_FILES["file"]["name"]);
    $type=strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $size= $_FILES["file"]["size"];
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $t = date('y-m-d h:i:s');
    $use=0;
    $limit=0;
    $sql_sl="SELECT * FROM users WHERE username='".$folder."'";
    $run=mysqli_query($connect,$sql_sl);
    $num=mysqli_num_rows($run);
    if($num>0){
        $data=mysqli_fetch_assoc($run);
        $use=$data['use_size'];
        $limit=$data['size_page'];
    }
    $image="CSS/images/";
    if($type == 'doc' || $type == 'docx'){
        $image = $image.'doc.png';
    }
    else if($type == 'pdf'){
        $image = $image.'pdf.png';
    }
    else if($type == 'ppt' || $type == 'pptx'){
        $image = $image . 'ppt.png';
    }
    else if($type == 'txt'){
        $image = $image . 'txt.png';
    }
    else if($type == 'mp3'){
        $image = $image . 'mp3.png';
    }
    else if($type == 'xlsx'){
        $image = $image . 'xls.png';
    }
    else {
        $image = $dir_sql . $filename;
    }
    $new_path = $dir_sql.$filename;
    if (file_exists($target_file)) {
        echo "Tệp tin đã tồn tại.";
        $uploadOk = 0;
    }
    if ($_FILES["file"]["size"] > 5000000) {
        echo "Dung lượng tệp tin quá lớn." ;
        $uploadOk = 0;
    }
    if ($type !== "txt" && $type !== "doc" && $type !== "docx"&& $type !== "jpg"&& $type !== "jpeg"&& $type !== "pdf"&& $type !== "png"&& $type !== "mp3"&& $type !== "ppt"&& $type !== "pptx"&& $type !== "xlsx") {
        echo "Không hỗ trợ lưu trữ tệp tin này." ;
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        echo "Không thể tải lên tệp tin.";
    }
    else {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $new_path)) {
            $use = $use+ $size;
            if($use>$limit) {
                echo 'Dung lượng lưu trữ đã đạt tối đa. Không thể lưu trữ thêm';
            }
            else {
                $insert;
                if($folder_container == '') {
                    $insert = "INSERT INTO file(username,file_name,type,size,modify,deleted,open_time,image) VALUE('" . $folder . "','" . $filename . "','" . $type . "','" . $size . "','" . $t . "','0','" . $t . "','" . $image . "')";
                } else {
                    $insert = "INSERT INTO file(username,file_name,type,size,modify,deleted,open_time,image,folder) VALUE('" . $folder . "','" . $filename . "','" . $type . "','" . $size . "','" . $t . "','0','" . $t . "','" . $image . "','" . $folder_container . "')";
                }
                $query = mysqli_query($connect, $insert);
                $update = "UPDATE users SET use_size='$use' WHERE username='$folder'";
                $query1 = mysqli_query($connect, $update);
                if ($query && $query1) {
                    echo htmlspecialchars(basename($_FILES["file"]["name"])) . " đã được tải lên thành công";
                } else {
                    echo mysqli_error($connect);
                }
            }
        }
        else {
            echo "Đã có sự cố trong quá trình tải lên.Vui lòng thử lại" ;
        }
    }
    
?>