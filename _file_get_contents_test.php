<?php
ini_set('allow_url_fopen',1);
if(!ini_get('allow_url_fopen')){die('[x]allow_url_fopen');} //無法使用網址抓取
ini_set('max_execution_time',5);//五秒
@set_time_limit(5);//五秒
//header('Content-Type: application/javascript; charset=utf-8');
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//header('Content-type: text/html; charset=utf-8');
//$query_string=$_SERVER['QUERY_STRING'];
//date_default_timezone_set("Asia/Taipei");//時區設定
//$url="http://i.imgur.com/b04tXD6.jpg";
$url="http://i.imgur.com/XegMmvO.png";
$opts = array('http'=>array('method'=>"GET",'timeout'=>10));
$context = stream_context_create($opts);
$max_size=5*1024*1024;//抓取上限
$cc=0;
while(1){
	$content = file_get_contents($url,NULL,$context,0,$max_size);
	if($content === TRUE){break;}
	if($cc>3){break;}
	$cc=$cc+1;
}
if(strlen($content) == 0){die('空');}
if($content === FALSE){//取得來源內容 // or die("[error]file_get_contents")
	Header("Content-type: image/png");//指定文件類型為PNG
	$moji=date("ymd",$time);
	$moji=sprintf("%06d",$moji);
	$img = imageCreate(90,15);
	$wd_color =imageColorAllocate($img, 255, 0, 0);//紅色
	$bg_color = imageColorAllocate($img, 255, 0, 0);
	imageFill($img, 0, 0, $bg_color);
	imagestring($img,5,0,0, $moji, $wd_color);
	imagePng($img);
	imageDestroy($img);
}else{
	Header("Content-type: image/jpg");//指定文件類型
	echo $content;
}
if(!strlen($content)){die("[error] 1 file_get_contents");}
$src='./'.$phpself.'.jpg';
$chk=file_put_contents($src,$content) or die("[error]file_put_contents");//放置來源內容;
unlink($src);
?>