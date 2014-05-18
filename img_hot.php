<?php
$query_string=$_SERVER['QUERY_STRING'];
$chk=0;
$type='';
if(preg_match("/\.jpg$/i",$query_string,$match)){$chk=1;$type='jpg';}
if(preg_match("/\.png$/i",$query_string,$match)){$chk=1;$type='png';}
if(preg_match("/\.gif$/i",$query_string,$match)){$chk=1;$type='gif';}
//
$yesno = is_file($query_string);//確認檔案存在
if(!$yesno){die('x');}
$contents = file_get_contents($query_string);//取得檔案內容
if(!$contents){die('x');}
$FFF = mime_content_type($query_string);//確認文件類型
Header("Content-type:".$FFF);//指定文件類型
echo $contents;
?>