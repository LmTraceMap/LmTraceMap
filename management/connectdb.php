<?php #連接資料庫

$server = "120.126.17.192";
#$dbuser = "debian-sys-maint";       # 使用者帳號
#$dbpassword = "fF4MLXieyN0YMIlX"; # 使用者密碼
$dbuser = "bubuuuu";
$dbpassword = "00000000";
$dbname = "L__monocytogenes_global_tracking_web_tool";    # 資料庫名稱
header("Content-Type:text/html; charset=utf-8");
$link = mysqli_connect($server, $dbuser, $dbpassword);

if(!$link)
{
        die("錯誤訊息: " . mysqli_connect_error());

}
else
{
        mysqli_query($link, "SET NAMES 'UTF8'")or die ("fail1");
        mysqli_select_db($link, $dbname) or die("fail2");
}

?>
