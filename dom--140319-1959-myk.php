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
include('./simple_html_dom.php');//v1.5
$input_c=!$input_c;//有勾選=1 >反轉=0 >0=漸進
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
	if(!is_dir($dir_mth)){//子資料夾不存在
		//沒事
	}else{//子資料夾存在.
		if(!is_file("index.php")){//如果根目錄沒有index檔案
			die("根目錄遺失index.php");
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
$kdao_only=0;
if(preg_match("%\.mykomica\.org%U",$url))
{$kdao_only=1;}

///////////
$w_chk=0;
$htmlbody='';$htmlbody2='';$htmlbody2_js='';
$have_pic=0;$have_text=0;
if(!$kdao_only){//只使用於綜合網址
    //die("x");
    //沒事
}else{
	////////////
	$html = file_get_html($url);//simple_html_dom
	$chat_array='';
	$chat_array=$html->outertext;
	if(preg_match("/cloudflare/i",$chat_array)){die('[x]cloudflare');}
	//echo print_r($chat_array,true);exit;//檢查點
	$chat_array=array();
	foreach($html->find('div.quote') as $k => $v){//分析
		$vv=$v->parent;
		//原始內容
		$chat_array[$k]['org_text']=$vv->outertext;
		//標題
		foreach($vv->find('span.title') as $k2 => $v2){
			$chat_array[$k]['title'] =$v2->plaintext;
			$v2->outertext="";
		}
		//名稱
		foreach($vv->find('span.name') as $k2 => $v2){
			$chat_array[$k]['name']  =$v2->plaintext;
			$v2->outertext="";
		}
		//版面貼圖
		$chat_array[$k]['image']=array();
		foreach($vv->find('img.img') as $k2 => $v2){
			$chat_array[$k]['image'][] = $v2->parent->href;
			$v2->parent->outertext="";
		}
		//內容
		foreach($vv->find('div.quote') as $k2 => $v2){
			foreach($v2->find('div.pushpost') as $k3 => $v3){
				$chat_array[$k]['push']  =$v3->innertext;//推文
				$v3->outertext="";
			}
			foreach($v2->find('img') as $k3 => $v3){//內文中的附圖?
				$FFF=$v3->src;//
				if(in_array($FFF,$chat_array[$k]['image'])){}else{
					$chat_array[$k]['image'][]=$FFF;
				}
				$v3->outertext="";
			}
			//array_unique($chat_array[$k]['image']);//可能有重複圖片//失敗
			$chat_array[$k]['text']  =$v2->innertext;//內文
			$v2->outertext="";
		}
		//ID
		foreach($vv->find('span.trip_id') as $k2 => $v2){
			$chat_array[$k]['trip_id']  =$v2->plaintext;
			$v2->outertext="";
		}
		//time
		foreach($vv->find('span.now') as $k2 => $v2){
			$chat_array[$k]['time']  =$v2->children(0)->plaintext;
			$v2->outertext="";
		}
		//no
		foreach($vv->find('span.controls') as $k2 => $v2){
			$chat_array[$k]['no']  =$v2->find('a.qlink',0)->plaintext;
			$v2->outertext="";
		}
		//
		$chat_array[$k]['zzz_text']  =$vv->outertext;
	}
	ksort($chat_array);//排序
	//echo print_r($chat_array,true);exit;//檢查點
	$chat_ct=count($chat_array);//計數
	//生成網頁內容
	foreach($chat_array as $k => $v){
		$have_text++;//計算留言數量
		$htmlbody.= '<span class="title">'.$chat_array[$k]['title']."</span>"."\n";//標題
		$htmlbody.= '<span class="name">'.$chat_array[$k]['name']."</span>"."\n";//名稱
		$htmlbody.= '<span class="idno">';
		$htmlbody.=$chat_array[$k]['time'];
		$htmlbody.=$chat_array[$k]['trip_id'];
		$htmlbody.=$chat_array[$k]['no'];
		$htmlbody.= '</span>';
		//
		$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$htmlbody.= "<span class='text'><blockquote>".$chat_array[$k]['text']."</blockquote></span>\n";//內文
		$chat_array[$k]['push']=strip_tags($chat_array[$k]['push'],"<br>");
		$htmlbody.= "<span class='push'><small>".$chat_array[$k]['push']."</small></span>\n";//推文
		//有圖
		//if($chat_array[$k]['image']){
		foreach($chat_array[$k]['image'] as $k => $v){//內文中有圖 
			$have_pic++;//計算圖片數量
			$pic_url=$v;
			$img_filename=img_filename($pic_url);//圖檔檔名
			$htmlbody.= "<br/>\n";
			$htmlbody.= '[<a href="./src/'.$img_filename.'" target="_blank"><img class="zoom" src="./src/'.$img_filename.'"/></a>]';//  border="1"
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			if($input_c){
				$htmlbody2.='<span style="background-image: url(\''.$pic_url_php.'\'); "><a href="'.$pic_url_php.'">^</a></span>';
				//$htmlbody2.='<img id="pic'.$have_pic.'" src="'.$pic_url_php.'" style="width:5px; height:10px;border:1px solid blue;" />'.$img_filename."<br/>"."\n";
			}else{
				$htmlbody2.=$have_pic.'<img id="pic'.$have_pic.'" src="./index.gif" style="width:5px; height:10px;border:1px solid blue;" /><span id="pn'.$have_pic.'">'.$img_filename."</span><br/>"."\n";
				$htmlbody2_js.="myArray[".$have_pic."]='".$pic_url_php."';\n";
			}
		}
		$htmlbody.="<br>\n";
	}

	$w_chk=1;//寫入到檔案
	$htmlbody2.= "[$have_pic][$have_text]";//
}//有輸入url/
//修飾

//////
$htmlbody=$url."<br/>\n".$htmlbody."<br>\n<br>\n";
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
	$no=$matches_url[1];//首篇編號
	//
	$pattern="%page_num=([0-9]+)%";
	preg_match($pattern, $url, $matches_url2);//抓首串頁面編號
	$no_pg=$matches_url2[1];//頁數
	$no_pg=ceil($no_pg);
	//
	$pattern="%\/([\w]+)\.mykomica.org\/([\w]+)\/%";
	preg_match($pattern, $url, $matches_sub);//抓首串編號
	$sub=$matches_sub[1].$matches_sub[2];//首篇編號
	//
	$logfile=$dir_mth."myk_".$sub."_".$no.'_'.$no_pg.".htm";//接頭(prefix)接尾(suffix)
	$cp = fopen($logfile, "a+") or die('failed');// 讀寫模式, 指標於最後, 找不到會嘗試建立檔案
	ftruncate($cp, 0); //砍資料至0
	fputs($cp, $output);
	fclose($cp);
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
if(isset($save_where)){
	$output.=$save_where;
	$output.=$url.'<br/>'."\n";
	if($have_pic){
		if($input_c){
			//快速
		}else{
			$output.=js_timedown();//
			$htmlbody2_js="\n\n<script>var myArray=[];\n".$htmlbody2_js."</script>\n\n";
			echo $htmlbody2_js;
		}
	}
}
$output.="\n";
echo $output;
echo $htmlbody2;//

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
	$url3=substr($url,$tmp_str);//圖檔全部檔名
	//$fn=$url3;
	//$fn_a=substr($fn,0,strrpos($fn,".")); //主檔名
	//$fn_b=substr($fn,strrpos($fn,".")+1-strlen($fn)); //副檔名
	//$fn_a=preg_replace("/[^\w]/","_",$fn_a);
	//$fn_a=preg_replace("/_+/","_",$fn_a);
	//$url3=$fn_a.".".$fn_b;
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
body2 { font-family:'Courier New',"細明體",'MingLiU'; }
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
body2 { font-family:'Courier New',"細明體",'MingLiU'; }
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
網址<input type="text" name="input_a" size="20" value=""><input type="submit" value=" send "><br/>
<label>重新讀圖<input type="checkbox" name="input_b" value="1" />(破圖時使用)</label><br/>
<label>漸進讀圖<input type="checkbox" name="input_c" value="1" />(主機不穩時使用)</label><br/>
</form>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo form();

function js_timedown(){
$have_pic=$GLOBALS['have_pic'];
$x=<<<EOT
<script>
$(document).ready(function() {
	timedown();
});
function timedown(){
	var t=0;
	document.getElementById("timedown_span").innerHTML=t;
	var timedown = setInterval(function() {
		t=t+1;
		document.getElementById("timedown_span").innerHTML="("+t+"/$have_pic)..."+myArray[t];
		document.getElementById("pic"+t).src=myArray[t];
		document.getElementById("pn"+t).style.color = "#00ff00";
		if(t<$have_pic){
			timedown;
		}else{
			document.getElementById("timedown_span").innerHTML="沒了";
			document.getElementById("timedown_div").style.backgroundColor="#00ff00";
			clearInterval(timedown);
		}
	}, 500);
}
</script>
<div id='timedown_div'><span id='timedown_span'></span></div>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo js_timedown();

?>