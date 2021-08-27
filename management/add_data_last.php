<?php
header("Content-Type: text/html; charset=utf8");
include('connectdb.php'); //連結資料庫

session_start();
if ($_SESSION['password'] == null) {
    echo "<script>alert('尚未登入！');</script>";
    header("refresh:0;url=signin.html");
}
$add_input_run = $_SESSION['add_input_run'];


$sql_get_column = "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='SraBioSampleInfo'";  //得到所有column的名稱
//$sql_get_column = "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='test'";  //得到所有column的名稱
$column = mysqli_query($link, $sql_get_column) or die("fail sent(get column name)");
$column_num = mysqli_num_rows($column); //有幾個column

$COLUMN = array();    //存欄位名稱
for ($i = 0; $i < $column_num; $i++) {
    $temp = mysqli_fetch_array($column);
    #array_push($COLUMN, $temp['column_name']);    //把query出來的欄位名稱存成一個陣列
    array_push($COLUMN, $temp[0]);
}

$add_input = array();
for ($i = 0; $i < $column_num; $i++) {
    if($COLUMN[$i]=='geographic_location')
    {
        $nation_temp = $_GET["add_input_" . str_replace(' ', '', $COLUMN[$i])."_nation"];
        $state_temp = $_GET["add_input_" . str_replace(' ', '', $COLUMN[$i])."_state"];

        $add_input[$i] = $nation_temp.":".$state_temp;
    }
    else
    {
        $add_input[$i] = $_GET["add_input_" . str_replace(' ', '', $COLUMN[$i])];
    }
}

$sql = "INSERT INTO `SraBioSampleInfo` (`Run`,`BioSample`,`strain`,`collection_date`,`geographic_location`,`host`,`isolation_source`,`collected_by`,`serovar`,`isolate name alias`,`isolate`,`IFSAC+_Category`,`source_type`,`PublicAccession`,`attribute_package`,`ProjectAccession`,`Genus`,`ontological_term`,`Species`,`sample_name`,`INSDC_center_alias`,`INSDC_center_name`,`INSDC_first_public`,`INSDC_last_update`,`INSDC_status`,`SRA_accession`,`Title`,`ENA_checklist`,`Alias`) VALUES ('$add_input[0]','$add_input[1]','$add_input[2]','$add_input[3]','$add_input[4]','$add_input[5]','$add_input[6]','$add_input[7]','$add_input[8]','$add_input[9]','$add_input[10]','$add_input[11]','$add_input[12]','$add_input[13]','$add_input[14]','$add_input[15]','$add_input[16]','$add_input[17]','$add_input[18]','$add_input[19]','$add_input[20]','$add_input[21]','$add_input[22]','$add_input[23]','$add_input[24]','$add_input[25]','$add_input[26]','$add_input[27]','$add_input[28]')";
//$sql = "INSERT INTO `test` (`名稱`,`國家`) VALUES ('$add_input[0]','$add_input[1]')";
$result = mysqli_query($link, $sql) or die('fail'); //執行sql

echo "<script>alert('新增成功！');</script>";
header("refresh:0;url=updatedb.html");
