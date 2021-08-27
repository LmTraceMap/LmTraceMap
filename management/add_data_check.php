<?php
header("Content-Type: text/html; charset=utf8");
include('connectdb.php'); //連結資料庫

session_start();
if ($_SESSION['password'] == null) {
    echo "<script>alert('尚未登入！');</script>";
    header("refresh:0;url=signin.html");
}
$add_input_run = $_GET['add_input_run']; //要刪掉的Run row
$_SESSION['add_input_run'] = $add_input_run;

if ($add_input_run == null) {
    echo "<script>alert('你沒有填資料！');</script>";
    header("refresh:0;url=updatedb.html");
} else {
    $sql = "SELECT * FROM `SraBioSampleInfo` WHERE `Run`='$add_input_run'";
    //$sql = "SELECT * FROM `test` WHERE `名稱`='$add_input_run'";
    $result = mysqli_query($link, $sql) or die('fail'); //執行sql
    $row_num = mysqli_num_rows($result); //返回資料數(0或1)

    if ($row_num == 1) {
        echo "<script>alert('資料庫已經有該Run的資料！');</script>";
        header("refresh:0;url=updatedb.html");
    } else {
        header("refresh:0;url=add_data.php");
    }
}
