<?php
//header('Content-Type: application/javascript; charset=utf-8');
//Header("Content-type: image/jpg");//指定文件類型
header('Content-type: text/html; charset=utf-8');
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$query_string=$_SERVER['QUERY_STRING'];
$input_a=$_POST['input_a'];
$input_b=$_POST['input_b'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
if($query_string){$url=trim($query_string);}else{$url=trim($input_a);}
//ignore_user_abort(1);//即使關閉網頁 也繼續讀取
///////////
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

///////////

if(empty($url)){//輸入密碼的頁面
	echo htmlhead();
	echo form();
	$output='';
	$output.="<a href='./'>根</a>\n";
	$output.="<a href='./$phpself'>返</a>\n";
	$output.="<br/>\n";
	echo $output;
	echo htmlend();
}else{//輸入密碼後
	if($url!="qqq"){die("b1");}//必須要輸入驗證
	if(!$input_b){$input_b=0;}
	if(preg_match("/[0-9]{1,2}/",$input_b)){
		if($input_b<10){
			$pn=$input_b;
		}else{
			die('b2a');
		}
		
	}else{
		die('b2b');
	}
	if($pn==0){$pn="index";}
	//$str=str_pad($str,2,"0",STR_PAD_LEFT);
	//$str=substr($str,0,2);
	$url="http://k0.dreamhosters.com/00/".$pn.".htm";//綜合首頁
	echo $url;
	echo "<br/>\n";
	$content = file_get_contents($url);
	//echo $content;
	//[<a href=index.php?res=4969843>H</a>]
	$pattern="/index\.php\?res=([0-9]+)\>返信/";
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
	foreach($kdao_arr as $k => $v){//迴圈A
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
		//$pattern='%<!--ad--><form action="index.php" method=POST>檔名：<a href="(.*)" target=_blank>%U';//非貪婪匹配
		$pattern='%<!--ad--><form action="index.php" method=POST>檔名：<a href="(http.*)" target=_blank>.*</a>.*<br><small>.*</small><br><a href=.*target=_blank><img src=.*border=0 align=left width=([0-9]*) height=([0-9]*) hspace=20.*</a><input type=checkbox%U';//非貪婪匹配
		preg_match($pattern, $content, $matches_db);//首篇的圖
		//print_r($matches_db);//$matches_db[1]
		//用迴圈叫出資料
		$htmlbody="";
		foreach($matches_b[1] as $k => $v){//用迴圈
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
			$have_img=0;
			if($k==0 && $matches_db[1]){//首篇的圖
				$tmp_str="http://web.archive.org/web/2014/".$matches_db[1];
				$tmp_str_w=$matches_db[2];
				$tmp_str_h=$matches_db[3];
				$have_img=1;
			}
			if($matches_dc[1]){//回應的圖
				$tmp_str="http://web.archive.org/web/2014/".$matches_dc[1];
				$tmp_str_w=$matches_dc[2];
				$tmp_str_h=$matches_dc[3];
				$have_img=1;
			}
			if($have_img){
				//$imgurl_arr[]=$tmp_str;
				//$tmp_str=$matches_dc[1];
				//$tmp_str=trim($tmp_str);
				//$htmlbody.='<img src="'.$tmp_str.'">';
				//$htmlbody.='<span style="display:block; width:2px; height:2px; BORDER:#000 1px solid; background:#FFFFFF url('.$tmp_str.') no-repeat left top; background-size:2px 2px;"/>送出</span>';
				//$htmlbody.='<script>document.write("[<a href=\''.$tmp_str.'\'><img src=\''.$tmp_str.'\' border=\'1\'></a>]");</script>';
				/*
				$tmp_str_ratio=($tmp_str_w/$tmp_str_h);
				if($tmp_str_ratio>1){
					$tmp_str_w=250;
					$tmp_str_h=floor(250/$tmp_str_ratio);
				}else{
					$tmp_str_w=floor(250*$tmp_str_ratio);
					$tmp_str_h=250;
				}
				*/
				//$htmlbody.= '[<a href="'.$tmp_str.'" target="_blank"><img class="zoom" src="'.$tmp_str.'" border="1"/></a>]';
				$htmlbody.= '[<a href="'.$tmp_str.'" target="_blank"><img class="zoom" src="'.$tmp_str.'" width="'.$tmp_str_w.'" height="'.$tmp_str_h.'" border="1"/></a>]';// 
				$htmlbody.=$tmp_str;
				$htmlbody.="<br>\n";
			}
		}//用迴圈
		$w_chk=1;
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
		$logfile=$dir_mth."z".$no."b.htm";//接頭(prefix)接尾(suffix)
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
		echo "<a href='$logfile'>$logfile</a>\n";
		echo "<br/>\n";
		ob_flush();
		flush();
		//睡1秒
		sleep(1);
	}//迴圈A
	ob_end_flush();
}
//
//
function rdm_str($x=''){
	for($i=0;$i<3;$i++){
		$x=$x.chr(rand(97,122));  //小寫英文
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
<input type="text" name="input_b" id="input_b" size="10" value="">
<input type="submit" value=" send ">
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();
?>