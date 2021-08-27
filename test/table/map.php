<?php
#127.0.0.1/test/hua_map/table/map_pickable.php?filename=202105261153225&country=台灣

include("connectdb.php");

$user_file_name  = $_GET['filename'];
$user_country =  $_GET['country'];
$rank = array(50);  //[菌的名字,距離]

$last_output = exec("/usr/bin/python3 ./rank50germ.py $user_file_name", $python_output, $ret); #執行python檔,會輸出前50名的細菌
if ($python_output == null)
  echo "python的output沒有進來<br>";
else {
  for ($i = 0; $i < 50; $i++) {
    $rank[$i] = explode(' ', $python_output[$i]); #用空格切割,放進$rank陣列
  }
}


#得到每個細菌所在的國家,並且得到國家的種類,算出國家的數量
for ($i = 0; $i < 50; $i++) {
  $germ_name = $rank[$i][0];
  $sql = "SELECT `geographic_location` FROM `SraBioSampleInfo` WHERE `Run`='$germ_name'"; #細菌所在的國家
  $result = mysqli_query($link, $sql) or die("fail sent");
  $germ_location = mysqli_fetch_array($result);

  array_push($rank[$i], $germ_location[0]);  #$rank[x][0]->細菌名字, $rank[x][1]距離, $rank[x][2]->該細菌的國家
}

$geo = array(); //總共有幾種國家 $geo[x][0]->地名,$geo[x][1]->該地名的數量(該地名有幾個菌)
$index_count = 0;

for ($i = 0; $i < 50; $i++) {
  $geo_count = 0;
  $state = 0;

  for ($k = 0; $k < $index_count; $k++) {
    if ($rank[$i][2] == $geo[$k][0]) {
      $state++;
      break;
    }
  }

  if ($state == 0) //如果該地名沒在$geo裡面
  {
    $geo[$index_count][0] = $rank[$i][2];

    for ($j = 0; $j < 50; $j++)  //算該地名的數量(該地名有幾個菌)
    {
      if ($geo[$index_count][0] == $rank[$j][2]) {
        $geo_count++;
      }
    }
    $geo[$index_count][1] = $geo_count;
    $index_count++;
  }
}

$sql_get_column = "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='SraBioSampleInfo'";  //得到所有column的名稱
$column = mysqli_query($link, $sql_get_column) or die("fail sent(get column name)");
$column_num = mysqli_num_rows($column); //有幾個column

$COLUMN = array();    //存欄位名稱
for ($i = 0; $i < $column_num; $i++) {
  $temp = mysqli_fetch_array($column);
  array_push($COLUMN, $temp['column_name']);    //把query出來的欄位名稱存成一個陣列
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <style>
    .leaflet-popup-content-wrapper {
      background: papayawhip;
    }

    p {
      font-family: Arial;
      font-weight: bold;
    }

    ul li {
      cursor: pointer;
    }

    ul li:hover {
      background: #DEDEDE;
    }

    #table {
      overflow: auto;
      width: 100%;
      height: 100%;
      /* 固定高度 */
    }

    #table td,
    th {
      border: 1px solid gray;
      width: 100px;
      height: 30px;
    }

    #table th {
      background-color: #DEDEDE;
    }

    #table table {
      table-layout: fixed;
      width: 200px;
      /* 固定寬度 */
    }

    #table td:first-child,
    th:first-child {
      position: sticky;
      left: 0;
      /* 首行永遠固定於左 */
      z-index: 1;
      background-color: #DEDEDE;
    }

    #table thead tr th {
      position: sticky;
      top: 0;
      /* 列首永遠固定於上 */
    }

    #table th:first-child {
      z-index: 2;
      background-color: #DEDEDE;
    }

    a:hover {
      text-decoration: underline;
      color: black;
    }

    a {
      text-decoration: none;
      color: black;
    }

    #help {
      position: absolute;
      color: black;
      font-family: Arial;
      font-weight: bold;
      top: 70px;
      float: right;
      right: 1%;
      margin-left: 1%;
      color: black;
    }

    #input_name_p {
      position: relative;
      color: black;
      top: 50px;
      font-size: 30px;
      text-align: center;
      font-family: Arial;
    }

    .border {
      border-top-left-radius: 10px;
      border-bottom-left-radius: 10px;
      cursor: pointer;
    }

    #upload_background::-webkit-scrollbar {
      display:none;
    }

    #upload_background {
      position: relative;
      background-color: white;
      width: 100%;
      height: 550px;
      overflow: scroll;
      /*text-align:center;*/
      left: 0%;
      top: 50px;
      /*overflow: auto;*/
    }
    .drop_area {
        position: relative;
        width: 100%;
        height: 700px;
    }
  </style>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <title>李斯特菌追蹤系統-結果頁面</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" />
  <!--<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/44/4/intl/zh_tw/common.js"></script>-->
  <!--<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/44/4/intl/zh_tw/util.js"></script>-->
  <!--<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/44/4/intl/zh_tw/geocoder.js"></script>-->
  <script src="leaflet.canvas-markers.js"></script>
  <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDE7ppDWw0j6o1M8Mwyz5rXDGkO_PdrNuw&libraries=geometry&callback=initMap"></script>-->

  <script src="../map.css"></script>
  <script src="../jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  <link href="https://cdn.bootcdn.net/ajax/libs/TableExport/5.2.0/img/csv.svg">


  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">

  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>


  <!--<script src='//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js'></script>-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.30.5/css/theme.blue.min.css">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.30.5/js/jquery.tablesorter.min.js"></script>

</head>

<body style="background: linear-gradient(#b5b18b,#b3af92);width:100%;position:relative;">
  <div class="drop_area">
    <p id="input_name_p"><a href="../upload.html" style="color:black;text-decoration: none;">L. monocytogenes global tracking web tool</a></p>
    <p id="help"><a href="../help.html" style="color:black;">Help</a></p>
    <div id="upload_background">
      <div style="background:white;position:relative;width:5%;height:100%;float:left;">
        <div class="border" id="change_map" onclick="ChangeToMap()" style="background:#6E6EFF;position:relative;width:90%;height:90px;color:white;margin-left:10%;text-overflow:ellipsis;word-break:break-all;font-size:120%;text-align:center;top:0.5%;padding-left:12%;padding-right:80%;">MAP</div>
        <div class="border" id="change_form" onclick="ChangeToForm()" style="background:#FF6E6E;position:relative;width:60%;height:120px;color:white;margin-left:40%;text-overflow:ellipsis;word-break:break-all;font-size:120%;text-align:center;top:0.5%;padding-left:12%;padding-right:20%;">FORM</div>
      </div>
      <div id="mapid" style="height: 100%;width:90%;position:absolute;border-style:groove;margin-left:5%;z-index:2;">

        <script type="text/javascript">
          var index_count = <?php echo $index_count; ?>;
          var delay = 10;
          var nextAddress = 0;
          var germ_geo = new Array();
          germ_geo = <?php echo json_encode($geo); ?>;

          var germ = new Array();
          germ = <?php echo json_encode($rank); ?>;

          // 建立 Leaflet 地圖
          var map = L.map('mapid');

          // 設定經緯度座標
          map.setView(new L.LatLng(20, 0), 2);

          // 設定圖資來源
          var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

          var pic = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            minZoom: 1,
            maxZoom: 39
          });

          map.addLayer(pic);

          var geocoder;

          function initMap() {
            geocoder = new google.maps.Geocoder();
          }

          function check() {
            if (nextAddress < index_count) {
              setTimeout("codeAddress('" + germ_geo[nextAddress][0] + "', '" + germ_geo[nextAddress][1] + "')", delay);
              nextAddress++;
            }
          }

          function user_location() {
            codeAddress('<?php echo $user_country; ?>', -1); //使用者的位置
            check();
          }

          function codeAddress(geo, num) {
            var address = geo;
            var lat;
            var lng;

            $.ajax({
              url: 'https://geocoder.ls.hereapi.com/6.2/geocode.json',
              type: 'GET',
              dataType: 'jsonp',
              jsonp: 'jsoncallback',
              data: {
                searchtext: address,
                gen: '9',
                apiKey: '-Vjk-8ApUTcBAyH9dyuStdgyXgTJ8MmwMpaU6idPsZg'
              },
              success: function(data) {
                var jso = JSON.stringify(data);
                jso = JSON.parse(jso).Response.View[0];
                lat = jso["Result"]["0"]["Location"]["DisplayPosition"]["Latitude"];
                lng = jso["Result"]["0"]["Location"]["DisplayPosition"]["Longitude"];
                if (num == -1) //如果是使用者自己的位置
                {
                  locating_icon = L.icon({ //safari不能跑
                    iconUrl: '../img/locating1.png',
                    iconSize: [30, 52],
                  });

                  mark = L.marker([lat, lng], {
                    icon: locating_icon,
                  }).addTo(map);

                } else {
                  mark = L.circleMarker([lat, lng], {
                    color: 'blue', // 線條顏色
                    fillColor: 'yellow', // 填充顏色
                    fillOpacity: 0.5, // 透明度
                    radius: 3 + 5 * num,
                  }).addTo(map);
                }
                popsign = "<p style='text-align: center;font-size:18px;margin:0px;background:gainsboro;border-top-left-radius:4px;border-top-right-radius:4px;'>" + geo + "</p>";

                if (num == -1) {
                  popsign += "<p style='text-align: center;'>使用者上傳的細菌的所在地</p>"
                } else {
                  count = 0;
                  for (var i = 0; i < 50; i++) {
                    if (geo == germ[i][2]) {
                      count++;
                      pop(count, i + 1, germ[i][0], germ[i][1]);
                    }
                  }
                }

                mark.bindPopup(popsign);

                check();
              }
            });

            /*geocoder.geocode({
            'address': address
          }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              lat = results[0].geometry.location.lat();
              lng = results[0].geometry.location.lng();

              if (num == -1) //如果是使用者自己的位置
              {
                locating_icon = L.icon({ //safari不能跑
                  iconUrl: '../img/locating1.png',
                  iconSize: [30, 52],
                });

                mark = L.marker([lat, lng], {
                  icon: locating_icon,
                }).addTo(map);

              } else {
                mark = L.circleMarker([lat, lng], {
                  color: 'blue', // 線條顏色
                  fillColor: 'yellow', // 填充顏色
                  fillOpacity: 0.5, // 透明度
                  radius: 3 + 5 * num,
                }).addTo(map);
              }

	      popsign = "<p style='text-align: center;font-size:18px;margin:0px;background:gainsboro;border-top-left-radius:4px;border-top-right-radius:4px;'>" + geo + "</p>";

              if (num == -1) {
                popsign += "<p style='text-align: center;'>使用者上傳的細菌的所在地</p>"
              } else {
                count = 0;
                for (var i = 0; i < 50; i++) {
                  if (geo == germ[i][2]) {
                    count++;
                    pop(count, i + 1, germ[i][0], germ[i][1]);
                  }
                }
              }

              mark.bindPopup(popsign);

            } else {
              if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                nextAddress--;
                delay++;
              } else
                alert("Geocode was not successful for the following reason: " + status);
            }
            check();
          });*/
          }

          function pop(seq, rank, germ_name, distance) {
            if (seq == 1)
              popsign = popsign + "<table style='text-align: center;width:100%;background:seashell;'><tr><td style='border: 1px solid #333;'>序號</td><td style='border: 1px solid #333;'>第幾名</td><td style='border: 1px solid #333;'>菌的名字</td><td style='border: 1px solid #333;'>幾個不一樣</td></tr>";
            popsign = popsign + "<tr><td style='border: 1px solid #333;'>" + seq + "</td><td style='border: 1px solid #333;'>" + rank + "</td><td style='border: 1px solid #333;'>" + germ_name + "</td><td style='border: 1px solid #333;'>" + distance + "</td></tr>";
          }

          function ChangeToMap() {
            var map = document.getElementById("mapid");
            var form = document.getElementById("table_c");
            var changemap = document.getElementById("change_map");
            var changeform = document.getElementById("change_form");
            changemap.style.width = "90%";
            changemap.style['margin-left'] = "10%";
            changemap.style['padding-right'] = "80%";
            changeform.style.width = "60%";
            changeform.style['margin-left'] = "40%";
            changeform.style['padding-right'] = "20";
            map.style['z-index'] = "2";
            form.style['z-index'] = "1";
          }

          function ChangeToForm() {
            var map = document.getElementById("mapid");
            var form = document.getElementById("table_c");
            var changemap = document.getElementById("change_map");
            var changeform = document.getElementById("change_form");
            changemap.style.width = "60%";
            changemap.style['margin-left'] = "40%";
            changemap.style['padding-right'] = "20%";
            changeform.style.width = "90%";
            changeform.style['margin-left'] = "10%";
            changeform.style['padding-right'] = "80%";
            map.style['z-index'] = "1";
            form.style['z-index'] = "2";
          }
        </script>
      </div>
      <div id="table_c" style="height: 100%;width:90%;position:absolute;overflow:scroll;overflow-x:hidden;border-style:groove;margin-left:5%;z-index:1;background:white; ">

        <div style="position:relative;  display: block; z-index: 100;">
          <!--下載table的按鈕-->
          <a id="exportcsv" class="btn btn-success" style="color:white; cursor:pointer;" download="前50近似的菌info.csv">下載csv檔</a>
          <a id="exportxls" class="btn btn-success" style="color:white; cursor:pointer;" download="前50近似的菌info.xls">下載xls檔</a>
          <input type="button" id="selectInput" value="請選擇欄位" class="btn btn-success" style="color:white; cursor:pointer;">
          <ul id="optionLis" style="position: absolute; margin-left:195px;width:250px;height:300px;padding-left:0px;background:white;overflow-y:scroll;border:solid 1px black;">
            <?php
            for ($i = 0; $i < $column_num; $i++) {
              echo "<li class='columnlist' style='display:block; list-style-type:none; border: solid 1px black; width:250px;height:25px;'>" . $COLUMN[$i] . "</li>";
            }
            ?>
          </ul>
        </div>
        <div id="table">
          <!--<table class="tree table table-condensed table-hover table-bordered table-responsive" id="treegridTable" style="position: absolute;top:50px;">-->
          <table class=" sort_table tree table table-condensed table-hover table-bordered" id="treegridTable">
            <thead>
              <tr id="table_column_display">
                <!--表格的欄位由js匯入-->
              </tr>
            </thead>
            <tbody id="table_info_display">
              <!--表格的內容由js匯入-->
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div style="position:absolute; display: block-inline; left:15px;">
      <p>traffic:
        <img style="width: 70px;" src="https://hitwebcounter.com/counter/counter.php?page=7844372&style=0007&nbdigits=5&type=page&initCount=0" title="Free Counter" Alt="web counter" /></a>
      </p>
    </div>
    <div style="position:absolute; display: block-inline; z-index: 100; right:15px;">
      <p><a href="contact.html">Contact</a></p>
    </div>
    <div style="position:relative; text-align:center;display: block-inline; ">
      <p>Copyright © 2021 China Medical University, Taiwan and Chang Gung University, Taiwan. All rights
        reserved.</p>
      <img src="../img/school/CMU_logo.png" style="height:50px;margin-left: 5px; margin-right: 5px;">
      <img src="../img/school/CGU_logo.webp" style="height:50px;margin-left: 5px; margin-right: 5px;">
    </div>
  </div>


  <script>
    //取得表格資料
    // 點擊匯出
    $("#exportcsv").click(function() {
      var List = [];
      var titleList = [];
      var memberContent = "";
      var Content;

      // 取得標題
      $("#treegridTable th div").each(function() {
        titleList.push(this.innerHTML);
      });
      List.push(titleList);

      // 取得所有資料
      $("#treegridTable tbody > tr").each(function() {
        var regList = [];
        $(this).children("td").each(function() {
          regList.push(this.innerHTML);
        });
        List.push(regList);
      });

      // 產生 csv 內容
      List.forEach(function(rowArray) {
        var row = rowArray.join(",");
        memberContent += row + "\r\n";
      });

      // 產生 csv Blob
      Content = URL.createObjectURL(new Blob(["\uFEFF" + memberContent], {
        type: 'text/csv;charset=utf-8;'
      }));

      // 產生 csv 連結
      this.href = Content;
    });

    // 點擊匯出
    $("#exportxls").click(function() {
      var List = [];
      var titleList = [];
      var memberContent = "";
      var Content;

      // 取得標題
      $("#treegridTable th div").each(function() {
        titleList.push(this.innerHTML);
      });
      List.push(titleList);

      // 取得所有資料
      $("#treegridTable tbody > tr").each(function() {
        var regList = [];
        $(this).children("td").each(function() {
          regList.push(this.innerHTML);
        });
        List.push(regList);
      });

      // 產生 xls 內容
      List.forEach(function(rowArray) {
        var row = rowArray.join(",");
        memberContent += row + "\r\n";
      });

      // 產生 xls Blob
      Content = URL.createObjectURL(new Blob(["\uFEFF" + memberContent], {
        type: 'application/vnd.ms-excel;charset=utf-8;'
      }));

      // 產生 xls 連結
      this.href = Content;
    });
  </script>

  <?php #把前50名的細菌資料弄到表格
  for ($i = 1; $i <= 50; $i++) {

    $temp = $rank[$i - 1][0];

    $sql = "SELECT * FROM `SraBioSampleInfo` WHERE `Run`='$temp'"; #細菌的資訊
    $info = mysqli_query($link, $sql) or die("fail sent(get germ info)");
    $germ_info = mysqli_fetch_array($info);

    $Run[$i - 1] = $germ_info['Run'];
    $BioSample[$i - 1] = $germ_info['BioSample'];
    $strain[$i - 1] = $germ_info['strain'];
    $collection_date[$i - 1] = $germ_info['collection_date'];
    $geographic_location[$i - 1] = $germ_info['geographic_location'];
    $host[$i - 1] = $germ_info['host'];
    $isolation_source[$i - 1] = $germ_info['isolation_source'];
    $collected_by[$i - 1] = $germ_info['collected_by'];
    $serovar[$i - 1] = $germ_info['serovar'];
    $isolate_name_alias[$i - 1] = $germ_info['isolate name alias'];
    $isolate[$i - 1] = $germ_info['isolate'];
    $IFSAC_Category[$i - 1] = $germ_info['IFSAC+_Category'];
    $source_type[$i - 1] = $germ_info['source_type'];
    $PublicAccession[$i - 1] = $germ_info['PublicAccession'];
    $attribute_package[$i - 1] = $germ_info['attribute_package'];
    $ProjectAccession[$i - 1] = $germ_info['ProjectAccession'];
    $Genus[$i - 1] = $germ_info['Genus'];
    $ontological_term[$i - 1] = $germ_info['ontological_term'];
    $Species[$i - 1] = $germ_info['Species'];
    $sample_name[$i - 1] = $germ_info['sample_name'];
    $INSDC_center_alias[$i - 1] = $germ_info['INSDC_center_alias'];
    $INSDC_center_name[$i - 1] = $germ_info['INSDC_center_name'];
    $INSDC_first_public[$i - 1] = $germ_info['INSDC_first_public'];
    $INSDC_last_update[$i - 1] = $germ_info['INSDC_last_update'];
    $INSDC_status[$i - 1] = $germ_info['INSDC_status'];
    $SRA_accession[$i - 1] = $germ_info['SRA_accession'];
    $Title[$i - 1] = $germ_info['Title'];
    $ENA_checklist[$i - 1] = $germ_info['ENA_checklist'];
    $Alias[$i - 1] = $germ_info['Alias'];
  }
  ?>

  <script>
    window.onload = function() {
      user_location();

      $(".sort_table").tablesorter({
        theme: "blue",
        widgets: ['zebra']
      });

      var Run = new Array();
      var BioSample = new Array();
      var strain = new Array();
      var collection_date = new Array();
      var geographic_location = new Array();
      var host = new Array();
      var isolation_source = new Array();
      var collected_by = new Array();
      var serovar = new Array();
      var isolate_name_alias = new Array();
      var isolate = new Array();
      var IFSAC_Category = new Array();
      var source_type = new Array();
      var PublicAccession = new Array();
      var attribute_package = new Array();
      var ProjectAccession = new Array();
      var Genus = new Array();
      var ontological_term = new Array();
      var Species = new Array();
      var sample_name = new Array();
      var INSDC_center_alias = new Array();
      var INSDC_center_name = new Array();
      var INSDC_first_public = new Array();
      var INSDC_last_update = new Array();
      var INSDC_status = new Array();
      var SRA_accession = new Array();
      var Title = new Array();
      var ENA_checklist = new Array();
      var Alias = new Array();
      var rank50 = new Array();

      Run = <?php echo json_encode($Run); ?>;
      BioSample = <?php echo json_encode($BioSample); ?>;
      strain = <?php echo json_encode($strain); ?>;
      collection_date = <?php echo json_encode($collection_date); ?>;
      geographic_location = <?php echo json_encode($geographic_location); ?>;
      host = <?php echo json_encode($host); ?>;
      isolation_source = <?php echo json_encode($isolation_source); ?>;
      collected_by = <?php echo json_encode($collected_by); ?>;
      serovar = <?php echo json_encode($serovar); ?>;
      isolate_name_alias = <?php echo json_encode($isolate_name_alias); ?>;
      isolate = <?php echo json_encode($isolate); ?>;
      IFSAC_Category = <?php echo json_encode($IFSAC_Category); ?>;
      source_type = <?php echo json_encode($source_type); ?>;
      PublicAccession = <?php echo json_encode($PublicAccession); ?>;
      attribute_package = <?php echo json_encode($attribute_package); ?>;
      ProjectAccession = <?php echo json_encode($ProjectAccession); ?>;
      Genus = <?php echo json_encode($Genus); ?>;
      ontological_term = <?php echo json_encode($ontological_term); ?>;
      Species = <?php echo json_encode($Species); ?>;
      sample_name = <?php echo json_encode($sample_name); ?>;
      INSDC_center_alias = <?php echo json_encode($INSDC_center_alias); ?>;
      INSDC_center_name = <?php echo json_encode($INSDC_center_name); ?>;
      INSDC_first_public = <?php echo json_encode($INSDC_first_public); ?>;
      INSDC_last_update = <?php echo json_encode($INSDC_last_update); ?>;
      INSDC_status = <?php echo json_encode($INSDC_status); ?>;
      SRA_accession = <?php echo json_encode($SRA_accession); ?>;
      itle = <?php echo json_encode($Title); ?>;
      ENA_checklist = <?php echo json_encode($ENA_checklist); ?>;
      Alias = <?php echo json_encode($Alias); ?>;
      rank50 = <?php echo json_encode($rank); ?>;


      var column_select = []; //選擇的欄位
      var temp_string;
      var selectInput = document.getElementById('selectInput');
      var optionLis = document.getElementById('optionLis');
      var table_column_display = document.getElementById('table_column_display');
      var table_info_display = document.getElementById('table_info_display');
      var optionList = optionLis.children;
      optionLis.style.display = "none";
      selectInput.onclick = function() {
        if (optionLis.style.display == "none") {
          optionLis.style.display = "block"
        } else {
          optionLis.style.display = "none"
        }
      }

      table_column_display.innerHTML = "<th style='width: 200px; font-size:12pt;'>排名</th>";
      table_column_display.innerHTML += "<th style='width: 200px; font-size:12pt;'>菌的名稱</th>";
      table_column_display.innerHTML += "<th style='width: 200px; font-size:12pt;'>距離</th>";

      for (var a = 1; a <= 50; a++) {
        if (a == 1) {
          display_string = "<tr class='treegrid-parent-2'>";
        } else {
          display_string += "<tr class='treegrid-parent-2'>";

        }
        display_string += "<td style='font-size:8pt;'>" + a + "</td>"; //排名
        display_string += "<td style='font-size:8pt;'>" + rank50[a - 1][0] + "</td>"; //名字rank[x][0]
        display_string += "<td style='font-size:8pt;'>" + rank50[a - 1][1] + "</td>"; //距離rank[x][1]
        display_string += "</tr>";
        table_info_display.innerHTML = display_string;
      }
      $(".sort_table").trigger("updateAll");



      $(".columnlist").click(function() {

        for (var i = 0; i < column_select.length; i++) {
          if (column_select[i] == this.innerHTML) {
            column_select.splice(i, 1);
            temp_string = column_select + ",";

            table_column_display.innerHTML = "<th style='width: 200px; font-size:12pt;'>排名</th>";
            table_column_display.innerHTML += "<th style='width: 200px; font-size:12pt;'>菌的名稱</th>";
            table_column_display.innerHTML += "<th style='width: 200px; font-size:12pt;'>距離</th>";

            for (var j = 0; j < column_select.length; j++) {
              table_column_display.innerHTML += "<th style='width: 200px; font-size:12pt;'>" + column_select[j] + "</th>"; //在table顯示選擇的欄位(取消的時候)
            }
            for (var a = 1; a <= 50; a++) {
              if (a == 1) {
                display_string = "<tr class='treegrid-parent-2'>";
              } else {
                display_string += "<tr class='treegrid-parent-2'>";

              }
              display_string += "<td style='font-size:8pt;'>" + a + "</td>"; //排名
              display_string += "<td style='font-size:8pt;'>" + rank50[a - 1][0] + "</td>"; //名字rank[x][0]
              display_string += "<td style='font-size:8pt;'>" + rank50[a - 1][1] + "</td>"; //距離rank[x][1]
              for (var j = 0; j < column_select.length; j++) {
                temp = table_info(column_select[j], a - 1);
                display_string += "<td style='font-size:8pt;'>" + temp + "</td>";
              }
              display_string += "</tr>";
              table_info_display.innerHTML = display_string;
            }
            $(".sort_table").trigger("updateAll");
            LiStyle();
            return;
          }
        }
        column_select.push(this.innerHTML);
        temp_string = column_select + ",";

        table_column_display.innerHTML = "<th style='width: 200px; font-size:12pt;'>排名</th>";
        table_column_display.innerHTML += "<th style='width: 200px; font-size:12pt;'>菌的名稱</th>";
        table_column_display.innerHTML += "<th style='width: 200px; font-size:12pt;'>距離</th>";

        for (var j = 0; j < column_select.length; j++) {
          table_column_display.innerHTML += "<th style='width: 200px; font-size:12pt;'>" + column_select[j] + "</th>"; //在table顯示選擇的欄位(選的時候)
        }

        for (var a = 1; a <= 50; a++) {
          if (a == 1) {
            display_string = "<tr class='treegrid-parent-2'>";
          } else {
            display_string += "<tr class='treegrid-parent-2'>";
          }
          display_string += "<td style='font-size:8pt;'>" + a + "</td>"; //排名
          display_string += "<td style='font-size:8pt;'>" + rank50[a - 1][0] + "</td>"; //名字rank[x][0]
          display_string += "<td style='font-size:8pt;'>" + rank50[a - 1][1] + "</td>"; //距離rank[x][1]

          for (var j = 0; j < column_select.length; j++) {
            temp = table_info(column_select[j], a - 1);
            display_string += "<td style='font-size:8pt;'>" + temp + "</td>";
          }
          display_string += "</tr>";
        }
        table_info_display.innerHTML = display_string;
        $(".sort_table").trigger("updateAll");
        LiStyle();

        //选中时li标签的样式变化
        function LiStyle() {
          for (var i = 0; i < optionList.length; i++) {
            optionList[i].style.background = '';
          }
          arr1 = temp_string.split(',');
          for (var i = 0; i < arr1.length; i++) {
            for (var j = 0; j < optionList.length; j++) {
              if (arr1[i] == optionList[j].innerHTML) {
                optionList[j].style.background = '#90EE90';
              }
            }
          }
        }

        function table_info(column_name, index) {
          switch (column_name) {
            case 'Run':
              return Run[index];
              break;
            case 'BioSample':
              return BioSample[index];
              break;
            case 'strain':
              return strain[index];
              break;
            case 'collection_date':
              return collection_date[index];
              break;
            case 'geographic_location':
              return geographic_location[index];
              break;
            case 'host':
              return host[index];
              break;
            case 'isolation_source':
              return isolation_source[index];
              break;
            case 'collected_by':
              return collected_by[index];
              break;
            case 'serovar':
              return serovar[index];
              break;
            case 'isolate name alias':
              return isolate_name_alias[index];
              break;
            case 'isolate':
              return isolate[index];
              break;
            case 'IFSAC+_Category':
              return IFSAC_Category[index];
              break;
            case 'source_type':
              return source_type[index];
              break;
            case 'PublicAccession':
              return PublicAccession[index];
              break;
            case 'attribute_package':
              return attribute_package[index];
              break;
            case 'ProjectAccession':
              return ProjectAccession[index];
              break;
            case 'Genus':
              return Genus[index];
              break;
            case 'ontological_term':
              return ontological_term[index];
              break;
            case 'Species':
              return Species[index];
              break;
            case 'sample_name':
              return sample_name[index];
              break;
            case 'INSDC_center_alias':
              return INSDC_center_alias[index];
              break;
            case 'INSDC_center_name':
              return INSDC_center_name[index];
              break;
            case 'INSDC_first_public':
              return INSDC_first_public[index];
              break;
            case 'INSDC_last_update':
              return INSDC_last_update[index];
              break;
            case 'INSDC_status':
              return INSDC_status[index];
              break;
            case 'SRA_accession':
              return SRA_accession[index];
              break;
            case 'Title':
              return Title[index];
              break;
            case 'ENA_checklist':
              return ENA_checklist[index];
              break;
            case 'Alias':
              return Alias[index];
              break;
          }
        }
      });
    }
  </script>

  <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDE7ppDWw0j6o1M8Mwyz5rXDGkO_PdrNuw&libraries=geometry&callback=initMap"></script>-->
</body>

</html>
