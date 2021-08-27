<?php
header("Content-Type: text/html; charset=utf8");

session_start();
$password=$_GET['input_password']; //使用者輸入的密碼

if($password=="12345678")
{
    $_SESSION['password'] = $password;
    echo "<script>alert('登入成功！');</script>";
    header("refresh:0;url=option.php");
}
else
{
    $_SESSION['password'] =null;
    echo "<script>alert('密碼錯誤！');</script>";
    header("refresh:0;url=signin.html");
}

