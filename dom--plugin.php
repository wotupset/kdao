<?php 
//ini_set("memory_limit","10M");
header('Content-type: text/html; charset=utf-8');
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$query_string=$_SERVER['QUERY_STRING'];
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//$input_a=$_POST['input_a'];
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
$phphost=$_SERVER["SERVER_NAME"];
//
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
if($query_string){
	$url=$query_string;
	$input_c='500';
}else{
	$url=$input_a;
}
$url=trim($url);
include('./simple_html_dom.php');//v1.5
//$input_c=!$input_c;//有勾選=1 >反轉=0 >0=漸進

///////////

///////////$dir_mth
if(1){
	$dir_mth="./_".date("ym",$time)."/"; //存放該月檔案
	if(!is_writeable(realpath("./"))){ die("根目錄沒有寫入權限，請修改權限"); }
	@mkdir($dir_mth, 0777); //建立資料夾 權限0777
	@chmod($dir_mth, 0777); //權限0777
	if(!is_dir(realpath($dir_mth))){die("月份資料夾不存在");}
	if(!is_writeable(realpath($dir_mth))){die("月份資料夾無法寫入");}
	if(!is_readable(realpath($dir_mth))){die("月份資料夾無法讀取");}
	if(is_file("index.php")){//確認檔案存在
		//有存在
	}else{
		//沒存在
		die("沒存在");
	}
	if(!is_dir($dir_mth)){//子資料夾不存在
		//沒事
	}else{//子資料夾存在.
		if(!file_exists("index.php")){//如果根目錄沒有index檔案
			die('index檔案遺失');
		}else{//根目錄有index檔案
			if(!is_file($dir_mth."index.php")){//如果該月目錄沒有index檔案
				$chk=@copy("index.php", $dir_mth."index.php");//複製檔案到該月目錄
				if(!$chk){die('複製檔案失敗');}//$dir_mth="safemode/";
			}
		}
	}
}
///////////$dir_mth
//允許的網址格式
//print_r($url_p,true);exit;//檢查點
//if(preg_match("%nagatoyuki\.org%U",$url_p['host'])){$kdao_only=1;}
///////////
$w_chk=0;
$htmlbody='';$htmlbody2='';$htmlbody2_js='';
$have_pic=0;$have_text=0;//計算圖片跟留言數量
if($url){//有輸入網址
	///////////
	if( function_exists('curl_version') ){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
		$return = array();
		$return = curl_getinfo($ch);//文件狀態
		if( !( $return['CURLINFO_HTTP_CODE']<400 ) ){die('CURLINFO_HTTP_CODE');}//狀態錯誤就停止
		$html_get = curl_exec($ch);
		//print_r($html_get);
		$return = curl_getinfo($ch);
		//print_r($return);
		curl_close($ch);
		//exit;
	}else{
		$html_get = file_get_contents($url);
	}
	$FFF=strlen($html_get);
	if($FFF == 0){die('沒有資料');}
	//echo $FFF;exit;
	if(!preg_match("/html/i",substr($html_get,0,500))){die('不是HTML檔案');}
	///////////
	$url_p=parse_url($url);
	$chk=0;
	unset($html);$html='';
	if(preg_match("%nagatoyuki\.org%U",$url_p['host'])) {$chk=1;include('./dom--naga.php');}
	if(preg_match("%\.mykomica\.org%U",$url_p['host'])) {$chk=1;include('./dom--myk.php');}
	if(preg_match("%futakuro\.com%U",$url_p['host'])) {$chk=1;include('./dom--jk2.php');}
	if(preg_match("%\.dreamhosters\.com%U",$url_p['host'])) {$chk=1;include('./dom--k.php');}
	if(preg_match("%\.komica\.org%U",$url_p['host'])) {$chk=1;include('./dom--k.php');}
	if(preg_match("%\.wsfun\.com%U",$url_p['host'])) {$chk=1;include('./dom--wsf.php');}
	if(preg_match("%\.2chan\.net%U",$url_p['host'])) {$chk=1;include('./dom--2c.php');}
	if(preg_match("%fenrisulfr\.org%",$url_p['host'])) {$chk=1;include('./dom--fen.php');}
	if(preg_match("%2cat\.or\.tl%",$url_p['host'])) {$chk=1;include('./dom--ted.php');}
	if(preg_match("%rthost\.ez\.lv%",$url_p['host'])) {$chk=1;include('./dom--ezlv.php');}
	if(preg_match("%yucie\.net%",$url_p['host'])) {$chk=1;include('./dom--yuc.php');}
	if(preg_match("%acfun\.tv%",$url_p['host'])) {$chk=1;include('./dom--acf.php');}
	if(preg_match("%4chan\.org%",$url_p['host'])) {$chk=1;include('./dom--4c.php');}
	//
	if(!$chk){die('不是符合的網域');}
	$html->clear();unset($html);//php5物件bug 要手動釋放記憶體
	if(!$no){die('沒有接收到no');}
	if(!$pre_fix){die('沒有接收到pre_fix');}
}else{
	//沒輸入
}
//////

$htmlbody=$url."<br/>\n".$htmlbody."<br>\n<br>\n";
if($w_chk){//寫入到檔案
	$output='';
	$output.=pack("CCC", 0xef,0xbb,0xbf);//UTF8檔頭
	$output.=htmlhead();
	$output.="<a href='./'>根</a>\n";
	//$output.="<a href='../$phpself'>返</a>\n";
	$output.="<br/>\n";
	$output.=$htmlbody;
	$output.=htmlend();

	//
	if($no_pg){$no_pg="_".$no_pg;}
	if($dm){$dm="_".$dm;}
	$logfile=$dir_mth.$pre_fix.$dm."_".$no."".$no_pg.".htm";//接頭(prefix)接尾(suffix)
	$cp = fopen($logfile, "a+") or die('failed');// 讀寫模式, 指標於最後, 找不到會嘗試建立檔案
	ftruncate($cp, 0); //砍資料至0
	fputs($cp, $output);
	fclose($cp);
	////////
	$save_url=$phpdir.$logfile;
	$save_where='';
	$save_where.="<a href='https://archive.today/?run=1&url=$save_url' target='_blank'>↗</a>";
	$save_where.="存檔=<a href='$logfile'>$logfile</a>\n";
	//
	////////
}//寫入到檔案/

//一般頁面
echo htmlhead2();
echo form();
$output='';
$output.="<a href='./'>根</a>\n";
$output.="<a href='./$phpself'>返</a>\n";
$output.="<a href='./k_fourm.php'>貼</a>\n";
$output.="<a href='./k_fourm2.php'>閱</a>\n";
$output.='<img src="./index.gif?'.$time.'">';
$output.='<img src="./png.php?'.$time.'" width="90" height="15"/>'."\n";
if(isset($save_where)){
	$output.=$save_where;
	if($have_pic){
		if($input_c){
			$output.=js_timedown();//
			$htmlbody2_js="\n\n<script>var myArray=[];\n".$htmlbody2_js."</script>\n\n";
			echo $htmlbody2_js;
		}else{
			//
		}
	}
}
$output.=$url."\n";
$output.='<br/>';
$output.="\n";
echo $output;
echo $htmlbody2;//

if($have_pic && 1){//打包功能 很吃流量 慎用//0=停用
echo "<br/>\n";
echo "<a href='./zip.php?a1=".$no."&a2=".$zip_pic."'>zip</a>";
}
echo htmlend();

////
function img_filename($x){
	$url=$x;
	$url2=substr($url,0,strrpos($url,"/")+1); //根目錄
	$tmp_str=strlen($url2)-strlen($url);
	$url3=substr($url,$tmp_str);//圖檔檔名
	return $url3;
}
////
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
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css"><!--
body2 { font-family:'MingLiU'; }
img.zoom {
height:auto; width:auto;
min-width:20px; min-height:20px;
max-width:250px; max-height:250px;
border:1px solid blue;
}
span.name {
display: inline-block;
white-space:nowrap;
font-weight: bold;
color: #117743;
min-width:10px;
max-width:100px;
overflow:hidden;
}
span.title {
display: inline-block;
white-space:nowrap;
font-weight: bold;
color: #CC1105;
min-width:10px;
max-width:100px;
overflow:hidden;
}
span.idno {
display: inline-block;
white-space:nowrap;
min-width:10px;
max-width:500px;
overflow:hidden;
}

--></STYLE>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
EOT;
$x="\n".$x."\n";
return $x;
}
//echo htmlhead();
function htmlhead2(){
$ymdhis=$GLOBALS['ymdhis'];
$phphost=$GLOBALS['phphost'];
$phpself=$GLOBALS['phpself'];
$url=$GLOBALS['url'];
$x=<<<EOT
<html><head>
<title>★$phphost$ymdhis</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css">
body2 { font-family:'MingLiU'; }
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
//echo htmlend();
function form(){
$phpself=$GLOBALS['phpself'];
$url=$GLOBALS['url'];
$x=<<<EOT
<form enctype="multipart/form-data" action='$phpself' method="post">
網址<input type="text" name="input_a" size="20" value=""><input type="submit" value=" send "><br/>
<label>重新讀圖<input type="checkbox" name="input_b" value="1" />(破圖時使用)</label><br/>
<label>漸進讀圖
<select name="input_c">
<option value="">OFF</option>
<option value="3000">3.0</option>
<option value="2000">2.0</option>
<option value="1000" selected="selected">1.0</option>
<option value="500">0.5</option>
</option>
</select>(主機不穩時使用)</label><br/>
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();
function js_timedown(){
$have_pic=$GLOBALS['have_pic'];
$input_c = $GLOBALS['input_c'];
$x=<<<EOT
<script>
$(document).ready(function() {
	timedown_y();
	//$input_c
});
function timedown_y(){
	var t=0;
	var sec=$input_c;
	var FFF='';
	document.getElementById("timedown_span").innerHTML="準備"+t;
	var timedown_x = setInterval(function() {
		t=t+1;
		document.getElementById("timedown_span").innerHTML="("+t+"/$have_pic)..."+myArray[t];
		document.getElementById("pic"+t).src=myArray[t];
		document.getElementById("pn"+t).style.color = "#00ff00";
		FFF=document.getElementById("pn"+t).innerHTML;
		document.getElementById("pn"+t).innerHTML = '<a href="'+myArray[t]+'" target="_blank">檢</a>'+FFF;
		//document.getElementById("pn"+t).setAttribute('onclick',"re_get("+t+")");
		// onclick="re_get('.$have_pic.')"
		if(t<$have_pic){
			timedown_x;
			document.title="("+t+"/$have_pic)";
		}else{
			document.getElementById("timedown_span").innerHTML="沒了"+t;
			document.getElementById("timedown_div").style.backgroundColor="#00ff00";
			clearInterval(timedown_x);
			document.title="完成"+t+"";
		}
	}, sec);
}
function re_get(x){
	var d = new Date();
	var n = d.getTime();
	document.getElementById("pic"+x).src='./index.gif';
	document.getElementById("pic"+x).src=myArray[x]+'?'+n;
}
</script>
<div id='timedown_div'><span id='timedown_span'></span></div>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo js_timedown();

?>