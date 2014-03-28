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
					//$chk=@copy("index.php", $dir_mth."index.php");//複製檔案到該月目錄
					//if(!$chk){die('複製檔案失敗');}//$dir_mth="safemode/";
				}
			}
		}
	}
	//
	ob_start();
	echo "<pre>\n";
	foreach($output as $k => $v){
		echo "$v";
		$chk=rename($v, $dir_mth.$v);//移動檔案
		if($chk){echo "移動成功\n";}else{echo "失敗\n";}
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