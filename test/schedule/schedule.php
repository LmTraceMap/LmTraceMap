#!/usr/local/bin/php
<?php
    exec('ls /var/www/html/hua_map/test/file',$file_output); //進去存使用者.fa檔案的第一層資料夾(file) [file_ouput存目前有幾個file]

    for($i=0;$i<sizeof($file_output);$i++)
    {
        if(file_exists('/var/www/html/hua_map/test/file/'.$file_output[$i].'/done.txt')==1)  //done.txt 做完
        {
            print_r(''.$file_output[$i].' is done <br>');
        }
        else if(file_exists('/var/www/html/hua_map/test/file/'.$file_output[$i].'/doing.txt')==1) //doing.txt 正在做
        {
            print_r(''.$file_output[$i].' is doing <br>'); //做完後會將doing.txt刪除
            exit();
        }
       else
        {
            exec("touch /var/www/html/hua_map/test/file/".$file_output[$i]."/doing.txt");
            print_r(''.$file_output[$i].' do now!');
            shell_exec('/usr/bin/php /var/www/html/hua_map/test/table/run_perl.php '.$file_output[$i].'');  //呼叫轉換表格php
	    #shell_exec('cd ../file/'.$file_output[$i].'  &&  /usr/bin/sudo php ../table/map_pickable.php '.$file_output[$i].'');  //呼叫轉換表格php
	    exit();
            
        }
    }
?>
    
