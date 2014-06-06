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
	if($chk==1){$FFF_arr[]=$file;}
	$cc = $cc + 1;
	//echo $file;
} 
closedir($handle); 
rsort($FFF_arr);
//print_r($FFF_arr);
$ct=count($FFF_arr);//攔截到的項目
//echo $ct;
//**********
//檢查是否支援url get 
$FFF=$phpdir."XPButtonUploadText_61x22.png";
$array=getimagesize($FFF);//取得圖片資訊 //非圖片傳回空白值
//print_r($array);exit;
if($query_string == ""){
	if(!$array[2]){
		$query_string = "a";
	}
}
$ct2=ceil($ct/10);
echo "<pre>";
echo "[url=".$phpdir."k_fourm2.php?".$ym."!".$ct2."]".$phphost."[/url]";
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