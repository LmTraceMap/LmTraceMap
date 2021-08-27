<?php
header("Content-Type: text/html; charset=utf8");
include("connectdb.php");

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
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>updatedb</title>
</head>

<body>
    <div>
        <div style="display: inline-block; margin: 1em; vertical-align : top ">
            <p>輸入想要新增的國家的經緯度</p>
            <form action='add_nation_last.php' method='get'>
                <p>
                <p><?php echo $COLUMN[0]; ?><br><input type='text' value='<?php echo $add_nation; ?>' readonly="readonly" name='add_nation_<?php echo $COLUMN[0]; ?>'></p>
                <?php
                for ($i = 1; $i < $column_num; $i++) {
                    echo "<p>" . $COLUMN[$i] . "<br><input type='text' style='width:170px;' name='add_nation_" . str_replace(' ', '', $COLUMN[$i]) . "'></p>";
                }
                ?>
                <input type='submit' value='確定'>
                </p>
            </form>
        </div>
    </div>


</body>

</html>