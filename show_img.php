<?php
error_reporting(E_ALL & ~E_NOTICE); //
date_default_timezone_set("Asia/Taipei");//
$time=time();
$ym=date("ym",$time);
$dir_mth="./_".$ym."/"; //
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//
$php_dir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$php_dir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
//
$query_string=$_SERVER['QUERY_STRING'];
$ym=$_GET['ym'];
$ym='./_'.$ym.'/src/';
$show_img=$_GET['show_img'];
$show_img=explode(",",$show_img);
//
$htmlbody='';
foreach($show_img as $k => $v){
	//$htmlbody.=$ym.$show_img[$k];
	$htmlbody.='<img src="'.$ym.$show_img[$k].'"/>';
	//$htmlbody.='<br/>'."\n";
	$htmlbody.='<hr/>'."\n";
}

//
echo htmlhead();
echo $htmlbody;
echo htmlend();
//
function htmlhead(){
$x=<<<EOT
<html><head>
<title>fella</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css">
body { }
img {
height:auto; width:auto;
min-width:20px; min-height:20px;
max-width:250px; max-height:250px;
border:1px solid blue;
}
</STYLE>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
EOT;
$x="\n".$x."\n";
return $x;
}
//echo htmlhead();

function htmlend(){
$x=<<<EOT
</body></html>
EOT;
$x="\n".$x."\n";
return $x;
}

?>