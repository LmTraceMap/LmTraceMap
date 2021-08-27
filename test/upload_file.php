<?php
 
    date_default_timezone_set('Asia/Taipei');
    $today = date('YmdHis');
    $rand = rand(0,9);
    $type = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);

    if($type == 'fa' || $type =='fasta'|| $type =='fna')
    {
    		$path = "./file/$today$rand/";         #上傳前段的路徑
      		if ($_FILES['upload_file']['error'] != "1")
    		{
            		if (file_exists($path) == true)   # 檢查檔案是否已經存在
            		{
	    		} 
            		else                                                        # 將檔案移至指定位置
			{
                		mkdir($path);
                		chmod($path,0777);
        			$rename  =  $today;
                		$file = $_FILES['upload_file']['tmp_name'];
                		$dest = $path.$rename.".fa";
				move_uploaded_file($file, $dest);
				$text = "".$today.",".$rand."";
				echo $text;
	    		}
		}
	}
    else 
    {
	    print_r("why?");
    }

?>
