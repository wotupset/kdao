<?php 
$query_string=$_SERVER['QUERY_STRING'];
//header('Content-Type: application/javascript; charset=utf-8');
//Header("Content-type: image/jpg");//指定文件類型
header('Content-type: text/html; charset=utf-8');
//echo set_time_limit();
//ini_set('max_execution_time',0);
$phphost=$_SERVER["SERVER_NAME"];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);

extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//$input_a=$_POST['input_a'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
if($query_string){$url=$query_string;}else{$url=$input_a;}
//$url=trim($url);
//
if($url){
	$url_p=parse_url($url);
	$url_i=pathinfo($url_p['path']);
	if(!preg_match("%4chan%",$url_p['host'])){die('x網域');}
	//
	$content = file_get_contents($url) or die("[x]file_get_contents");//取得來源內容
	//echo $content;exit;
	$pattern="%(i.4cdn.org/[a-z]{1,3}/[0-9]{13}\.[a-z]{3})\"%U";//非貪婪
	preg_match_all($pattern, $content, $matches_chk);//內文-首篇
	//print_r($matches_chk);exit;//
	if(count($matches_chk[0])==0){die("[x]沒找到");}//沒找到
	$array_pic_url=array();
	foreach($matches_chk[1] as $k => $v){
		array_push($array_pic_url,$v);
	}
	$array_pic_url=array_unique($array_pic_url);//刪除重複的值
	$cc=0;$js='';
	foreach($array_pic_url as $k => $v){
		$cc=$cc+1;
		$pic_url='http://'.$v;
		$pic_url="./140319-1959-pic.php?".$pic_url;
		$js.="myArray[".$cc."]='".$pic_url."';\n";
		//
	}
	//
	//一般頁面
	echo htmlhead();
	echo form();
	$output='';
	$output.='<a href="./">根</a>'."\n";
	$output.='<a href="./'.$phpself.'">返</a>'."\n";
	$output.='<img src="./index.gif">'."\n";
	$output.=$url;
	$output.='<br/>'."\n";
	echo $output;
	$htmlbody='';
	$htmlbody.="\n\n<script>var myArray=[];\n".$js."</script>\n\n";
	echo $htmlbody;
	echo js_timedown($cc);
	echo htmlend();

}else{
	//一般頁面
	echo htmlhead();
	echo form();
	$output='';
	$output.='<a href="./">根</a>'."\n";
	$output.='<a href="./'.$phpself.'">返</a>'."\n";
	$output.='<img src="./index.gif">'."\n";
	$output.='<br/>'."\n";
	echo $output;
	echo $htmlbody;
	echo htmlend();
}
//
//過濾


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
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css"><!--
body2 { font-family:'MingLiU'; }
img.zoom {height:auto; width:auto; max-width:250px; max-height:250px;}
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
function form(){
$phpself=$GLOBALS['phpself'];
$url=$GLOBALS['url'];
$x=<<<EOT
<form enctype="multipart/form-data" action='$phpself' method="post">
網址<input type="text" name="input_a" size="20" value="">
<input type="submit" value=" send "><br/>
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();
function js_timedown($have_pic){
$x=<<<EOT
<script>
$(document).ready(function() {
	timedown_y();
	//$input_c
});
function timedown_y(){
	var t=0;
	var sec=1000;
	var FFF='';
	document.getElementById("timedown_div").innerHTML="準備"+t;
	var timedown_x = setInterval(function() {
		t=t+1;
		document.getElementById("timedown_div").innerHTML="("+t+"/$have_pic)..."+myArray[t];
		//FFF=document.getElementById("timedown_div_2").innerHTML;
		//document.getElementById("timedown_div_2").innerHTML=FFF+'<img src="'+myArray[t]+'" id="pic'+t+'" onclick="reget('+t+');" style="width:10px; height:20px;border:1px solid blue;">';
		//$("#timedown_div_2").append('<img src="'+myArray[t]+'" id="pic'+t+'" onclick="reget('+t+');" style="width:10px; height:20px;border:1px solid blue;">');
		//FFF = document.createTextNode('<img src="'+myArray[t]+'" id="pic'+t+'" onclick="reget('+t+');" style="width:10px; height:20px;border:1px solid blue;">');
		FFF = document.createElement('span');
		FFF.innerHTML = '<img src="'+myArray[t]+'" id="pic'+t+'" style="width:10px; height:20px;border:1px solid blue;">';
		FFF.id = 'span'+t;
		//FFF.setAttribute("id", "uniqueIdentifier");
		document.getElementById("timedown_div_2").appendChild(FFF);
		document.getElementById("pic"+t).setAttribute('onclick',"reget("+t+")");
		//
		if(t<$have_pic){
			if(t%2){
			document.getElementById("timedown_div").style.backgroundColor="#00ffff";
			FFF='／';
			}else{
			document.getElementById("timedown_div").style.backgroundColor="#ffffff";
			FFF='＼';
			}
			document.title=FFF+"("+t+"/$have_pic)";
			timedown_x;
		}else{
			document.getElementById("timedown_div").innerHTML="沒了"+t;
			document.getElementById("timedown_div").style.backgroundColor="#00ff00";
			document.title="完成"+t+"";
			clearInterval(timedown_x);
		}
	}, sec);
}
function reget(x){
	var d = new Date();
	var n = d.getTime();
	document.getElementById('span'+x).style.backgroundColor="#00ffff";
	document.getElementById("pic"+x).src='./index.gif';
	document.getElementById("pic"+x).src=myArray[x]+'?'+n;
}
</script>
<div id='timedown_div'></div>
<div id='timedown_div_2'></div>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo js_timedown();

?>