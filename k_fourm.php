<?php
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$ym=date("ym",$time);
$dir_mth="./_".$ym."/src/"; //存放該月檔案
$query_string=$_SERVER['QUERY_STRING'];
$phplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."";
$phphost=$_SERVER["SERVER_NAME"];
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//**********
if(!is_dir($dir_mth)){die('[x]');}//找不到資料夾就終止

if(!is_writeable(realpath($dir_mth))){die("目錄沒有寫入權限"); }
$url=$dir_mth;
//echo $url;
$handle=opendir($url); 
$cc = 0;
$FFF_arr=array();
while(($file = readdir($handle))!==false) { 
	$chk=0;
	if(preg_match("/\.jpg$/i",$file)){$chk=1;}
	if(preg_match("/\.png$/i",$file)){$chk=1;}
	if(preg_match("/\.gif$/i",$file)){$chk=1;}
	if($chk==1){
		$FFF_arr[0][$cc]=$file;
		$FFF_arr[1][$cc]=filectime($url.$file);
	}
	$cc = $cc + 1;
	//echo $file;
} 
closedir($handle); 
//rsort($FFF_arr);
array_multisort(
$FFF_arr[1], SORT_DESC,SORT_NUMERIC,
$FFF_arr[0]
);
//print_r($FFF_arr);
$ct=count($FFF_arr[0]);//攔截到的項目
//echo $ct;
//**********
//檢查是否支援 allow_url_fopen
echo $allow_url_fopen = ini_get('allow_url_fopen');

$ct2=ceil($ct/10);
echo "<a href='./'>目</a>"."\n";
echo "<a href='./".$phpself."'>返</a>"."\n";
echo "<a href='./".$phpself."?a'>01</a>"."\n";
echo "<pre>";
$cc=0;
foreach($FFF_arr[0] as $k => $v ){
	if($cc>100){break;}
	$FFF=$phpdir."k_fourm2.php?".$ym."!".$ct2;//相簿位置
	switch($query_string){
		case 'a': //html
			if($cc == 0){
				echo "&lt;a href='".$FFF."'&gt;".$phphost."&lt;/a&gt; &lt;br/&gt;";
				echo "\n";
			}
			echo $cc;
			echo "&lt;img src='".$phpdir.$dir_mth.$v."'&gt; &lt;br/&gt;";
		break;
		default: //預設
			if($cc == 0){
				echo "[url=".$FFF."]".$phphost."[/url]";
				echo "\n";
			}
			echo $cc;
			echo "[img]".$phpdir.$dir_mth.$v."[/img]";
		break;
	}
	echo "\n";
	$cc=$cc+1;
}
echo "</pre>";
echo "<br/>\n";
?>