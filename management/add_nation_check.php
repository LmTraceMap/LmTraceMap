<?php
header("Content-Type: text/html; charset=utf8");
include('connectdb.php'); //連結資料庫

session_start();
if ($_SESSION['password'] == null) {
    echo "<script>alert('尚未登入！');</script>";
    header("refresh:0;url=signin.html");
}
$add_nation = $_GET['add_nation'];
$_SESSION['add_nation'] = $add_nation;

if ($add_nation == null) {
    echo "<script>alert('你沒有填資料！');</script>";
    header("refresh:0;url=nation.html");
} else {
    $sql = "SELECT * FROM `nation_located` WHERE `nation`='$add_nation'";
    $result = mysqli_query($link, $sql) or die('fail'); //執行sql
    $row_num = mysqli_num_rows($result); //返回資料數(0或1)

    if ($row_num == 1) {
        echo "<script>alert('資料庫已經有該國家！');</script>";
        header("refresh:0;url=nation.html");
    } else {
        header("refresh:0;url=add_nation.php");
    }
}
