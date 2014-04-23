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
//$input_b=$_POST['input_b'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
//if($query_string){$url=$query_string;}else{$url=$input_a;}
if(!preg_match('/[0-9]+/',$input_b)){$input_b=0;}
$url=$input_a;
$url=trim($url);
///////////
//允許的網址格式
$kdao_only=0;
if(preg_match("%2chan\.net%U",$url))
{$kdao_only=1;}

///////////
$w_chk=0;
$htmlbody='';
if(!$kdao_only){//只使用於綜合網址
    //die("x");
    //沒事
}else{
	////////////
	$content = file_get_contents($url) or die("[error]file_get_contents");//取得來源內容
	//$content = preg_replace("/\n/","",$content);
	//$content = preg_replace("/\t/","",$content);
	//過濾
	//</small><br><a href="http://svc.2chan.net/dec/18/src/1396892486423.jpg" target="_blank"><img src
	//$pattern="%<a href=\"(http://www.komicdn.com/my/.*/src/[0-9]{13}\.[a-z]{3})\" rel=\"_blank\">%U";
	$pattern="%</small><br><a href=\"(http://[a-z]{3}\.2chan\.net/dec/18/src/[0-9]{13}\.[a-z]{3})%U";
	preg_match_all($pattern, $content, $matches_img);//圖片網址
	//print_r($matches_a);//$matches_c[1][$k][0]
	if(count($matches_img[1])==0){die("x");}//沒找到

	//用迴圈叫出資料
	$cc=0;
	//$dir_mth="./_".date("ym",$time)."/"; //存放該月檔案
	//if(!is_dir($dir_mth)){mkdir($dir_mth, 0777);}
	$dir_path="./_2chan/";
	if(!is_dir($dir_path)){mkdir($dir_path, 0777);}
	$htmlbody.=count($matches_img[1]);
	$htmlbody.="<br/>\n";
	foreach($matches_img[1] as $k => $v){//迴圈
		$pic_url=$v;//圖片網址
		$img_fn=img_filename($v);//圖檔檔名
		$src=$dir_path.$img_fn;//存圖檔的位置
		//$htmlbody.="<a href='".$v."'>".$img_fn."</a>";//連結
		//$htmlbody.="<a href='".$v."'>".$img_fn."</a>";//連結
		$pic_url_php="./140319-1959-2chan-pic.php?".$pic_url;//叫php存下檔案
		$htmlbody.="<span style='background-image: url(\"".$pic_url_php."\"); '><a href='".$src."'>".$img_fn."</a><a href='".$pic_url_php."'>*php</a></span>";
		$htmlbody.="<br/>\n";
		/*
		////跳頁迴圈型
		$multi=10;
		if($cc>=$input_b*$multi && $cc<$input_b*$multi+10){//一次抓10張
			//if(!is_file($src)){$chk=copy($v,$src) or die("[error]copy");}
			$htmlbody.="*".$cc;
			if($cc >= count($matches_a[1])){$loop=0;}else{$loop=1;}
		}
		*/
		//$chk=copy($v,$src) or die("[error]copy");//

		$cc=$cc+1;

	}//迴圈
}//有輸入url/
//修飾

//////

//一般頁面
/*
////跳頁迴圈型
if($loop){
	$input_b=$input_b+1;
	$t_url=$phpself."?input_a=".$input_a."&input_b=".$input_b;
	header("refresh:10; url=$t_url");
}
*/
echo htmlhead();
echo form();
$output='';
$output.="<a href='./'>根</a>\n";
$output.="<a href='./$phpself'>返</a>\n";
$output.="<br/>\n";
echo $output;
echo $htmlbody;//

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
$input_a=$GLOBALS['input_a'];
$input_b=$GLOBALS['input_b'];
$x=<<<EOT
<form enctype="multipart/form-data" action='$phpself' method="get">
綜合網址<input type="text" name="input_a" size="20" value="$input_a">
綜合網址<input type="text" name="input_b" size="20" value="$input_b">
<input type="submit" value=" send ">
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();
?>