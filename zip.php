<?php
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$input_a1=$_GET['a1'];
$input_a2=$_GET['a2'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$dir_mth="./_".date("ym",$time)."/"; //存放該月檔案
$date_ymd=date("ymd",$time); //存放該月檔案
//$query_string=$_SERVER['QUERY_STRING'];
//整理輸入的資料
$FFF_arr=explode(",",$input_a2);
$no=$input_a1;
//exit;
if(!class_exists("ZipArchive")){die("[x]ZipArchive");}//若不支援ZipArchive 就停止
if(!is_writeable(realpath("./"))){die("根目錄無法寫入");}//沒權限 就停止
if($input_a1=="zip"){
	$file_name=base64_decode($input_a2);
	if(is_file($file_name)){}else{//確認檔案是否存在
		die('the file is no found');
	}
	$file_size=filesize($file_name);//檔案大小
	header("Content-Length:".$file_size);
	$tmp="Content-Disposition: attachment; filename=".$file_name."";
	header($tmp);
	header('Content-Transfer-Encoding: Binary'); //
	$file_type = mime_content_type($file_name);
	header('Content-type:'.$file_type); //強制下載 = octet-stream
	readfile($file_name);
	exit;
}
///////////////////
ob_start();
//**********
$zip = new ZipArchive; //首先实例化这个类
$zip_fn="d_".$no."_".$time.".zip";
$res = $zip->open($zip_fn, ZipArchive::CREATE);
if($res === FALSE){die("res錯誤");}//res若錯誤 就停止
foreach($FFF_arr as $k => $v){
	$s_file=$dir_mth.'src/'.$v;
	$t_file=$date_ymd.'/'.$v;
	if(is_file($s_file)){//來源檔案
		$zip->addFile($s_file,$t_file);// or die('[x]z'.$k)
		echo $zip->getStatusString();
		echo "<br/>\n";
	}else{
		echo $s_file."來源檔案不存在 略過";
		echo "<br/>\n";
	}
}
$zip->close(); //关闭
$zip_fn_enc=base64_encode($zip_fn);
echo 'ok<a href="'.$phpself.'?a1=zip&a2='.$zip_fn_enc.'">zip打包</a>';//zip打包
clearstatcache();
$filesize_=floor(filesize($zip_fn)/1024);//壓縮檔大小 轉成KB
echo $filesize_."KB";//壓縮檔大小
echo "<br/>\n";
/*
$res = $zip->open($zip_fn);
$zip->extractTo('./');
$zip->close(); //关闭
*/
////刪除舊的zip
//**********遍歷資料夾
$url="./";
$handle=opendir($url); 
$cc = 0;
while(($file = readdir($handle))!==false) { 
	if($file=="."||$file == ".."){continue;}//跳過檢查這兩個
	if(!is_file($file)){continue;}//非檔案跳過
	$fn=$file;
	$fn_a=substr($fn,0,strrpos($fn,".")); //主檔名
	$fn_b=strrpos($fn,".")+1-strlen($fn);
	$fn_b=substr($fn,$fn_b); //副檔名
	if($fn_b=="zip"){
		if(preg_match("/[0-9]{10}$/u",$fn_a,$match)){
			//echo "<pre>".$match[0]."</pre>";
			//echo $file;
			echo "<br/>\n";
			if($time - $match[0] > 3600){//刪除過期的檔案//3600=1hr
				$FFF=unlink($file);
				if($FFF){echo $file."刪除";}else{echo $file."刪除失敗";}
			}
		}
	}//忽略上傳的php檔案(安全考量)
	$cc = $cc + 1;
} 
closedir($handle); 
//**********
////
//**********
$htmlbody=ob_get_clean();
header('Content-type: text/html; charset=utf-8');
echo htmlhead();
echo $htmlbody;
echo htmlend();

function htmlhead(){
$x=<<<EOT
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css"><!--
body { font-family:"細明體",'MingLiU'; }
--></STYLE>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
EOT;
$x="\n".$x."\n";
return $x;
}

function htmlend(){
$x=<<<EOT
</body></html>
EOT;
$x="\n".$x."\n";
return $x;
}

?>