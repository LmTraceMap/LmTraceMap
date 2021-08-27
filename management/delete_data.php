<?php

header("Content-Type: text/html; charset=utf8");
include('connectdb.php'); //連結資料庫

session_start();
if($_SESSION['password']==null){
    
    echo "<script>alert('尚未登入！');</script>";
    header("refresh:0;url=signin.html");
}
$delete_input=$_GET['delete_input']; //要刪掉的Run row

if ($delete_input == null) {
    echo "<script>alert('你沒有填資料！');</script>";
    header("refresh:0;url=updatedb.html");

} else {

    $sql = "SELECT * FROM `SraBioSampleInfo` WHERE `Run`='$delete_input'";
    //$sql = "SELECT * FROM `test` WHERE `名稱`='$delete_input'";
    $result = mysqli_query($link, $sql)or die('fail'); //執行sql
    $row_num = mysqli_num_rows($result); //返回資料數(0或1)

    if($row_num==0)
    {
        echo "<script>alert('資料庫中沒有該筆資料！');</script>";
        header("refresh:0;url=updatedb.html");
    }
    else
    {
        $sql = "DELETE FROM `SraBioSampleInfo` WHERE `Run`='$delete_input'";
        //$sql = "DELETE FROM `test` WHERE `名稱`='$delete_input'";
        $result = mysqli_query($link, $sql)or die('fail'); //執行sql
        echo "<script>alert('刪除成功！');</script>";
        header("refresh:0;url=updatedb.html");
    }
}
