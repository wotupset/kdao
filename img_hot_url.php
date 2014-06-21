<?php
error_reporting(E_ALL & ~E_NOTICE); //
date_default_timezone_set("Asia/Taipei");//
$time=time();
$ym=date("ym",$time);
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
////

/*
if(function_exists("mime_content_type")){
	$FFF=mime_content_type($query_string);
}else{
	$chk=0;$type='';
	if(preg_match("/\.jpg$/i",$query_string)){$chk=1;$type='image/jpeg';}
	if(preg_match("/\.png$/i",$query_string)){$chk=1;$type='image/png';}
	if(preg_match("/\.gif$/i",$query_string)){$chk=1;$type='image/gif';}
	if(!$chk){die('x');}
	//
	$FFF=$type;
}
*/
//
if(0){//註解掉
Header("Content-type:".$array['mime']);//輸出到瀏覽器
echo $contents;
exit;
}
////
$pic_html = '';
$pic_html = xxx();
////
$echo=<<<EOT
<!DOCTYPE html>
<html>
<head>
<title>浮水印</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
<form enctype="multipart/form-data" action='$phpself' method="post">
<input type="text" name="input_a" size="20" placeholder="url">
<input type="submit" value=" send "><br/>
<label>重新讀圖<input type="checkbox" name="input_b" value="1" />(破圖時使用)</label>
</form>
<a href="./">目</a>
<a href="./$phpself">返</a>
<br/>
$pic_html
</body>
</html>
EOT;
echo $echo;
exit;
function xxx(){
	$query_string=$_SERVER['QUERY_STRING'];
	$input_a=$_POST['input_a'];
	$time=$GLOBALS['time'];
	//
	$input=$input_a;
	//
	if(!$input){return "x100";}
	$array=getimagesize($input);//取得圖片資訊 //非圖片傳回空白值
	//print_r($array);exit;
	/*
	    [0] => 1440
	    [1] => 810
	    [2] => 2
	    [3] => width="1440" height="810"
	    [bits] => 8
	    [channels] => 3
	    [mime] => image/jpeg
	1=gif
	2=jpg
	3=png
	*/
	if(!$array[2]){return "x200";}
	//
	$contents = file_get_contents($input);//取得圖片完整內容
	if($content === FALSE){return "x300";}
	//
	$ym=date("ym",$time);
	$dir_mth="./_".$ym."/"; //
	$date_now=date("d", $time)."v".date("His", $time);
	$ext='';
	switch($array[2]){
	case '1':
		$ext="gif";
	break;
	case '2':
		$ext="jpg";
	break;
	case '3':
		$ext="png";
	break;
	default:
		die('x400');
	break;

	}
	$pic_src=$dir_mth."_".$date_now."_".$time.".".$ext;
	$pic_html='';
	$yesno = file_put_contents($pic_src , $contents );//儲存圖片內容
	if($yesno === FALSE){return "x400";}else{
	$pic_html="<img src='".$pic_src."'/>";
	}
	//
	$x = $pic_html;
	return $x;
}
?>