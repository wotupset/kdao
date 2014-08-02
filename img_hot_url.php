<?php
error_reporting(E_ALL & ~E_NOTICE); //
date_default_timezone_set("Asia/Taipei");//
$time=time();
$ym=date("ym",$time);
$dir_mth="./_".$ym."/src/"; //
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
//
$query_string=$_SERVER['QUERY_STRING'];
$input_a=$_POST['input_a'];
if($query_string){$url=$query_string;}else{$url=$input_a;}
////
$pic_html = '';
if($url){
	$url_p=parse_url($url);
	$pic_html .= '#<pre>'.print_r($url_p,true).'</pre>';
	$url_i=pathinfo($url_p['path']);
	$pic_html .= '#<pre>'.print_r($url_i,true).'</pre>';
	//
	$filesave_tmp=$dir_mth.md5($url);
	$yn=copy($url,$filesave_tmp);
	if($yn === false){die('[]複製來源檔案失敗');}
	$array=getimagesize($filesave_tmp);//取得圖片資訊 //非圖片傳回空白值
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
			unlink($filesave_tmp);
			die('x400');
		break;
	}
	$pic_html .= '#<pre>'.print_r($array,true).'</pre>';
	//本地檔案大小
	$info_filesize=filesize($filesave_tmp);
	$FFF='';
	if($info_filesize >1024){$info_filesize=$info_filesize/1024;$FFF='kb';} //byte -> kb
	if($info_filesize >1024){$info_filesize=$info_filesize/1024;$FFF='mb';} //byte -> kb
	if($info_filesize >1024){$info_filesize=$info_filesize/1024;$FFF='gb';} //byte -> kb
	$info_filesize=number_format($info_filesize,2);
	$info_filesize=$info_filesize.$FFF;
	$pic_html .= '#<pre>'.$info_filesize.'</pre>';

	$filesave_new=$filesave_tmp.'.'.$ext;
	rename($filesave_tmp,$filesave_new);
	$pic_html .= '#'.$filesave_new.'<br/><img src="'.$filesave_new.'"/>';

}
////
$echo=<<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
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

?>