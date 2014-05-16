<?php
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();

////
//
$htmlbody='';
$htmlbody.= <<<EOT
<form enctype="multipart/form-data" action='$phpself' method="post">
<textarea name="input_a" cols="48" rows="4" wrap="soft"></textarea>
<input type="submit" value=" send ">
</form>
EOT;

if($input_a){
	$input_a = preg_match_all("/\[img\].*\[\/img\]/u",$input_a,$match);
	$htmlbody.=$input_a;
	$htmlbody.="<br/>\n";
	//print_r($match);
	foreach($match[0] as $k => $v){
		$htmlbody.=$k;
		$htmlbody.="<br/>\n";
		$htmlbody.=$v;
		$htmlbody.="<br/>\n";
	}
}
echo htmlhead();
echo $htmlbody;
echo htmlend();
//**************
function htmlhead(){
$title=_def_DATE;
$x=<<<EOT
<html><head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<meta name="Robots" content="noindex,follow">
<STYLE TYPE="text/css"><!--
body2 { font-family:"細明體",'MingLiU'; }
--></STYLE>
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
//echo htmlend();
?>