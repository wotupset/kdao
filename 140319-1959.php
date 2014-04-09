<?php 
//header('Content-Type: application/javascript; charset=utf-8');
//Header("Content-type: image/jpg");//指定文件類型
header('Content-type: text/html; charset=utf-8');
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$query_string=$_SERVER['QUERY_STRING'];
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//$input_a=$_POST['input_a'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
if($query_string){$url=$query_string;}else{$url=$input_a;}
$url=trim($url);
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
				$chk=@copy("index.php", $dir_mth."index.php");//複製檔案到該月目錄
				if(!$chk){die('複製檔案失敗');}//$dir_mth="safemode/";
			}
		}
	}
}
//允許的網址格式
$kdao_only=0;
if(preg_match("%dreamhosters\.com/[0-9]{2}/%U",$url))
{$kdao_only=1;}
if(preg_match("%komica\.org/[0-9]{2}/%U",$url))
{$kdao_only=1;}

///////////
$w_chk=0;
if(!$kdao_only){//只使用於綜合網址
    //die("x");
    //沒事
}else{
	////////////
	$pattern="%index.php\?res=([0-9]+)%";
	preg_match($pattern, $url, $matches_url);//抓首串編號
	//print_r($matches_url);//
	$no=$matches_url[1];//首篇編號
	//
	$content = file_get_contents($url) or die("[error]file_get_contents");//取得來源內容
	$content = preg_replace("/\n/","",$content);
	$content = preg_replace("/\t/","",$content);
	//過濾
	$pattern="%<blockquote>.+</blockquote>%U";
	preg_match_all($pattern, $content, $matches_a);//PREG_OFFSET_CAPTURE
	//print_r($matches_a);//
	if(count($matches_a[0])==0){die("x");}//沒找到
	$pattern="%<font color=#117743><b>(.+)</b></font>(.+)<a class=del%U";
	preg_match_all($pattern, $content, $matches_b,PREG_OFFSET_CAPTURE);
	//print_r($matches_b);//

	$pattern='%<font color=#cc1105 size.*</blockquote>%U';//非貪婪匹配
	preg_match_all($pattern, $content, $matches_da,PREG_OFFSET_CAPTURE);
	//print_r($matches_da);//$matches_da[0][$k][0]
	
	//$pattern='%--><form action="index.php" method=POST>檔名：<a href="(.*)" target=_blank>%U';//非貪婪匹配
	//$pattern='%<!--ad--><form action="index.php" method=POST>檔名：<a href="(http.*)" target=_blank>.*</a>.*<br><small>.*</small><br><a href=.*target=_blank><img src=.*border=0 align=left width=([0-9]*) height=([0-9]*) hspace=20.*</a><input type=checkbox%U';//非貪婪匹配
	$pattern='%</small><br><a href="(.*)" target=_blank><img src%U';//非貪婪匹配
	preg_match($pattern, $content, $matches_db);//首篇的圖 只找第一個
	//print_r($matches_db);//$matches_db[1]
	
	//用迴圈叫出資料
	$htmlbody="";
	$htmlbody2="";
	$imgurl_arr=array();//存圖片網址
	$cc=0;$cc2=0;
	foreach($matches_b[1] as $k => $v){//迴圈
		$htmlbody.= "<b>".$matches_b[1][$k][0]."</b>\n";//名稱
		//分析ID與編號
		//$pattern="/ID:(.*) No\.([0-9]*)/";
		//preg_match_all($pattern, $matches_b[2][$k][0], $matches_bb,PREG_OFFSET_CAPTURE);
		//print_r($matches_bb);//$matches_c[1][$k][0]
		//$htmlbody.= "ID:".$matches_bb[1][0][0]."\n";//ID
		//$htmlbody.= "No.".$matches_bb[2][0][0]."\n";//文章編號
		$htmlbody.= $matches_b[2][$k][0];
		//strip_tags($matches_a[0][$k],"<br>")
		$htmlbody.= "<blockquote>".strip_tags($matches_a[0][$k],"<br>")."</blockquote>\n";//內文
		//分析內文中的圖a
		$pattern='%</small><br><a href="(.*)" target=_blank><img src%U';//非貪婪匹配
		//$pattern='%<br><a href="(.*)" target=_blank><img src=(.*) border=0 align=left .*></a>%U';//非貪婪匹配
		//$pattern='%<br><a href="(.*)" target=_blank><img src=.*border=0 align=left width=([0-9]*) height=([0-9]*) hspace=20.*></a><blockquote>%U';//非貪婪匹配
		preg_match($pattern, $matches_da[0][$k][0], $matches_dc);//從內文中找圖
		//print_r($matches_db);
		/*
		//分析內文中的圖b
		$pattern='%<br><a href="(.*)" target=_blank><img src=(.*)nothumbs.png border=1 align=left .*></a>%U';//非貪婪匹配
		//$pattern='%<br><a href="(.*)" target=_blank><img src=.*border=0 align=left width=([0-9]*) height=([0-9]*) hspace=20.*></a><blockquote>%U';//非貪婪匹配
		preg_match($pattern, $matches_da[0][$k][0], $matches_dd);//從內文中找圖//無縮圖
		//print_r($matches_db);
		*/
		$have_img=0;
		if($k==0 && $matches_db[1]){//首篇的圖
			//$tmp_str="http://web.archive.org/web/2014/".$matches_db[1];
			$pic_url=$matches_db[1];
			$tmp_str_w=$matches_db[2];
			$tmp_str_h=$matches_db[3];
			$have_img=1;
		}
		if($matches_dc[1]){//回應的圖
			//$tmp_str="http://web.archive.org/web/2014/".$matches_dc[1];
			$pic_url=$matches_dc[1];
			$tmp_str_w=$matches_dc[2];
			$tmp_str_h=$matches_dc[3];
			$have_img=1;
		}
		/*
		if(count($matches_dd)>0){//回應的圖//無縮圖
			//$tmp_str="http://web.archive.org/web/2014/".$matches_dc[1];
			$pic_url="http://k0.dreamhosters.com/pix/nothumbs.png";
			$tmp_str_w="125";
			$tmp_str_h="94";
			$have_img=1;
		}
		*/
		if($have_img){//有偵測到圖
			//$pic_url
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			
			$pic_url_2=substr($pic_url,0,strrpos($pic_url,"/")+1); //根目錄
			$pic_url_3_a=strlen($pic_url_2)-strlen($pic_url);
			$pic_filename=substr($pic_url,$pic_url_3_a);//圖檔檔名
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
			//$htmlbody.= '[<a href="'.$tmp_str.'" target="_blank"><img class="zoom" src="'.$tmp_str.'" width="'.$tmp_str_w.'" height="'.$tmp_str_h.'" border="1"/></a>]';// 
			$htmlbody2.='<span style="background-image: url(\''.$pic_url_php.'\'); ">^</span>';
			//width="'.$tmp_str_w.'" height="'.$tmp_str_h.'" 
			$htmlbody.= '[<a href="./src/'.$pic_filename.'" target="_blank"><img class="zoom" src="./src/'.$pic_filename.'" border="1"/></a>]';// 
			//$htmlbody.=$tmp_str;
			//$htmlbody.="\n";
			//$htmlbody.=$tmp_str_w."x".$tmp_str_h;
			//$htmlbody.="\n";
			//$htmlbody.=$tmp_str_ratio;
			//$htmlbody.="\n";
			$htmlbody.="<br>\n";
			$cc2=$cc2+1;
		}
		$cc=$cc+1;
	}//迴圈
	$w_chk=1;
	$htmlbody2.= "[$cc][$cc2]";
}//有輸入url/
//修飾
$htmlbody=$url."\n"."<br/>\n".$htmlbody."<br>\n<br>\n";
//////
if($w_chk){//寫入到檔案
    $output='';
    $output.=pack("CCC", 0xef,0xbb,0xbf);//UTF8檔頭
    $output.=htmlhead();
    $output.="<a href='./'>根</a>\n";
    $output.="<a href='../$phpself'>返</a>\n";
    $output.="<br/>\n";
    $output.=$htmlbody;
    $output.=htmlend();
    //
    $logfile=$dir_mth."z".$no.".htm";//接頭(prefix)接尾(suffix)
    //$logfile="z".$no.".htm";//接頭(prefix)接尾(suffix)
    $cp = fopen($logfile, "a+") or die('failed');// 讀寫模式, 指標於最後, 找不到會嘗試建立檔案
    ftruncate($cp, 0); //砍資料至0
    fputs($cp, $output);
    fclose($cp);
    ////////
    $save_where="存檔=<a href='$logfile'>$logfile</a>\n";
    ////////
}//寫入到檔案/

//一般頁面
echo htmlhead();
echo form();
$output='';
$output.="<a href='./'>根</a>\n";
$output.="<a href='./$phpself'>返</a>\n";
if(isset($save_where)){$output.=$save_where;}
$output.="<br/>\n";
echo $output;
echo $htmlbody2;
//echo $htmlbody;//

echo htmlend();

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
	if(1){
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
綜合網址<input type="text" name="input_a" size="20" value="">
<input type="submit" value=" send "><br/>
<label>重新讀圖<input type="checkbox" name="input_b" value="1" />(破圖時使用)</label>
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();
?>