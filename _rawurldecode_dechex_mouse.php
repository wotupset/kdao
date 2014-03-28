<?php
header('Content-type: text/html; charset=utf-8');
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
echo htmlhead();
//html開始
echo "<a href='./$phpself'>$phpself</a><br/>\n";
//!empty($input_a)
if($input_a){echo auto_link(rawurldecode($input_a));echo "<br/>\n";}
if($input_b){echo hexdec($input_b);echo "<br/>\n";}

echo <<<EOT
<form enctype="multipart/form-data" action='$phpself' method="post">
<input type="text" value="??" id="in" /><br/>
被編碼過的網址<input type="text" name="input_a" id="input_a" size="20" value=""><br/>
被編碼過的時間<input type="text" name="input_b" id="input_b" size="20" value=""><br/>
<input type="submit" value=" send ">
</form>
<input id="whichkey" value="type something">
<div id="log"></div>
<script language="Javascript">
var xx="";
var yy="";
var dda="",ddb="",ddc="";
$(document).mousemove(function(event) {
	if(event.pageX!=xx || event.pageY!=yy){
		ddc=ddb;
		ddb=dda;
		dda=event.pageX+","+event.pageY;
		document.getElementById("rain").innerHTML =dda+"<br>"+ddb+"<br>"+ddc;
	}
	xx=event.pageX;
	yy=event.pageY;
});
</script>
<script>
$(document).on( "keydown", function( event ) {
	if(event.which == 17) {
		$( "#log" ).html( event.type + ": " + "ctrl"  );
	}else{
		$( "#log" ).html( event.type + ": " + event.which);
	}
});
</script>
<h2 id="rain">0 , 0</h2>
EOT;

echo htmlend();
/*
echo $echo=urlencode(${org});
echo "\n";
echo "\n";

echo $echo=rawurlencode($org);
echo "\n";
echo "\n";

echo $echo=urldecode($_);
echo "\n";
echo "\n";
*/

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

function auto_link($string){
	$string = preg_replace("/(^|[^=\]])(http|https)(:\/\/[\!-;\=\?-\~]+)/si", "\\1<a href=\"\\2\\3\" target='_blank'>\\2\\3</a>", $string);
	return $string;
}
?>