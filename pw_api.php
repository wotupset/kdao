<?php
if(!ini_get('allow_url_fopen')){die('[x]allow_url_fopen');} //無法使用網址抓取
include('./simple_html_dom.php');//v1.5
$query_string=$_SERVER['QUERY_STRING'];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$input_a=$_POST['input_a'];
$mode=$_POST['mode'];
//
if(0){
$url="http://p.pw/API/write/get?url=http://disp.cc/b/terievv&type=xml";
$html = file_get_contents($url);
$obj = new SimpleXMLElement($html);

//echo $obj->success;
//print_r($obj->success);
//var_dump($obj->success);
}

//
$htmlbody='';$res='';
switch($mode){
	case 'reg':
		$url="http://p.pw/API/write/get?user=179766&type=xml&url=".$input_a;
		$html=file_get_contents($url);
		$obj = new SimpleXMLElement($html);
		if($obj->success == "1"){
			$yn = '成功';
			$res = $obj->data->url;
		}else{
			$yn = '失敗';
			$res = $obj->error->msg;
		}
		//$htmlbody.=$url."<br/>";
		$htmlbody.=$input_a."<br/>";
		$htmlbody.=$yn."<br/>";
		$htmlbody.=$res;
	break;
	default:
	break;
}
//
echo htmlhead();
echo form();
echo $htmlbody;
echo htmlend();


//////////
function htmlhead(){
$ymdhis=$GLOBALS['ymdhis'];
$phpself=$GLOBALS['phpself'];
$url_i=pathinfo($phpself);
$url_i_b=$url_i["filename"];
$url=$GLOBALS['url'];
//下午 04:38 2014/10/23
$x=<<<EOT
<!DOCTYPE HTML>
<html lang="zh">
<head>
<title>$url_i_b</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<meta name="Robots" content="index,follow">
<STYLE></STYLE>
<SCRIPT></SCRIPT>
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
function form(){
$phpself=$GLOBALS['phpself'];
$url=$GLOBALS['url'];
$x=<<<EOT
<form enctype="multipart/form-data" action='$phpself' method="post">
<input type="hidden" name="mode" value="reg">
縮網址<input type="text" name="input_a" id="input_a" size="20" value="">
<input type="submit" value=" send ">
</form>
<a href="./">目</a>
<a href="./$phpself">返</a>
<hr>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();

?>