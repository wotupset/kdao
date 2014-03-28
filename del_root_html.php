<?php
header('Content-type: text/html; charset=utf-8');
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//$input_a=$_POST['input_a'];
$query_string=$_SERVER['QUERY_STRING'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
$url=$input_a;
////
$echo_body='';
if(empty($input_a)){
	//$echo_body.=form();
}else{
	if($input_a!="qqq"){die("x");}//必須要輸入驗證
	//遍歷所在目錄
	$time = (string)time();
	$output=array();
	$url='./';//要檢查的資料夾
	$handle=opendir($url); 
	$cc = 0;
	while (($file = readdir($handle))!==false) { //遍歷該資料夾
		if($file != "." && $file != "..") { //這兩個不處理
			if(is_file($file)){//如果是檔案
				if(preg_match("/.htm$/",$file)){ //有符合的檔名
					array_push($output, $file); //抽出dir名稱到陣列2
				}
			}
		} 
		$cc = $cc + 1;
	} 
	closedir($handle); 
	rsort($output);//新的在前 
	//
	ob_start();
	echo "<pre>\n";
	foreach($output as $k => $v){
		echo "$v";
		$chk=unlink($v);//刪除檔案
		if($chk){echo "刪除成功\n";}else{echo "失敗\n";}
	}
	echo "</pre>\n";
	$echo_body=ob_get_contents();//輸出擷取到的echo
	ob_end_clean();//清空擷取到的內容
	//輸出結果
}
echo htmlhead();
echo form();
echo "<a href='./'>根</a>\n";
echo "<a href='./$phpself'>返</a>\n";
echo $echo_body;
echo htmlend();
////
function htmlhead(){
$ymdhis=$GLOBALS['ymdhis'];
$x=<<<EOT
<html><head>
<title>$ymdhis</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<meta name="Robots" content="noindex,follow">
<STYLE TYPE="text/css"><!--
body2 { font-family:"細明體",'MingLiU'; }
img {height:auto; width:auto; max-width:250px; max-height:250px;}
--></STYLE>
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
$x=<<<EOT
<form enctype="multipart/form-data" action='$phpself' method="get">
不能直接執行<input type="text" name="input_a" id="input_a" size="20" value="">
<input type="submit" value=" send ">
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();
////
?>