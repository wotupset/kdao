<?php
error_reporting(E_ALL & ~E_NOTICE); //
date_default_timezone_set("Asia/Taipei");//
$time=time();
$ym=date("ym",$time);
$dir_mth="./_".$ym."/"; //
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
//
$query_string=$_SERVER['QUERY_STRING'];
$url=$_POST['url'];
$pass=$_POST['pass'];
//if($query_string){$url=$query_string;}else{$url=$input_a;}
////
$body_html='';
echo $url;
echo $pass;
if(!is_writeable(realpath("./"))){echo "根目錄無法寫入";}
if($pass == 'qqq'){
	if($url){
		$url_p=parse_url($url);
		$url_i=pathinfo($url_p['path']);
/*
    [dirname] => /a
    [basename] => 1409599459171.jpg
    [extension] => jpg
    [filename] => 1409599459171
*/
		//print_r($url_i);exit;
		//
		$FFF='./'.$time.'.'.$url_i['extension'];
		$FFF=copy($url,$FFF);
		if($FFF === false ){
			$body_html='x';
		}else{
			$body_html='ok';
		}
	}else{}
}
////
$echo=<<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>cosmic</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
<form enctype="multipart/form-data" action='$phpself' method="post">
<input type="text" name="url" size="20" placeholder="url">
<input type="text" name="pass" size="20" value="$pass">
<input type="submit" value=" send "><br/>
</form>
<a href="./">目</a>
<a href="./$phpself">返</a>
<br/>
$body_html
</body>
</html>
EOT;
echo $echo;
exit;

?>