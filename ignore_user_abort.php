<?php
//header('Content-Type: application/javascript; charset=utf-8');
//Header("Content-type: image/jpg");//指定文件類型
header('Content-type: text/html; charset=utf-8');
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
//
echo ignore_user_abort(1);
//echo set_time_limit();
echo $tmp_arr[]="<br>\n";
$tmp_arr=array();
//$tmp_arr=explode("\n",$tmp_arr);
if(ob_get_level()==0){ob_start();}
for($i=0;$i<30;$i++){
	echo $tmp_arr[]=$i."Line to show.";
	echo $tmp_arr[]="<br>\n";
	ob_flush();
	flush();
	//睡1秒
	sleep(1);
}
echo "Done.";
ob_end_flush();
//print_r($tmp_arr);
$tmp_str=implode("",$tmp_arr);
//echo $tmp_str;
$logfile="123.txt";
$cp = fopen($logfile, "a+") or die('failed');// 讀寫模式, 指標於最後, 找不到會嘗試建立檔案
ftruncate($cp, 0); //砍資料至0
fputs($cp, $tmp_str);
fclose($cp);
?>