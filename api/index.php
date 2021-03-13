<?php
$servername = "localhost";
$username = "youao";
$password = "123654";
$dbname = "atest";

$mod = $_GET['mod'];
$act = $_GET['act'];

include $mod . '/' . $act . '.php';


function dbconn()
{
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    return $conn;
}