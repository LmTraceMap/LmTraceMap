<!DOCTYPE html>
<!-- saved from url=(0046)http://120.126.17.217/hua_map/test/upload.html -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


    <title>李斯特菌追蹤系統-technical details</title>
    <link href="./upload_files/font-awesome.min.css" rel="stylesheet">
    <link href="./upload_files/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="./upload_files/jquery.min.js.下載"></script>
    <script type="module" src="script.js"></script>
    <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script>
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet'>
    </link>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
    <!--<link rel="stylesheet" type="text/css" href="../css/msdropdown/dd.css" />	
    <script src="../js/msdropdown/jquery.dd.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/msdropdown/flags.css" />
    -->
    <style>
        .word label {
            cursor: pointer;
        }

        a:hover {
            text-decoration: underline;
            color: black;
        }

        a {
            text-decoration: none;
            color: black;
        }

        p {
            font-family: Arial;
            font-weight: bold;
        }

        #upload_background {
            position: relative;
            background-color: white;
            width: 100%;
            height: 57%;
            overflow-y: scroll;
            /*text-align:center;*/
            top: 8%;
            display: block;
            /*overflow: auto;*/
        }

        #input_name_p {
            position: absolute;
            color: black;
            font-family: Arial;
            font-weight: bold;
            font-size: 200%;
            bottom: 5%;
            width: 100%;
            text-align: center;
        }

        #help {
            position: absolute;
            color: black;
            font-family: Arial;
            font-weight: bold;
            bottom: 5%;
            float: right;
            right: 1%;
        }

        #sample {
            position: absolute;
            color: black;
            font-family: Arial;
            font-weight: bold;
            text-align: center;
            bottom: 5%;
            float: right;
            right: 14%;
        }

        #auto_upload {
            position: absolute;
            color: black;
            font-family: Arial;
            font-weight: bold;
            text-align: center;
            bottom: 5%;
            float: right;
            right: 22%;
        }
        
        #technical {
            position: absolute;
            color: black;
            font-family: Arial;
            font-weight: bold;
            text-align: center;
            bottom: 5%;
            float: right;
            right: 4.5%;
        }

        #upload_button {
            position: relative;
        }

        #choose_word {
            position: relative;
            display: inline-block;
            font-family: Arial;
            font-weight: bold;
            font-size: 13pt;
        }

        .upload_file_background_title {
            position: relative;
            text-align: center;
            width: 100%;
        }

        .upload_file_background_title1 {
            margin-bottom: 6px;
            margin-left: 1px;
            margin-right: 1px;
            position: relative;
            display: inline-block;
            height: 50px;
            width: 28%;
            background-color: #aaa789;
        }

        .upload_file_word {
            color: white;
            font-size: 100%;
            font-family: Arial;
            font-weight: bold;
        }

        #upload_file_background_data {
            position: relative;
            text-align: center;
        }

        .upload_file_background_data1 {
            margin-bottom: 10px;
            margin-left: 1px;
            margin-right: 1px;
            position: relative;
            display: inline-block;
            height: 100px;
            width: 28%;
            background-color: #d5d2b8;
            font-weight: bold;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .drop_area {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .drop_area.hover {
            background-color: blue;
        }

        #country_p {
            position: relative;
            font-family: Arial;
            font-weight: bold;
            font-size: 13pt;
        }

        div .progressbar {
            position: absolute;
            border-radius: 8px;
            overflow: hidden;
            height: 20px;
            width: 100%;
        }

        div .progressbar>span.progress {
            position: absolute;
            height: 100%;
            box-sizing: border-box;
            background-color: #007bff;
        }

        #countries option {
            font-size: 10pt;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        /*滾動條裡面小方塊樣式*/
        ::-webkit-scrollbar-thumb {
            border-radius: 100px;
            background: rgb(175, 175, 175);
        }

    </style>
</head>

<body>
    <div style="position: relative; height: 15%; width: 100%;background-color: #f1f1dd;">
        <div id="input_name_p"><a href="./upload.php" style="text-decoration:none;">
                <div>LmTraceMap</div>
                <div style="font-size: 15pt;">(L.monocytogenes global tracking web tool)</div>
	    </a></div>
	<div id="auto_upload"><a href="./auto_upload.php">auto upload</div>
        <div id="sample"><a href="./sample.html">Input/Output<br>Sample</a></div>
        <div id="help"><a href="./help.html">Help</a></div>
        <div id="technical"><a href="./technical_details.php">technical details</a></div>
    </div>
    <div id="upload_background">
        <div class="drop_area" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
            <p>After the user uploads, the computer will run the following instruction:</p>
            <ol>
                <li>php run_perl.php FileName</li>
                <li>perl ./3.wgMLST-Profiler.pl -i FileName -d ./2.PGAdb_Lm_cgMLST_2 -s ./2.PGAdb_Lm_cgMLST_2/scheme/core.scheme -o ../file/FileName/output -p 12</li>
                <li>makeblastdb -in File_Path/AssemblyDB.fa -dbtype nucl -out File_Path/AssemblyDB</li>
                <li>blastn -num_threads 12 -query File_Path/panDB_all.fa -db File_Path/AssemblyDB -out File_Path/profiling.bls -outfmt '6 qseqid sseqid pident length mismatch gapopen qstart qend sstart send evalue bitscore'</li>
                <li> python3 ./rank50germ.py FileName</li>
            </ol>
            <p>crontab instruction:</p>
            <ol>
              <li>*/3 * * * * php schedule.php</li>
              <li>* * */1 * * php auto_dele.php</li>
            </ol>
        </div>
    </div>
    <footer
        style="position:absolute;bottom: 0%; width: 100%; height: 20%; background-color: #c5c09a; overflow-y: scroll;">
        <div style="position:relative; top:5%">
            <div style="position:absolute; display: block-inline; left:15px; width: 30%;">
               <p>traffic:
		   <img style="width:15%;"
		   src="https://hitwebcounter.com/counter/counter.php?page=7844372&style=0007&nbdigits=5&type=page&initCount=0" title="Free Counter" Alt="web counter"   border="0" /></a>                                     
                </p>
            </div>
            <div style="position:absolute; display: block-inline; z-index: 100; right:15px;">
                <p><a href="contact.html">Contact</a></p>
            </div>
            <div style="position:relative; text-align:center;display: block-inline; font-size: 10pt; height:100%">
                <div style="font-size:8pt;">*suggest using chrome, safari, firefox browser</div>
                <div style="font-weight: bold;">Copyright © 2021<br>Department of Public Health, China Medical University, Taiwan.<br>
                    Department and Graduate Institute of Computer Science and Information Engineering, Taiwan.</div>
                <img src="./img/school/CMU_logo.png" style="height:40px;margin-left: 5px; margin-right: 5px;">
                <img src="./img/school/CGU_logo.webp" style="height:40px;margin-left: 5px; margin-right: 5px;">
            </div>
        </div>
    </footer>
	
	<?php
  	 if(isset($_POST['call_button'])){
	 echo "1111"; 
	$this->auto();
	 }	
				
	function auto(){
		echo "console.log(1111)";
		echo shell_exec("python auto_upload.py");
	}
	?>	

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-8NQD2ZJ32X"></script>
	<script>
		 window.dataLayer = window.dataLayer || [];
		 function gtag(){dataLayer.push(arguments);}
		 gtag('js', new Date());

		 gtag('config', 'G-8NQD2ZJ32X');


        function cancel() {
            window.location.reload();
            console.log("1");
	}
	
	$("#call_button").click(function(){
		const {exec} = require(['child_process']).exec('python auto_upload.py',function(){
		alert("0000");
		})
		
	
		//var a = "<?php echo shell_exec("python auto_upload.py"); ?>";
		//$.ajax({
		//	url:"auto_upload.php",
		//	context:document.body}).success(function(){
		//		alert("do");
		//	});
		//var childProcess = require(['child_process']);

    		//var ls = childProcess.exec('php auto_upload.php', function (error, stdout, stderr) {
        	//if (error) {
            	//console.log(error.stack);
            	//console.log('Error code: '+error.code);
        	//}
        	//console.log('Child Process STDOUT: '+ stdout);
        	//callBack(stdout);
    //});
	})
	

        var save_file;

        $(document).ready(function () {
            $("#upload_file").change(function () {
                var file;
                var form_data = new FormData();
                var up_progress = document.getElementById('upload_file_background_data3');
                var upload_name = document.getElementById('upload_file_background_data1');
                var upload_size = document.getElementById('upload_file_background_data2');
                form_data.append("upload_file", $("#upload_file")[0].files[0]);
                $.ajax
                    ({
                        url: './upload_file.php',
                        type: 'POST',
                        data: form_data,
                        cache: false,
                        contentType: false,
                        processData: false,

                        xhr: function () {
                            var i = 0;
                            var myXhr = $.ajaxSettings.xhr();
                            upload_name.innerHTML = $("#upload_file")[0].files[0].name;
                            upload_size.innerHTML = $("#upload_file")[0].files[0].size + " KB";
                            if (myXhr.upload) {
                                myXhr.upload.addEventListener('progress', function (evt) {
                                    console.log("progress 事件觸發");
                                    var progress_el = document.querySelector("span.progress");
                                    if (evt.lengthComputable) {
                                        var loaded = (evt.loaded / evt.total);
                                        if (loaded <= 1) {
                                            //console.log(evt.loaded / evt.total)
                                            var percent = loaded * 100;
                                            console.log("進度：" + percent + "%");
                                            //up_progress.innerHTML = "進度：" + percent + "%";
                                            // 改變介面進度條的百分比
                                            progress_el.setAttribute("style", "width: " + percent + "%;");
                                        }
                                    }
                                }, false);
                            }
                            return myXhr;
                        },
                        success: function (data) {
                            var data_arr = data.split(',');
                            test = data_arr[0] + data_arr[1];
                            console.log(test); //存檔案名稱
                            save_file = test;
                            alert("上傳成功!");
                        },
                        error: function () {
                            alert("上傳失敗!");
                        },
                    });

            })
        });

        var filename;

        function dropHandler(evt) {
            evt.preventDefault();
            var files = evt.dataTransfer.files;//由DataTransfer物件的files屬性取得檔案物件
            var fd = new FormData();

            var xhr = new XMLHttpRequest();

            var up_progress = document.getElementById('upload_file_background_data3');
            var upload_name = document.getElementById('upload_file_background_data1');
            var upload_size = document.getElementById('upload_file_background_data2');

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                    text_arr = xhr.responseText.split(',');
                    test = text_arr[0] + text_arr[1];
                    console.log(test);
                    save_file = test;
                }
            };

            xhr.open("POST", "./upload_file.php", true);
            xhr.onload = function (fd) {
                if (save_file != "undefined") {
                    alert("上傳成功");
                    console.log(xhr.status);
                    filename = files[0].name;
                }
                else {
                    alert("上傳格式不符合,請重新上傳");
                    window.location.reload();
                }
            };

            xhr.upload.onprogress = function (evt) {
                upload_name.innerHTML = files[0].name;
                upload_size.innerHTML = files[0].size + " KB";
                var progress_el = document.querySelector("span.progress");
                if (evt.lengthComputable) {
                    var loaded = (evt.loaded / evt.total);
                    if (loaded <= 1) {
                        var percent = loaded * 100;
                        console.log("進度：" + percent + "%");
                        progress_el.setAttribute("style", "width: " + percent + "%;");
                    }
                }

            }

            fd.append("upload_file", evt.dataTransfer.files[0]);
            xhr.send(fd);//開始上傳
            console.log(fd.get('upload_file'));
        }

        function dragOverHandler(ev) {
            console.log('File(s) in drop zone');
            ev.preventDefault();
        }

        $('form').submit(function (e) {
            //country = $("#countries").val();
            country = $("#countries :selected").text();
            console.log(country);
            mail = $("#mail").val();

            if (country == '請選擇國家' || save_file == undefined) {
                alert("請填妥資料");
            }
            else
                location.href = "loading.php?filename=" + save_file + "&country=" + country + "&mail=" + mail
        })

    </script>



</body>

</html>
