<?php 
//header('Content-Type: application/javascript; charset=utf-8');
//Header("Content-type: image/jpg");//指定文件類型
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$query_string=$_SERVER['QUERY_STRING'];
$input_a=$_POST['input_a'];
$mode=$_POST['mode'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
if($query_string){$url=$query_string;}else{$url=$input_a;}
if($url){
	if(!preg_match("%http://himado\.in/.*%U",$url)){die('reg1');}//只使用於XX網址
	$mode='reg';
}

$url=trim($url);
//echo $url;exit;
////
$htmlbody='';

function reg(){
	//http://himado.in/206235
	$url=$GLOBALS['url'];
	//<h1 id="movie_title">とある科学の超電磁砲S 映像特典 「もっとまるっと超電磁砲Ⅳ」
	$content = file_get_contents($url) or die("[error] 0 file_get_contents");
	//$content = preg_replace("/\n/","",$content);
	$content = preg_replace("/\t/","",$content);
	$pattern='%var movie_url = \'(.*)\';%U';//非貪婪匹配
	preg_match($pattern, $content, $matches_r1);//首篇的圖
	$pattern='%<h1 id="movie_title">(.*)</h1>%U';//非貪婪匹配
	preg_match($pattern, $content, $matches_r2);//首篇的圖
	//$x=print_r($matches_r1,true);
	$x='';
	$x.=$url;
	$x.="<br/>\n";
	$x.=auto_link(rawurldecode($matches_r1[1]));
	$x.="<br/>\n";
	$x.=$matches_r2[1];
	return $x;
}
switch($mode){
	case 'reg':
		$htmlbody.=reg();
	break;
	default:
	break;
}
$output='';
$output.="<a href='./'>根</a>\n";
$output.="<a href='./$phpself'>返</a>\n";
$output.="<br/>\n";
$htmlbody=$output.$htmlbody;
echo htmlhead();
echo form();
echo $htmlbody;
echo htmlend();
////
function auto_link($string){
	$string = preg_replace("/(^|[^=\]])(http|https)(:\/\/[\!-;\=\?-\~]+)/si", "\\1<a href=\"\\2\\3\" target='_blank'>\\2\\3</a>", $string);
	return $string;
}
function rdm_str($x=''){
	for($i=0;$i<3;$i++){
		$x=$x.chr(rand(97,122)); //小寫英文
	}
	return $x;
}
////
function htmlhead(){
$ymdhis=$GLOBALS['ymdhis'];
$phpself=$GLOBALS['phpself'];
$url=$GLOBALS['url'];
$x=<<<EOT
<html><head>
<title>$ymdhis</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css"><!--
body2 { font-family:"細明體",'MingLiU'; }
img.zoom {height:auto; width:auto; max-width:250px; max-height:250px;}
--></STYLE>
<script>
$(document).ready(function() {
	if(0){//註解??
		var state={
			note:"note",
			aaa:"刷新網址",
			title:"標題",
			url:"$phpself?$url"
		}
		//alert(state.url);
		window.history.replaceState(state,state.title,state.url);//无刷新改变URL//pushState
		//改變網址但不重整網頁
		window.onpopstate = function(e){
			//alert("popstate="+e.state.aaa);
		}
	}
	if(0){
		//$(document).on("keydown",function( event ) { //keyup keypress
		$(document).keydown(function(event){
			if(event.which == 17) {
				window.scroll(0, 0);//
			}
		});
	}
});

//window.onload = function () { }
</script>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE" onkeypress="check(event)">
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
網址<input type="text" name="input_a" id="input_a" size="20" value="">
<input type="submit" value=" send ">
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();
?>