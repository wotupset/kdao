<?php
//header('Content-Type: application/javascript; charset=utf-8');
//Header("Content-type: image/jpg");//指定文件類型
header('Content-type: text/html; charset=utf-8');
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
//$query_string=$_SERVER['QUERY_STRING'];
//$input_a=$_POST['input_a'];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
$ym=date('ym',$time);//輸出的檔案名稱
if($input_a!="qqq"){die('a');}
if($input_b!="123"){die('b');}
///////////
if(1){
	$dir_mth="./public_html/kdao/_".$ym."c/"; //存放該月檔案
	@mkdir("./public_html/", 0777); //建立資料夾 權限0777
	@mkdir("./public_html/kdao/", 0777); //建立資料夾 權限0777
	@mkdir("./public_html/kdao/_".$ym."c/", 0777); //建立資料夾 權限0777
	if(!is_dir($dir_mth)){die("月份資料夾不存在");}
	if(!is_writeable($dir_mth)){die("月份資料夾無法寫入");}
	if(!is_readable($dir_mth)){die("月份資料夾無法讀取");}
}
//

///////////

if(1){//輸入密碼的頁面

	$url="http://k0.dreamhosters.com/00/index.htm";//綜合首頁
	$content = file_get_contents($url);
	//echo $content;
	//[<a href=index.php?res=4969843>H</a>]
	$pattern="/index\.php\?res=([0-9]+)\>/";
	preg_match_all($pattern, $content, $matches_a,PREG_OFFSET_CAPTURE);
	//print_r($matches_a);
	$kdao_arr=array();
	$cc=0;
	foreach($matches_a[1] as $k => $v){
		$kdao_arr[$k][0]="http://k0.dreamhosters.com/00/index.php?res=".$matches_a[1][$k][0]."";
		$kdao_arr[$k][1]=$matches_a[1][$k][0];
		//echo $kdao_arr[$cc][0];
		$cc=$cc+1;
	}
	//exit;
	//$ct = count($kdao_arr);
	//for($i = 0; $i < $ct; $i++){
	if(ob_get_level() == 0) ob_start();
	foreach($kdao_arr as $k => $v){
		//if($k>4){break;}
		$no =$kdao_arr[$k][1];//md5($url);
		$url=$kdao_arr[$k][0];
		$content = file_get_contents($url) or die("[error]file_get_contents");
		$content = preg_replace("/\n/","",$content);
		$content = preg_replace("/\t/","",$content);
		//過濾
		$pattern="%<blockquote>.+</blockquote>%U";
		preg_match_all($pattern, $content, $matches_a);//PREG_OFFSET_CAPTURE
		//print_r($matches_a);//$matches_c[1][$k][0]
		if(count($matches_a[0])==0){die("x");}//沒找到
		$pattern="%<font color=#117743><b>(.+)</b></font>(.+)<a class=del%U";
		preg_match_all($pattern, $content, $matches_b,PREG_OFFSET_CAPTURE);
		//print_r($matches_b);//$matches_c[1][$k][0]
		$pattern='%</small><br><a href="(.+)" target=_blank>%U';
		preg_match_all($pattern, $content, $matches_c,PREG_OFFSET_CAPTURE);
		//print_r($matches_c);//$matches_c[1][$k][0]
		$pattern='%<font color=#cc1105 size.*</blockquote>%U';//非貪婪匹配
		preg_match_all($pattern, $content, $matches_da,PREG_OFFSET_CAPTURE);
		//print_r($matches_da);//$matches_da[0][$k][0]
		$pattern='%<!--ad--><form action="index.php" method=POST>檔名：<a href="(.*)" target=_blank>%U';//非貪婪匹配
		preg_match($pattern, $content, $matches_db);//首篇的圖
		//print_r($matches_db);//$matches_db[1]
		//用迴圈叫出資料
		$htmlbody="";
		foreach($matches_b[1] as $k => $v){
			$htmlbody.= "<b>".$matches_b[1][$k][0]."</b>\n";//名稱
			//分析ID與編號
			//$pattern="/ID:(.*) No\.([0-9]*)/";
			//preg_match_all($pattern, $matches_b[2][$k][0], $matches_bb,PREG_OFFSET_CAPTURE);
			//print_r($matches_bb);//$matches_c[1][$k][0]
			//$htmlbody.= "ID:".$matches_bb[1][0][0]."\n";//ID
			//$htmlbody.= "No.".$matches_bb[2][0][0]."\n";//文章編號
			$htmlbody.= $matches_b[2][$k][0];
			$htmlbody.= "".$matches_a[0][$k]."\n";//內文
			//
			$pattern='%<br><a href="(.*)" target=_blank><img src=(.*) border=0 align=left .*></a>%U';//非貪婪匹配
			$arr_imgurl[$k]=array();
			preg_match($pattern, $matches_da[0][$k][0], $matches_dc);//從內文中找圖
			//print_r($matches_db);
			if($k==0 && $matches_db[1]){//首篇的圖
				$tmp_str="http://web.archive.org/web/2014/".$matches_db[1];
				//$tmp_str=$matches_dc[1];
				//$tmp_str=trim($tmp_str);
				//$htmlbody.='<img src="'.$tmp_str.'">';
				//$htmlbody.='<span style="display:block; width:2px; height:2px; BORDER:#000 1px solid; background:#FFFFFF url('.$tmp_str.') no-repeat left top; background-size:2px 2px;"/>送出</span>';
				//$htmlbody.='<script>document.write("[<a href=\''.$tmp_str.'\'><img src=\''.$tmp_str.'\' border=\'1\'></a>]");</script>';
				$htmlbody.= '[<a href="'.$tmp_str.'" target="_blank"><img src="'.$tmp_str.'" border="1"/></a>]';
				$htmlbody.=$tmp_str;
				$htmlbody.="<br>\n";
			}
			if($matches_dc[1]){//回應的圖
				$tmp_str="http://web.archive.org/web/2014/".$matches_dc[1];
				//$tmp_str=$matches_dc[1];
				//$tmp_str=trim($tmp_str);
				//$htmlbody.='<img src="'.$tmp_str.'">';
				//$htmlbody.='<span style="display:block; width:2px; height:2px; BORDER:#000 1px solid; background:#FFFFFF url('.$tmp_str.') no-repeat left top; background-size:2px 2px;"/>送出</span>';
				//$htmlbody.='<script>document.write("[<a href=\''.$tmp_str.'\'><img src=\''.$tmp_str.'\' border=\'1\'></a>]");</script>';
				$htmlbody.= '[<a href="'.$tmp_str.'" target="_blank"><img src="'.$tmp_str.'" border="1"/></a>]';
				$htmlbody.=$tmp_str;
				$htmlbody.="<br>\n";
			}
		}
		$htmlbody.="<br>\n<br>\n";
//修飾
//$htmlbody="<a href='./'>根</a>\n"."<a href='../$phpself'>返</a>\n"."<a href='$logfile'>元</a>\n"."<br/>\n".$htmlbody;//修飾
$htmlbody=$url."\n"."<br/>\n".$htmlbody;

		//要寫入的內容
		$output='';
		$output.=pack("CCC", 0xef,0xbb,0xbf);
		$output.= htmlhead();
		$output.="<a href='./'>根</a>\n";
		$output.="<a href='../'>返</a>\n";
		$output.="<br/>\n";
		$output.= $htmlbody;
		$output.= htmlend();
		//echo $htmlbody;	exit;
		//準備寫入檔案
		//$ymdhis
		$logfile=$dir_mth."z".$no."c.htm";//接頭(prefix)接尾(suffix)
		//$logfile="z".$no.".htm";//接頭(prefix)接尾(suffix)
		$cp = fopen($logfile, "a+") or die('failed');// 
		ftruncate($cp, 0); //砍資料至0
		fputs($cp, $output);
		fclose($cp);
		//輸出檢查
		
		echo $url;
		echo "\n";
		if(is_file($logfile)){$tmp_str="成功";}else{die("失敗");}
		echo $tmp_str;
		echo "\n";
		echo filesize($logfile);
		echo "\n";
		echo "<a href='$logfile'>$logfile</a><br/>\n";
		ob_flush();
		flush();
		//睡1秒
		sleep(1);
	}
	ob_end_flush();
}
//
//
function rdm_str($x=''){
	for($i=0;$i<3;$i++){
		$x=$x.chr(rand(97,122)); //pg^
	}
	return $x;
}
function htmlhead(){
$ymdhis=$GLOBALS['ymdhis'];
$x=<<<EOT
<html><head>
<title>$ymdhis</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<meta name="Robots" content="index,follow">
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
<form enctype="multipart/form-data" action='$phpself' method="post">
不能直接執行<input type="text" name="input_a" id="input_a" size="20" value="">
<input type="submit" value=" send ">
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();
?>