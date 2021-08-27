<html>

<head>
	<meta charset="utf-8">
	<script src="http://code.jquery.com/jquery-1.4.1.min.js"></script>
</head>
<style>
	html,
	body {
		height: 100%;
		margin: 0;
		padding: 0;
	}

	#loading {
		border: 30px solid #f3f3f3;
		/* Light grey */
		border-top: 30px solid #3498db;
		/* Blue */
		border-radius: 50%;
		width: 200px;
		height: 200px;
		animation-duration: 5000ms;
		animation-name: spin;
		animation-iteration-count: infinite;
		margin-top: 13%;
		margin-bottom: 5%;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}
</style>

<body style="background-color:#f1f1dd;text-align:center;height:100%">
	<div align="center" style="position:absolute;margin-left:50%;left:-35%;width:70%;height:600px;top:15%;background:white;border-radius:50px;">
		<?php
		header("Content-Type: text/html; charset=utf-8");
		$bool = 0;
		$savefile =  $_GET['filename'];
		$country =  urldecode($_GET['country']);
		$mail =  urldecode($_GET['mail']);
		$count = 0;
		exec("touch /var/www/html/hua_map/test/file/" . $savefile . "/mail.txt");
		$fp = fopen("./file/" . $savefile . "/mail.txt", "w");
		fwrite($fp, $mail);
		fwrite($fp, "\n");
		fwrite($fp, $country);
		fclose($fp);

		echo "<div id = 'loading'></div>";

		exec('ls ./file', $file_output);
		for ($i = 0; $i < sizeof($file_output); $i++) {
			if (file_exists('./file/' . $file_output[$i] . '/done.txt') != 1)  //done.txt 做完
			{
				$count++;
			}
		}

		$countsum = $count * 4;

		if ($count == 0) {
			echo "<p style='font-size:18px;font-weight:bold;'>Doing......</p>";
		} else {
			echo "<p style='font-size:18px;font-weight:bold;'>before your file, there have $count files queuing. <br>please wating for about $countsum mins.</p>";
		}



		if (!file_exists('./file/' . $savefile . '/done.txt')) {
			//	echo 'doing.txt';
			//	sleep(20);
			for ($i = 0; $i < 1000; $i++);
			header('refresh: 10;url="./loading.php?filename=' . $savefile . '&country=' . $country . '&mail=' . $mail . '"');
		} else {

			$bool = 1;
		}

		?>
	</div>
	<script>
		/*	
	$(window).load(function(){
        	$('#loading').hide(5000);
	});
	*/
		var bool = <?php echo $bool ?>;
		if (bool == 1) {
			$('#loading').hide(5000);
			location.href = "./table/map_pickable.php?filename=" + <?php echo $savefile ?> + "&country=<?php echo $country ?>";
		}
	</script>
</body>

</html>
