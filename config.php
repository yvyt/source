<?php
function connect(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "database_final";
    // Create connection
    $conn = new mysqli($servername, $username, $password,$dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
function getPrefix(){
    return "localhost";
    // return "localhost/source";
}
?>