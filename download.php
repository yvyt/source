<?php
session_start();
if (isset($_GET['file_down'])) {
    //Read the url
    $url = 'files/' . $_GET['username'] . '/' ;
    if(count($_SESSION['path']) > 0) {
        $url = $url . join('/', $_SESSION['path']) . '/';
    }
    $url = $url . $_GET['file_down'];

    //Clear the cache
    clearstatcache();

    //Check the file path exists or not
    if (file_exists($url)) {

        //Define header information
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($url) . '"');
        header('Content-Length: ' . filesize($url));
        header('Pragma: public');

        //Clear system output buffer
        flush();

        //Read the size of the file
        readfile($url, true);

        //Terminate from the script
        die();
    } else {
        echo "File path does not exist.";
    }
}