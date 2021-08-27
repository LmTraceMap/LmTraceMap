<?php
header("Content-Type: text/html; charset=utf8");
include('connectdb.php'); //連結資料庫

session_start();
if ($_SESSION['password'] == null) {
    echo "<script>alert('尚未登入！');</script>";
    header("refresh:0;url=signin.html");
}
$add_nation = $_SESSION['add_nation'];


$sql_get_column = "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='nation_located'";  //得到所有column的名稱
$column = mysqli_query($link, $sql_get_column) or die("fail sent(get column name)");
$column_num = mysqli_num_rows($column); //有幾個column

$COLUMN = array();    //存欄位名稱
for ($i = 0; $i < $column_num; $i++) {
    $temp = mysqli_fetch_array($column);
    #array_push($COLUMN, $temp['column_name']);    //把query出來的欄位名稱存成一個陣列
    array_push($COLUMN, $temp[0]);    //把query出來的欄位名稱存成一個陣列
}

$add_input = array();
for ($i = 0; $i < $column_num; $i++) {
    $add_input[$i] = $_GET["add_nation_" . str_replace(' ', '', $COLUMN[$i])];
}

$sql = "INSERT INTO `nation_located` (`nation`,`longtitude`,`latitude`) VALUES ('$add_input[0]','$add_input[1]','$add_input[2]')";
$result = mysqli_query($link, $sql) or die('fail'); //執行sql

echo "<script>alert('新增成功！');</script>";
header("refresh:0;url=nation.html");
