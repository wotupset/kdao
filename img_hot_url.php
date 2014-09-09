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
$input_a=$_POST['input_a'];
if($query_string){$url=$query_string;}else{$url=$input_a;}
////
$pic_html = '';
if($url){
	$url_p=parse_url($url);
	$url_i=pathinfo($url_p['path']);
	///////////
	if( function_exists('curl_version')){
		//
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
		$content = curl_exec($ch);
		$getinfo = curl_getinfo($ch);//文件狀態
		curl_close($ch);
		//
		//print_r($getinfo);exit;
		//echo $getinfo['http_code'];exit;
		if( ! $getinfo['http_code']  ){die('HTTP_CODE 不存在');}//狀態錯誤就停止
		if( $getinfo['http_code'] >= 400 ){die('HTTP_CODE 失敗');}//狀態錯誤就停止
	}else{
		$content = file_get_contents($url);
		$getinfo = '不是使用curl';
		//echo $info_strlen=strlen($content);exit;
	}
	///////////
	$info_strlen=strlen($content);
	//echo $info_strlen;exit;
	$filesave_tmp=$dir_mth.md5($content);
	$yn = file_put_contents($filesave_tmp,$content);
	//$yn=copy($url,$filesave_tmp);
	if($yn === false){die('[]複製來源檔案失敗');}
	//本地檔案大小
	$info_filesize=filesize($filesave_tmp);
	if($info_filesize == 0){die('資料=0');}
	$imginfo=getimagesize($filesave_tmp);//取得圖片資訊 //非圖片傳回空白值
	switch($imginfo[2]){
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
			die('非圖片');
		break;
	}
	//
	//$finfo = finfo_open(FILEINFO_MIME); // return mime type ala mimetype extension
	//$finfo_e = finfo_file($finfo, $filesave_tmp) . "\n";
	//finfo_close($finfo);
	$finfo = new finfo();
	$finfo_e = $finfo->file($filesave_tmp,FILEINFO_MIME);
	//輸出資訊
	$pic_html .= '#<pre>'.print_r($url_p,true).'</pre>';
	$pic_html .= '#<pre>'.print_r($url_i,true).'</pre>';
	$pic_html .= '#<pre>'.print_r($getinfo,true).'</pre>';
	$pic_html .= '#<pre>'.print_r($imginfo,true).'</pre>';
	$pic_html .= '#<pre>'.print_r($finfo_e,true).'</pre>';
	//計算大小
	$FFF='';$FFF_in=$info_filesize;
	if($FFF_in >1024){$FFF_in=$FFF_in/1024;$FFF='kb';} //byte -> kb
	if($FFF_in >1024){$FFF_in=$FFF_in/1024;$FFF='mb';} //byte -> kb
	if($FFF_in >1024){$FFF_in=$FFF_in/1024;$FFF='gb';} //byte -> kb
	$FFF_in=number_format($FFF_in,2);
	$FFF_in=$FFF_in.$FFF;
	$pic_html .= '#<pre>'.$FFF_in.'</pre>';
	//
	$filesave_new=$filesave_tmp.'.'.$ext;
	if(file_exists($filesave_new)){unlink($filesave_new);}
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