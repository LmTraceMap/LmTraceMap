<?php
header("Content-Type: text/html; charset=utf8");
include('connectdb.php'); //連結資料庫

session_start();
if ($_SESSION['password'] == null) {
    echo "<script>alert('尚未登入！');</script>";
    header("refresh:0;url=signin.html");
}
$edit_input_run = $_SESSION['edit_input_run'];


$sql_get_column = "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='SraBioSampleInfo'";  //得到所有column的名稱
$column = mysqli_query($link, $sql_get_column) or die("fail sent(get column name)");
$column_num = mysqli_num_rows($column); //有幾個column

$COLUMN = array();    //存欄位名稱
for ($i = 0; $i < $column_num; $i++) {
    $temp = mysqli_fetch_array($column);
    #array_push($COLUMN, $temp['column_name']);    //把query出來的欄位名稱存成一個陣列
    array_push($COLUMN, $temp[0]);
}

$edit_input = array();
for ($i = 0; $i < $column_num; $i++) {
    if($COLUMN[$i]=='geographic_location')
    {
        $nation_temp = $_GET["edit_input_" . str_replace(' ', '', $COLUMN[$i])."_nation"];
        $state_temp = $_GET["edit_input_" . str_replace(' ', '', $COLUMN[$i])."_state"];

        $edit_input[$i] = $nation_temp.":".$state_temp;
    }
    else
    {
        $edit_input[$i] = $_GET["edit_input_" . str_replace(' ', '', $COLUMN[$i])];
    }
}

$sql = "UPDATE `SraBioSampleInfo` SET `Run`='$edit_input[0]', `BioSample`='$edit_input[1]',`strain`='$edit_input[2]',`collection_date`='$edit_input[3]',`geographic_location`='$edit_input[4]',`host`='$edit_input[5]',`isolation_source`='$edit_input[6]',`collected_by`='$edit_input[7]',`serovar`='$edit_input[8]',`isolate name alias`='$edit_input[9]',`isolate`='$edit_input[10]',`IFSAC+_Category`='$edit_input[11]',`source_type`='$edit_input[12]',`PublicAccession`='$edit_input[13]',`attribute_package`='$edit_input[14]',`ProjectAccession`='$edit_input[15]',`Genus`='$edit_input[16]',`ontological_term`='$edit_input[17]',`Species`='$edit_input[18]',`sample_name`='$edit_input[19]',`INSDC_center_alias`='$edit_input[20]',`INSDC_center_name`='$edit_input[21]',`INSDC_first_public`='$edit_input[22]',`INSDC_last_update`='$edit_input[23]',`INSDC_status`='$edit_input[24]',`SRA_accession`='$edit_input[25]',`Title`='$edit_input[26]',`ENA_checklist`='$edit_input[27]',`Alias`='$edit_input[28]' WHERE `Run`='$edit_input_run'";
$result = mysqli_query($link, $sql) or die('fail'); //執行sql

echo "<script>alert('編輯成功！');</script>";
header("refresh:0;url=updatedb.html");
