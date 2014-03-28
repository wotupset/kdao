<?php
//header('Content-Type: application/javascript; charset=utf-8');
Header("Content-type: image/jpg");//指定文件類型
//header('Content-type: text/html; charset=utf-8');
//$query_string=$_SERVER['QUERY_STRING'];
//date_default_timezone_set("Asia/Taipei");//時區設定
//$url="http://i.imgur.com/b04tXD6.jpg";
$url="http://web.archive.org/web/2014/http://eden.komica.org/00/src/1395496021223.jpg";
$content = file_get_contents($url) or die("[error] 0 file_get_contents");
if(!strlen($content)){die("[error] 1 file_get_contents");}
//$content = file_get_contents($url,NULL,NULL,0,100);
echo $content;
//echo "<img src='https://web.archive.org/web/20140319155311/http://eden.komica.org/00/src/1395242681298.gif'>";

?>