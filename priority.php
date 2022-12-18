<?php
session_start();
// Thêm file connect db
include_once('./config.php');
if (isset($_SESSION['assign_path_pri'])) {
    $_SESSION['path_pri'] = $_SESSION['assign_path_pri'];
} else {
    $_SESSION['path_pri'] = array();
}

if (!isset($_SESSION['assign_folder_pri'])) {
    $_SESSION['assign_folder_pri'] = '';
}
// lấy dữ liệu 
$connect = connect();
$login = false;
$username = "";
$email;
$name = "";
// kiểm tra session role
$role = $_SESSION['role'];
// nếu đăng nhập bằng tài khoản admin => không truy cập dc vào trang này
if ($role == 0) {
    header('Location: indexAdmin.php');
    exit();
}
// Lưu session url
if (!isset($_SESSION['user'])) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}
$is_use = 0;
$max = 0;
$login = true;
$email = $_SESSION['user'];
// Lấy dữ liệu cá nhân user đang đăng nhập
$sql = "SELECT * FROM users WHERE username='" . $email . "' LIMIT 1";
$query = mysqli_query($connect, $sql);
$num_row = mysqli_num_rows($query);
if ($num_row > 0) {
    $data = mysqli_fetch_assoc($query);
    $name = $data['name'];
    $is_use = $data['use_size'];
    $max = $data['size_page'];
}
// đăng xuất
if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    unlink($_SESSION['url']);
    unset($_SESSION['user']);
    header('Location: login.php');
    exit();
}
// lấy folder và file quan trọng (priority=1) và chưa bị xóa (deleted=0)
$select_file;
$select_folder;
if ($_SESSION['assign_folder_pri'] != '') {
    $select_folder = "SELECT * FROM folder WHERE deleted='0' and username='$email' and parent = '" . $_SESSION['assign_folder_pri'] . "'";
    $select_file = "SELECT * FROM file WHERE deleted='0' and username='$email' and folder = '" . $_SESSION['assign_folder_pri'] . "'";
} else {
    $select_folder = "SELECT * FROM folder WHERE priority='1' and deleted='0' and username='$email'";
    $select_file = "SELECT * FROM file WHERE priority='1' and deleted='0' and username='$email'";
}
$exec_file = mysqli_query($connect, $select_file);
$num_file = mysqli_num_rows($exec_file);

$exec_folder = mysqli_query($connect, $select_folder);
$num_folder = mysqli_num_rows($exec_folder);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/styleAdmin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <title>Quản lý dữ liệu-Quan trọng</title>
</head>

<body>
    <header>
        <h>Quan trọng</h>
    </header>
    <div class="container-">
        <nav class="navbar navbar-expand-lg" id="navbar1">
            <div class="container-fluid">
                <img src="./CSS/images/logo.jpg" height="50px" width="50px" style="border-radius: 50px;">
                <a class="navbar-brand" href="index.php" style="padding-left: 50px;color: rgb(66, 72, 116);">Trang Chủ</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <form action="index.php?search=1" method="POST" class="d-flex" role="search" style="width: 60%; padding-left:10%;">
                        <input class="form-control me-2" name="search" type="search" placeholder="Tìm kiếm" aria-label="Search" id="charSearch">
                        <button class="btn btn-outline-success" name="submit" value="submit-search" type="submit">Tìm</button>
                    </form>
                    <ul>
                        <!-- Hiển thị thông tin user -->
                        <li class="nav-item dropdown" id="login">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php
                                if ($name != "") {
                                    echo $name;
                                } else {
                                    echo "User";
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu" id="dropdownLogin">
                                <li><a class="dropdown-item" href="./editInfor.php">Hồ sơ của tôi</a></li>
                                <li><a class="dropdown-item" href="./changePassword.php">Đổi mật khẩu</a></li>

                                <li><a class="dropdown-item" href="index.php?dangxuat=1">Đăng xuất</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="row">
        <section>
            <nav id="navbar2">
                <div class="dropdown">
                    <img src="./CSS/images/folder3.png" width="15%" height="15%">
                    <button class="btn btn-secondary dropdown-toggle" id="dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thư mục của tôi
                    </button>
                    <ul class="dropdown-menu" id="dropdownUL">
                        <li><a class="dropdown-item" href="index.php" onclick="changePath('')">Thư mục gốc</a></li>
                    </ul>
                </div>


                <!-- edit folder -->
                <div>
                    <div class="popup" id="popupEditFolder">
                        <form style=" background: linear-gradient(135deg, #71b7e6, #9b59b6); border-radius:10px; padding:20px">
                            <h style=" color: black; font-size: 25px; font-family: 'Times New Roman', Times, serif; margin: 25%">
                                Đổi tên thư mục</h>
                            <input class="form-control" type="text" id="idEditFolderName">
                            <p id="error" style="text-align:center;color:red"></p>
                            <div class="formAdd" style="display: flex;">
                                <button type="button" id="btnAddFile" onclick="cfEditFolderName()"> Đổi </button>
                                <button type="button" id="btnCancel" onclick="cancelEditFolder()"> Hủy </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- edit file -->
                <div>
                    <div class="popup" id="popupEditFile">
                        <form style=" background: linear-gradient(135deg, #71b7e6, #9b59b6); border-radius:10px; padding:20px">
                            <h style=" color: black; font-size: 25px; font-family: 'Times New Roman', Times, serif; margin: 25%">
                                Đổi tên tập tin</h>
                            <input class="form-control" type="text" id="idEditFileName">
                            <p id="error" style="text-align:center;color:red"></p>
                            <div class="formAdd" style="display: flex;">
                                <button type="button" id="btnAddFile" onclick="cfEditFileName()"> Đổi </button>
                                <button type="button" id="btnCancel" onclick="cancelEditFile()"> Hủy </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="priority">
                    <img src="./CSS/images/priority5.png" width="15%" height="15%">
                    <a class="btn" id="btnPriority" href="priority.php">Quan trọng</a>
                    <!-- <button type="button" class="btn btn-light" id = "btnShare">Đã chia sẻ</button> -->
                </div>
                <div class="dropdown">
                    <img src="./CSS/images/share7.png" width="15%" height="15%">
                    <button class="btn btn-secondary dropdown-toggle" id="dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Chia sẻ
                    </button>
                    <ul class="dropdown-menu" id="dropdownUL">
                        <li><a class="dropdown-item" href="share.php">Đã chia sẻ</a></li>
                        <li><a class="dropdown-item" href="share_with_me.php">Chia sẻ với tôi</a></li>

                    </ul>
                </div>
                <div class="recent">
                    <img src="./CSS/images/recent1.png" width="15%" height="15%">
                    <a class="btn" id="btnRecent" href="recent.php">Tập tin gần đây</a>
                    <!-- <button type="button" class="btn btn-light" id = "btnShare">Đã chia sẻ</button> -->
                </div>
                <div class="trash">
                    <img src="./CSS/images/trash1.png" width="15%" height="15%">
                    <a class="btn" id="btnTrash" href="trash.php">Thùng rác</a>
                    <!-- <button type="button" class="btn btn-light" id = "btnShare">Đã chia sẻ</button> -->
                </div>
                <div class="share">
                    <img src="./CSS/images/priority2.png" width="15%" height="15%">
                    <a class="btn" id="btnTrash" href="upgrade.php">Dung lượng</a>
                    <div>
                        <?php
                        // Tính phần trăm dung lượng đã xài
                        $now_us = ($is_use / $max) * 100;
                        ?>
                        <progress id="file" value="<?php echo $now_us ?>" max="100"></progress>
                    </div>
                </div>
            </nav>

            <div style="display: none;">Here</div>
            <article id="art2" style="height:100%">
                <div class="row" id="display_file">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" onclick="changePath('')">Quan trọng</a></li>
                            <!-- other folders -->
                            <?php
                            $variable = $_SESSION['path_pri'];
                            foreach ($variable as $key) {
                                if ($key != '') {
                            ?>
                                    <li class="breadcrumb-item"><a href="#" onclick="changePath('<?= $key ?>')"><?= $key ?></a></li>
                            <?php
                                }
                            }
                            ?>
                            <li class="breadcrumb-item"><a href="#"></a></li>
                        </ol>
                    </nav>
                    <?php
                    // Hiển thị folder 
                    $folder_data = array();
                    if ($num_folder != 0) {
                        while ($row = mysqli_fetch_array($exec_folder)) {
                            array_push($folder_data, $row)
                    ?>
                            <div class="col-lg-3 col-md-3">
                                <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px;">
                                    <img src="./CSS/images/folder.webp" class="card-img-top" width="256px" height="256px">
                                    <div class="card-body">
                                        <p class="card-text" id="folder_name">
                                            <?php echo $row['name'] ?>
                                        </p>
                                        <div class="dropdown" id="dropdownThuMuc" style=" background-color: rgb(247, 251, 252);color: rgb(0, 74, 124);font-family: 'Times New Roman', Times, serif;">
                                            <button onclick="getCurFolder('<?= $row['name'] ?>', '<?= $row['id'] ?>')" id="dropDownOfFile" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="./CSS/images/3dot.png" width="15%" height="15%">
                                            </button>
                                            <ul class="dropdown-menu">

                                                <li><a class="dropdown-item" href="#" onclick="changePath('<?php echo $row['name'] ?>')">Xem chi tiết </a>
                                                </li>
                                                <li><a class="dropdown-item" href="set_starred.php?folder_huy=<?php echo $row['id'] ?>">Loại bỏ quan trọng</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deletedFolder('<?= $row['id'] ?>')">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <?php
                    // Hiển thị file
                    $file_data = array();
                    if ($num_file == 0 && $num_folder == 0) {
                        echo "<h2 style=\"text-align:center\">Chưa có dữ liệu lưu trữ</h2>";
                    } else {
                        while ($row = mysqli_fetch_array($exec_file)) {
                            array_push($file_data, $row);
                    ?>
                            <div class="col-lg-3 col-md-3">
                                <div class="card" style="width: 85%; background-color: rgb(247, 251, 252);border: 0px;">
                                    <img src="./<?php echo $row['image'] ?>" class="card-img-top" width="256px" height="256px">
                                    <div class="card-body">
                                        <p class="card-text" id="file_name">
                                            <?php
                                            if (strlen($row['file_name']) > 20) {
                                                echo substr($row['file_name'], 0, 19) . '...';
                                            } else {
                                                echo $row['file_name'];
                                            }
                                            ?>
                                        </p>
                                        <div class="dropdown" id="dropdownThuMuc" style=" background-color: rgb(247, 251, 252);color: rgb(0, 74, 124);font-family: 'Times New Roman', Times, serif;">
                                            <button onclick="getCurFile('<?= $row['file_name'] ?>', '<?= $row['id'] ?>')" id="dropDownOfFile" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="./CSS/images/3dot.png" width="15%" height="15%">
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item cursor-pointer" id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="getDetail(
                                                <?php echo $row['id'] ?>)">Xem chi tiết</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="openShare(<?php echo $row['id'] ?>)">Chia sẻ</a></li>
                                                <li><a class="dropdown-item" href="set_starred.php?huy=<?php echo $row['id'] ?>">Loại bỏ quan trọng</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="deletedFile(<?php echo $row['id'] ?>)">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
                <div class="shareFile">

                    <div class="popup" id="share">
                        <form style=" background: linear-gradient(135deg, #71b7e6, #9b59b6); border-radius:10px; padding:20px">
                            <h style=" color: black; font-size: 25px; font-family: 'Times New Roman', Times, serif; margin-left: 35%;">
                                Chia sẻ </h>
                            <input class="form-control" type="text" id="users">
                            <input class="form-control" type="hidden" id="id_file">
                            <p id="error" style="text-align:center;color:red"></p>
                            <div class="form" style="display: flex;">
                                <button type="button" id="btnAddFile" onclick="shareFile()"> Thêm </button>
                                <button type="button" id="btnCancel" onclick="closeShare()"> Hủy </button>
                            </div>
                        </form>
                    </div>
                </div>

            </article>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Chi tiết tập tin</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <p><b>File Name</b>:<span class="ms-2" id="name_detail"></span></p>
                    <p><b>Type</b>:<span class="ms-2" id="type_detail"></span></p>
                    <p><b>Size</b>:<span class="ms-2" id="size_detail"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>Footer</p>
    </footer>
    <script>
        var popup = document.getElementById("popup");
        var popupFolder = document.getElementById("popupFolder");
        // var popupEditFolder = document.getElementById("popupEditFolder");
        // var popupEditFile = document.getElementById("popupEditFile");

        var temp = {
            id: -1,
            curFile: 'file',
            curFolder: 'folder',
            isFile: false,
            isImage: true
        }

        function openPopup() {
            popup.classList.add("open-popup");
        }

        function openShare(id) {
            document.getElementById("share").classList.add("open-popup");
            document.getElementById("id_file").value = id;
        }

        function closePopup() {
            popup.classList.remove("open-popup");
        }

        function closeShare() {
            document.getElementById("share").classList.remove("open-popup");
        }

        function deletedFile(id) {
            var del = confirm("Bạn có chắc chắn xóa file này không? File sẽ được chuyển vào thùng rác và tự động xóa sau 30 ngày.");
            var form_data = new FormData();
            form_data.append("id", id);
            if (del == true) {
                console.log(id);
                $.ajax({
                    url: "deleted.php",
                    type: "POST",
                    dataType: 'script',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(dat2) {
                        alert(dat2);
                        location.reload();
                    }
                });
            } else {

            }
            return del;
        }

        function deletedFolder(id) {
            var del = confirm("Bạn có chắc chắn xóa thư mục này không? Thư mục sẽ được chuyển vào thùng rác và tự động xóa sau 30 ngày.");
            var form_data = new FormData();
            form_data.append("id", id);
            form_data.append("del_folder_to_trash", 'ok');
            if (del == true) {
                console.log(id);
                $.ajax({
                    url: "deleted.php",
                    type: "POST",
                    dataType: 'script',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(dat2) {
                        alert(dat2);
                        location.reload();
                    }
                });
            } else {

            }
            return del;
        }

        function shareFile() {
            users = document.getElementById("users").value;
            id_file = document.getElementById("id_file").value;
            var is_all = 0; //false
            if (users === '') {
                is_all = 1;
            }
            var user_arr = [];
            if (users !== '') {
                user_arr = users.split(",");
            }

            user_share = JSON.stringify(user_arr);
            console.log(user_share);
            var form_data = new FormData();
            form_data.append("id_file", id_file);
            form_data.append("users", user_share);
            form_data.append("isAll", is_all);
            $.ajax({
                url: "shareFile.php",
                type: "POST",
                dataType: 'script',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(dat2) {
                    alert(dat2);
                    location.reload();
                }
            });
        }

        function openPopupFolder() {
            popupFolder.classList.add("open-popup");
        }

        function closePopupFolder() {
            popupFolder.classList.remove("open-popup");
        }

        function changePath(cur) {
            console.log(cur)
            $.ajax({
                url: "folder_service.php",
                type: "POST",
                dataType: 'json',
                data: {
                    change_path_pri: cur
                },
                success: function(dataResponse) {
                    console.log("change path ok")
                },
                error: function(dataResponse) {
                    console.log("change path khok")
                }
            });
            location.href = 'index.php';
            location.reload()
        }

        function getCurFolder(cfo, id) {
            temp.curFolder = cfo
            temp.id = id
            temp.isFile = false
        }

        function getCurFile(cfi, id) {
            temp.curFile = cfi
            temp.id = id
            temp.isFile = true
        }

        // Hiển thị thông tin file
        function getDetail(fid) {
            var modalID = temp.id;
            $.ajax({
                url: "folder_service.php",
                type: "POST",
                data: {
                    id: modalID,
                    get_detail: 'ok'
                },
                dataType: 'json',
                success: function(data) {
                    console.log('ok')
                    $('#name_detail').text(data.data.file_name);
                    $('#type_detail').text(data.data.type);
                    $('#size_detail').text(formatNumber(data.data.size) + " B");
                },
                error: function(data) {
                    console.log('khok')
                }
            });
        }

        // format number have ',' between
        function formatNumber(p) {
            str = "";
            if(p<1000) {
                return p;
            }
            while(p>0) {
                thr = p%1000;
                s = thr+"";
                if(p >= 1000) {
                    if(thr == 0) {
                        s = "0"+"0"+"0";
                    }
                    else if(thr < 100 && thr >=10) {
                        s = "0"+s;
                    }
                    else if(thr < 10) {
                        s = "0"+"0"+s;
                    }
                }
                p = Math.floor(p/1000);
                if(str != "") {
                    str = s +","+ str;
                }
                else {
                    str = s + str;
                }
            }
            return str;
        }

        // Tìm kiếm 
        $(document).ready(function() {
            $("#charSearch").on('input', function() {
                var file_data = <?php echo json_encode($file_data) ?>;
                var folder_data = <?php echo json_encode($folder_data) ?>;
                var char = $("#charSearch").val();
                var result = file_data.filter(element => element['file_name'].includes(char));
                var resulte = folder_data.filter(element => element['name'].includes(char));
                
                var html_result = "";
                if (resulte.length > 0) {
                    var ht1 = "";
                    for (i = 0; i < resulte.length; i++) {
                        ht1 += "<div class=\"col-lg-3 col-md-3\"> <div class=\"card\" style=\"width: 85%; background-color: rgb(247, 251, 252);border: 0px;\">";
                        ht1 += "<img src=\"./CSS/images/folder.webp\" class=\"card-img-top\" width=\"256px\" height=\"256px\">";
                        ht1 += "<div class=\"card-body\">";
                        ht1 += "<p class=\"card-text\" id=\"file_name\">";
                        ht1 += resulte[i]['name'].substr(0, 19);
                        ht1 += "</p>";
                        ht1 += "<div class=\"dropdown\" id=\"dropdownThuMuc\" style=\"background-color: rgb(247, 251, 252);color: rgb(0, 74, 124);font-family: 'Times New Roman', Times, serif;\">";
                        ht1 += "<button onclick=\"getCurFile(" + "'" + resulte[i]['name'] + "','" + resulte[i]['id'] + "'" + ')"' + " id=\"dropDownOfFile\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">";
                        ht1 += "<img src=\"./CSS/images/3dot.png\" width=\"15%\" height=\"15%\">";
                        ht1 += "</button>";
                        ht1 += "<ul class=\"dropdown-menu\">";
                        ht1 += "<li><a class=\"dropdown-item\" href=\"download.php?path=" + resulte[i]['name'] + "&username=" + resulte[i]['username'] + "\"" + ">Tải về</a></li>";
                        ht1 += "<li><a class=\"dropdown-item\" href=\"#\" onclick=\"showRenameFile()\">Đổi tên thư mục</a></li>";
                        ht1 += "<li><a class=\"dropdown-item\" href=\"#\">Xem chi tiết </a></li>";
                        ht1 += "<li><a class=\"dropdown-item\" href=\"#\" onclick=\"openShare(" + resulte[i]['id'] + ")\"" + ">Chia sẻ</a></li>";
                        ht1 += "<li><a class=\"dropdown-item\" href=\"set_starred.php?id=" + resulte[i]['id'] + "\">Thêm vào quan trọng</a></li>";
                        ht1 += "<li><a class=\"dropdown-item\" href=\"#\" onclick=\"deletedFile(" + resulte[i]['id'] + ")" + "\">Xóa</a></li>";
                        ht1 += "</ul>";
                        ht1 += "</div>";
                        ht1 += "</div>";
                        ht1 += "</div>";
                        ht1 += "</div>";
                    }
                    html_result += ht1;
                }

                if (result.length > 0) {
                    ht2 = "";
                    for (i = 0; i < result.length; i++) {
                        ht2 += "<div class=\"col-lg-3 col-md-3\"> <div class=\"card\" style=\"width: 85%; background-color: rgb(247, 251, 252);border: 0px;\">";
                        ht2 += "<img src=\"./" + result[i]['image'] + '"' + " class=\"card-img-top\" width=\"256px\" height=\"256px\">";
                        ht2 += "<div class=\"card-body\">";
                        ht2 += "<p class=\"card-text\" id=\"file_name\">";
                        ht2 += result[i]['file_name'].substr(0, 19);
                        ht2 += "</p>";
                        ht2 += "<div class=\"dropdown\" id=\"dropdownThuMuc\" style=\"background-color: rgb(247, 251, 252);color: rgb(0, 74, 124);font-family: 'Times New Roman', Times, serif;\">";
                        ht2 += "<button onclick=\"getCurFile(" + "'" + result[i]['file_name'] + "','" + result[i]['id'] + "'" + ')"' + " id=\"dropDownOfFile\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">";
                        ht2 += "<img src=\"./CSS/images/3dot.png\" width=\"15%\" height=\"15%\">";
                        ht2 += "</button>";
                        ht2 += "<ul class=\"dropdown-menu\">";
                        ht2 += "<li><a class=\"dropdown-item\" href=\"download.php?path=" + result[i]['file_name'] + "&username=" + result[i]['username'] + "\"" + ">Tải về</a></li>";
                        ht2 += "<li><a class=\"dropdown-item\" href=\"#\" onclick=\"showRenameFile()\">Đổi tên tập tin</a></li>";
                        ht2 += "<li><a class=\"dropdown-item\" href=\"#\">Xem chi tiết </a></li>";
                        ht2 += "<li><a class=\"dropdown-item\" href=\"#\" onclick=\"openShare(" + result[i]['id'] + ")\"" + ">Chia sẻ</a></li>";
                        ht2 += "<li><a class=\"dropdown-item\" href=\"set_starred.php?id=" + result[i]['id'] + "\">Thêm vào quan trọng</a></li>";
                        ht2 += "<li><a class=\"dropdown-item\" href=\"#\" onclick=\"deletedFile(" + result[i]['id'] + ")" + "\">Xóa</a></li>";
                        ht2 += "</ul>";
                        ht2 += "</div>";
                        ht2 += "</div>";
                        ht2 += "</div>";
                        ht2 += "</div>";
                    }
                    html_result += ht2;
                }
                console.log(html_result);
                document.getElementById("display_file").innerHTML = html_result;
            })
        });
    </script>
</body>


</html>