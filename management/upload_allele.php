<?php

$temp = $_FILES['upload_file']['name'];
$type = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);
echo "<script>console.log(傳過來的黨名$temp);</script>";

if ($type == 'txt') {
//if ($type !=null) {
    if ($_FILES['upload_file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['upload_file']['tmp_name'];
        $dest = "../test/table/allele_profile_20683.txt";
        //$dest = "../test/table/allele_profile_20683_update.txt";
        move_uploaded_file($file, $dest);
        echo "<script>alert('上傳成功!');</script>";
        header("refresh:0;url=update_allele.html");

    }
}
else
{
    echo "<script>alert('上傳失敗!<br>檔案格式錯誤,請選擇文字檔!');</script>";
    header("refresh:0;url=update_allele.html");
}
