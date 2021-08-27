<?php
function callpy($argv){
	#$jsondata=exec('/usr/bin/perl /var/www/html/hua_map/test/table/3.wgMLST-Profiler.pl -i ../file/'.$argv[1].'/ -d /var/www/html/hua_map/test/table/2.PGAdb_Lm_cgMLST_2 -s /var/www/html/hua_map/test/table/2.PGAdb_Lm_cgMLST_2/scheme/core.scheme -o ../file/'.$argv[1].'/output -p 12',$output,$retun_var);
	$jsondata=exec('cd /var/www/html/hua_map/test/table; /usr/bin/perl ./3.wgMLST-Profiler.pl -i ../file/'.$argv[1].'/ -d ./2.PGAdb_Lm_cgMLST_2 -s ./2.PGAdb_Lm_cgMLST_2/scheme/core.scheme -o ../file/'.$argv[1].'/output -p 12',$output,$retun_var);
	#$jsondata=exec("cat /var/www/html/2.PGAdb_Lm_cgMLST_2/locusfiles/*",$output,$retun_var);
	#$jsondata=exec("ls /var/www/html/2.PGAdb_Lm_cgMLST_2/locusfiles/",$output,$retun_var);
	#$jsondata=exec("/usr/bin/python3 map.py",$output,$retun_var);
	
	print($retun_var);
	#$jsondata=system("/home/hohehohe/文件/ncbi-blast-2.11.0+/bin/blastn -num_threads 12 -query ./3.wgProfiles/panDB_all.fa -db ./3.wgProfiles/AssemblyDB -out ./3.wgProfiles/profiling.bls -outfmt '6 qseqid sseqid pident length mismatch gapopen qstart qend sstart send evalue bitscore'",$output,$retun_var);

	if($retun_var ==0)		
	{
        exec('rm -r /var/www/html/hua_map/test/file/'.$argv[1].'/doing.txt');
        exec('chmod 777 /var/www/html/hua_map/test/file/'.$argv[1].'/mail.txt');
        $fp = fopen("/var/www/html/hua_map/test/file/".$argv[1]."/mail.txt","r");
        $mail=fgets($fp);
        $country=fgets($fp);
        exec("echo  '稍後請到以下網址觀看結果:\nhttp://120.126.17.192/hua_map/test/table/map_pickable.php?filename=".$argv[1]."&country=".$country."'| mail -s 'LmTraceMap' $mail");
        fclose($fp);
       # $done = fopen(' /var/www/html/hua_map/upload/file/'.$argv[1].'/done.txt',"w");
       # fclose($done);
	}
	return $jsondata;
}

print_r($argv);

$jsondata=callpy($argv);

?>

