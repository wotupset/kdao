<?php 
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
$query_string=$_SERVER['QUERY_STRING'];
if($query_string=="png"){
header('Content-Type: image/gif');
$b64="R0lGODdhAQABAIAAMQAAAP///ywAAAAAAQABAAACAkQBADs=";
echo base64_decode($b64);
exit;
}
//header('Content-Type: application/javascript; charset=utf-8');
//Header("Content-type: image/jpg");//指定文件類型
header('Content-type: text/html; charset=utf-8');
//echo set_time_limit();
//ini_set('max_execution_time',0);
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);

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
if(preg_match("%wsfun\.com%U",$url))
{$kdao_only=1;}

///////////
$w_chk=0;
if(!$kdao_only){//只使用於綜合網址
    //die("x");
    //沒事
}else{
	//取得來源內容
	$content = file_get_contents($url) or die("[error]file_get_contents");//取得來源內容
	$content = preg_replace("/\n\n<\/div>\n\n/","qfo2Tv77RXt8To65",$content);
	$content = preg_replace("/\n\n<\/div><\/div>\n\n/","VDZtI5qmc0h755B4",$content);
	$content = preg_replace("/\n/","",$content);
	$content = preg_replace("/\r/","",$content);
	$content = preg_replace("/\t/","",$content);
	//過濾
	$pattern="%<div class=\"threadpost\" id=\"r[0-9]+\">.*</div>qfo2Tv77RXt8To65%u";//非貪婪
	preg_match_all($pattern, $content, $matches_a);//內文-首篇
	//print_r($matches_a);exit;
	if(count($matches_a[0])==0){die("[x]沒找到內文格式");}//沒找到
	//過濾
	$pattern="%<div class=\"reply\" id=\"r[0-9]+\">.*VDZtI5qmc0h755B4%U";//非貪婪
	preg_match_all($pattern, $content, $matches_b);//內文
	//print_r($matches_b[0]);exit;
	if(count($matches_b)==0){die("[x]沒找到內文格式");}//沒找到
	$matches_ab=array_merge($matches_a[0],$matches_b[0]);//合併 //整理出的所有留言
	//print_r($matches_ab);exit;
	//
	/*
	$matches_ab[0]=第一篇
	$matches_ab[1]=第二篇
	*/
	
	//用迴圈叫出資料
	$htmlbody="";
	$htmlbody2="";
	$img_all='';
	$cc=0;//回文數
	$cc2=0;//貼圖數
	foreach($matches_ab as $k => $v){//迴圈
		//<a href="http://acgspace.wsfun.com/kancolle/src/1398711077205.jpg" rel="_blank">
		$pattern="%檔名：<a href=\"(.*)\" rel=\"_blank\">[0-9]{10}%U";//非貪婪
		$chk_1=preg_match($pattern, $v, $matches_t1);//圖片
		//print_r($matches_t1);//
		//
		$pattern="%<div class=\"quote\">(.*)<\/div>%U";//非貪婪
		$chk_2=preg_match($pattern, $v, $matches_t2);//內文
		//print_r($matches_t2);//
		//
		$pattern="%<span class=\"title\">(.*)</span>.+<span class=\"name\">(.*)</span>(.*)<a href%U";//非貪婪
		$chk_3=preg_match($pattern, $v, $matches_t3);//標題 名稱 ID 時間
		//print_r($matches_t3);// 1=標題 2=名稱 3=時間 4=ID
		//
		//標題 名稱 ID 時間
		if($chk_3){
			$matches_t3[2]=strip_tags($matches_t3[2],"<br>");//去掉名稱的email
			//$htmlbody.="<b>".$matches_t3[1]." ".$matches_t3[2]."</b> ".$matches_t3[3]." ".$matches_t3[4]."<br/>\n";//去掉html標籤
			$htmlbody.= strip_tags($matches_t3[1])."\n";//標題
			$htmlbody.= strip_tags($matches_t3[2])."\n";//名稱
			$htmlbody.= strip_tags($matches_t3[3])."\n";//時間
			$htmlbody.= strip_tags($matches_t3[4])."\n";//ID
		}
		//內文
		if($chk_2){
			$htmlbody.="<blockquote>".strip_tags($matches_t2[1],"<br>")."</blockquote>\n";//去掉html標籤
		}
		
		$have_img=0;
		if(count($matches_t1[1])){//回應中有圖
			$pic_url=$matches_t1[1];
			$have_img=1;
		}
		
		if($have_img){
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			$img_filename=img_filename($matches_t1[1]);//圖檔檔名
			if($cc2>0){$img_all_cm=",";}
			$img_all.=$img_all_cm.$img_filename;
			$htmlbody2.='<span style="background-image: url(\''.$pic_url_php.'\'); ">^</span>';
			$htmlbody.= '[<a href="./src/'.$img_filename.'" target="_blank"><img class="zoom" src="./src/'.$img_filename.'" border="1"/></a>]';// 
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
	$pattern="%res=([0-9]+)%";
	preg_match($pattern, $url, $matches_url);//抓首串編號
	$pattern="%page_num=([0-9]+)%";
	preg_match($pattern, $url, $matches_url2);//抓首串頁面編號
	$no=$matches_url[1];//首篇編號
	$no_pg=$matches_url2[1];//頁數
	//
	if($no_pg){
	$logfile=$dir_mth."wsf".$no."_".$no_pg.".htm";//接頭(prefix)接尾(suffix)
	}else{
	$logfile=$dir_mth."wsf".$no.".htm";//接頭(prefix)接尾(suffix)
	}
	//
	$list=file_put_contents($logfile,$output);
	/*
    $cp = fopen($logfile, "a+") or die('failed');// 讀寫模式, 指標於最後, 找不到會嘗試建立檔案
    ftruncate($cp, 0); //砍資料至0
    fputs($cp, $output);
    fclose($cp);
    */
    ////////
    $save_where="存檔=<a href='$logfile'>$logfile</a>\n";
    ////////
}//寫入到檔案/

//一般頁面
echo htmlhead2();
echo form();
$output='';
$output.="<a href='./'>根</a>\n";
$output.="<a href='./$phpself'>返</a>\n";
if(isset($save_where)){$output.=$save_where;}
$output.="<br/>\n";
echo $output;
echo $htmlbody2;
if($cc2 && 0){//打包功能 很吃流量 慎用//0=停用
echo "<br/>\n";
echo "<a href='./zip.php?a1=".$no."&a2=".$img_all."'>zip</a>";
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
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
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
//
			var FFF=0;
			$(document).keydown(function(event){
				if(event.which == 17) {//按下ctrl
					FFF=FFF+1;
					if(FFF >2){
						window.scroll(0, 0);//移動到網頁頂端
					}
				}
			});
//

	}
	//
	/*
	function countdown(count) {      // declare the countdown function.
		(function step(){
			count=count+1;
			alert(count);
			setTimeout(step, 2000); 
		})();
	}
	var FFF=0;
	countdown(FFF);
	*/
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
function htmlhead2(){
$x=<<<EOT
<html><head>
<title>$ymdhis</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css">
body2 { font-family:"細明體",'MingLiU'; }
img.zoom {height:auto; width:auto; max-width:250px; max-height:250px;}
</STYLE>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE" onkeypress="check(event)">

EOT;
$x="\n".$x."\n";
return $x;
}

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