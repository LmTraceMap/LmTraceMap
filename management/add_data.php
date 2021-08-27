<?php
header("Content-Type: text/html; charset=utf8");
include("connectdb.php");

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
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>updatedb</title>
</head>

<body>
    <div>
        <div style="display: inline-block; margin: 1em; vertical-align : top ">
            <p>輸入想要新增的row的資料</p>
            <form action='add_data_last.php' method='get'>
                <p>
                <p><?php echo $COLUMN[0]; ?><br><input type='text' value='<?php echo $add_input_run; ?>' readonly="readonly" name='add_input_<?php echo $COLUMN[0]; ?>'></p>
                <?php
                for ($i = 1; $i < $column_num; $i++) {

                    if ($COLUMN[$i] == 'geographic_location') {
                        echo "<p>" . $COLUMN[$i] . "<br>";
                        echo "<select style='width:170px;' name='add_input_" . str_replace(' ', '', $COLUMN[$i]) . "_nation'>";

                        $sql = "SELECT `nation` FROM `nation_located`";  //得到所有選的國家
                        $result = mysqli_query($link, $sql) or die("fail sent(get nation)");
                        $nation_num = mysqli_num_rows($result); //有幾個column

                        for($j=0; $j<$nation_num; $j++)
                        {
                            $nation=mysqli_fetch_array($result);
                            echo "<option>". $nation['nation'] ."</option>";
                        }

                        echo "</select>";
                        echo "<br><input type='text' style='width:170px;' name='add_input_" . str_replace(' ', '', $COLUMN[$i]) . "_state' placeholder='state'>";
                        echo "</p>";
                    } else {
                        echo "<p>" . $COLUMN[$i] . "<br><input type='text' style='width:170px;' name='add_input_" . str_replace(' ', '', $COLUMN[$i]) . "'></p>";
                    }
                }
                ?>
                <input type='submit' value='確定'>
                </p>
            </form>
        </div>
        <div style="display: inline-block; margin: 1em; vertical-align : top ">
            <p>輸入想要刪除的row的Run</p>
            <form action='delete_data.php' method='get'>
                <p>
                <p>Run<br><input type='text' placeholder='ex: SRR1153450' name='delete_input'></p>
                <input type='submit' value='確定'>
                </p>
            </form>
        </div>
        <div style="display: inline-block; margin: 1em; vertical-align : top ">
            <p>輸入想要編輯的row的Run</p>
            <form action='edit_data.php' method='get'>
                <p>
                <p>Run<br><input type='text' placeholder='ex: SRR1153450' name='delete_input'> </p>
                <input type='submit' value='確定'>
                </p>
            </form>
        </div>
    </div>


</body>

</html>