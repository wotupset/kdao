<?php
$query_string=$_SERVER['QUERY_STRING'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
//
if(!ignore_user_abort()){ignore_user_abort(true);}
if(!$query_string){die("null");}
$url=$query_string;
$url2=substr($url,0,strrpos($url,"/")+1); //根目錄
$tmp_str=strlen($url2)-strlen($url);
$url3=substr($url,$tmp_str);//圖檔檔名
//echo $url3;
//exit;
//$content = file_get_contents($url,null,null,0,2*1024*1000) or die("[error]file_get_contents");//取得來源內容
$dir_mth="./_".date("ym",$time)."/"; //存放該月檔案
if(!is_dir($dir_mth)){
	mkdir($dir_mth, 0777); //建立資料夾 權限0777
}
$dir_mth_src=$dir_mth."src/";
if(!is_dir($dir_mth_src)){
	mkdir($dir_mth_src, 0777); //建立資料夾 權限0777
}
$src=$dir_mth_src.$url3;
echo copy($url,$src) or die("[error]copy");
//$chk=file_put_contents($src,$content) or die("[error]file_put_contents");//放置來源內容;
//header("refresh:0; url=$src");
//connection_aborted()

?>