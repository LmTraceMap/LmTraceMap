<?php
$today = date("YmdHis",strtotime("-1 month"));
	print_r("today:".$today);
	

	$file_arr = array();
	exec('ls /var/www/html/hua_map/test/file/',$output);
	print_r($output);
	
		
	for($i=0;$i<count($output);$i++)
	{
		$file_arr[$i] = substr_replace($output[$i],'',-1);
	}

	print_r($file_arr);

	for($i=0;$i<count($output);$i++)
	{
		if($file_arr[$i]<$today)
		{
			print_r("out");
			exec('sudo rm -r /var/www/html/hua_map/test/file/'.$output[$i].'');
		}
		else
			print_r("save");
	}
?>
