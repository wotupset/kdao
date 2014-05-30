<?php
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$dir_mth="./_".date("ym",$time)."/src/"; //存放該月檔案
$query_string=$_SERVER['QUERY_STRING'];
$phplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."";
$phphost=$_SERVER["SERVER_NAME"];
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
//**********
if(!is_dir($dir_mth)){die('[x]');}
if(!is_writeable(realpath($dir_mth))){die("目錄沒有寫入權限"); }
$url=$dir_mth;
$handle=opendir($url); 
$cc = 0;
$FFF_arr=array();
while(($file = readdir($handle))!==false) { 
	$chk=0;
	if(preg_match("/\.jpg$/i",$file)){$chk=1;}
	if(preg_match("/\.png$/i",$file)){$chk=1;}
	if(preg_match("/\.gif$/i",$file)){$chk=1;}
	if($chk==1){$FFF_arr[]=$file;}
	$cc = $cc + 1;
} 
closedir($handle); 
//**********
rsort($FFF_arr);
//echo "<pre>".print_r($FFF_arr,true)."</pre>";
echo "<pre>";
echo "[url=".$phpdir."fourm2.php]".$phphost."[/url]";
echo "\n";
$cc=1;
foreach($FFF_arr as $k => $v ){
	if($cc>100){break;}
	echo $cc;
	switch($query_string){
		case 'a':
			echo "[img]".$phpdir."img_hot.php?".$dir_mth.$v."[/img]";
		break;
		default:
			echo "[img]".$phpdir.$dir_mth.$v."[/img]";
		break;
	}
	echo "\n";
	$cc=$cc+1;

}
echo "</pre>";

?>