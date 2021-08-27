<?php
	if(file_exists("/var/www/html/hua_map/test/file/2021"))
	{
		exec("rm -rf /var/www/html/hua_map/test/file/2021");
		print_r("exists");
	}

	exec("mkdir /var/www/html/hua_map/test/file/2021");
	chmod("/var/www/html/hua_map/test/file/2021",0777);
	exec("cp /var/www/html/hua_map/test/test.fa /var/www/html/hua_map/test/file/2021/");
	header("Location: http://120.126.17.192/hua_map/test/loading.php?filename=2021&country=Taiwan&mail=sharonxie@gmail.com"); 

?>
