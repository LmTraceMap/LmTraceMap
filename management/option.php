<?php
session_start();
if ($_SESSION['password'] == null) {
    echo "<script>alert('尚未登入！');</script>";
    header("refresh:0;url=signin.html");
}
?>


<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>choose</title>
</head>

<body>

    <div>
        <div style="display: inline-block; margin: 1em; vertical-align : top ">
            請選擇要更新的東西！
            <p>
            <p>allele_profile_20683.txt<input type='button' value='選擇' onclick="jump('allele')"></p>
            <p>SraBioSampleInfo_sel<input type='button' value='選擇' onclick="jump('Sra')"></p>
            <p>nation_located<input type='button' value='選擇' onclick="jump('nation')"></p>
            </p>
        </div>
    </div>

    <script>
        function jump(i) {
            if (i == "allele") {
                location.href = 'update_allele.html';
            } else if (i == "Sra") {
                location.href = 'updatedb.html';
            }else if (i == "nation") {
                location.href = 'nation.html';
            }
        }
    </script>

</body>

</html>