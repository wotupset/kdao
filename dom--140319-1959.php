<?php 
//dom ver
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
$url=trim($url);
include('./simple_html_dom.php');//v1.5
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
	//取得來源內容
	//$content = file_get_contents($url) or die("[error]file_get_contents");//取得來源內容
	$html = file_get_html($url);//simple_html_dom
	//echo $html->plaintext;exit;
	$FFF= $html->find('form',1)->outertext;//留言區
	//$html2 = $FFF;//simple_html_dom
	$html2 = str_get_html($FFF);//simple_html_dom
	//批次找留言
	$chat_array=array();
	$FFF=$html2->find('table');
	$FFF_ct=count($FFF);
	//echo $html2->find('table',0)->outertext;//第一個table的內容
	$cc=0;$have_img=0;
	foreach($FFF as $k => $v){
		$cc++;
		if($cc < $FFF_ct){
			$chat_array[$cc]['org_text']= $v->outertext;//存到陣列中//->outertext
			$chat_array[$cc]['title']   = $html2->find('table',$k)->find('font b',0)->innertext;
			$html2->find('table',$k)->find('font',0)->outertext="";
			$chat_array[$cc]['name']    = $html2->find('table',$k)->find('font b',1)->innertext;
			$html2->find('table',$k)->find('font',1)->outertext="";
			$chat_array[$cc]['text']    = $html2->find('table',$k)->find('blockquote',0)->innertext;
			$html2->find('table',$k)->find('blockquote',0)->outertext="";
			$chat_array[$cc]['image']   = $html2->find('table',$k)->find('a',1)->href;
			$html2->find('table',$k)->find('a',1)->outertext="";
			if($chat_array[$cc]['image']){$have_img++;}
			//
			$html2->find('table',$k)->find('td a',0)->outertext="";
			$html2->find('table',$k)->find('td a',0)->outertext="";
			$html2->find('table',$k)->find('input',0)->outertext="";
			//
			$chat_array[$cc]['time']    = $html2->find('table',$k)->find('td',1)->innertext;
			$FFF=$chat_array[$cc]['time'];
			$FFF=substr($FFF,0,strrpos($FFF,"&nbsp;")); //
			$FFF = trim( strip_tags( $FFF ) );//修飾
			$chat_array[$cc]['time']=$FFF;
			//echo $k;//陣列項目的key
			//echo $chat_array[$cc];//內容
			//
			$html2->find('table',$k)->outertext=$k;//清空
			//echo $html2->find('table',$k)->outertext;
		}else{
			$html2->find('table',$k)->outertext="x";//清空
			//break;
		}//continue;
		//echo "<hr/>";
		//echo "\n\n";
	}
	//首篇另外處理
	$FFF ='';
	$FFF = $html2->outertext;//剩餘的資料
	$html3 = str_get_html($FFF);//simple_html_dom
	//
	$chat_array[0]['org_text'] = $html3->outertext;//原始內容
	$chat_array[0]['title']  = $html3->find('font b',0)->innertext;
	$html3->find('font',0)->outertext='';
	$chat_array[0]['name']   = $html3->find('font b',1)->innertext;
	$html3->find('font',1)->outertext='';
	$chat_array[0]['text']   = $html3->find('blockquote',0)->innertext;
	$html3->find('blockquote',0)->outertext="";
	//
	//$chat_array[0]['image']   = $html2->find('a')->href;
	//$chat_array[0]['image'] = print_r($chat_array[0]['image'],true);
	//
	$chat_array[0]['image'] = $html3->find('a img',0);
	if($chat_array[0]['image']){
		$chat_array[0]['image'] = $html3->find('a img',0)->parent()->href;
		$have_img++;
	}
	//清理
	foreach($html3->find('a') as $k => $v){
		$v->outertext='p'.$k;
	}
	$html3->find('input',0)->outertext="";
	$html3->find('small',0)->outertext="";
	//
	$chat_array[0]['time']    = $html3->find('form',0)->outertext;
	//修飾
	$FFF=$chat_array[0]['time'];
	$FFF=substr($FFF,0,strrpos($FFF,"p0 &nbsp;")); //
	$FFF=substr($FFF,strrpos($FFF,"=POST>")+6); //
	$FFF = trim( strip_tags( $FFF ) );//修飾
	$chat_array[0]['time']=$FFF;
	//$chat_array[0]['time']    = $html2->outertext;
	//
	sort($chat_array);//排序
	//echo print_r($chat_array,true);exit;//檢查點
	//批次輸出html資料
	foreach($chat_array as $k => $v){
		$htmlbody.= '<span class="name">'.$chat_array[$k]['name']."</span>"."\n";//內文
		$htmlbody.= '<span class="title">'.$chat_array[$k]['title']."</span>"."\n";//內文
		$htmlbody.=$chat_array[$k]['time'];
		$htmlbody.= "<blockquote>".$chat_array[$k]['text']."</blockquote>\n";//內文
		if($chat_array[$k]['image']){
			$pic_url=$chat_array[$k]['image'];
			$htmlbody.="[<img class='zoom' src='".$pic_url."'>]";
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			$htmlbody2.='<span style="background-image: url(\''.$pic_url_php.'\'); "><a href="'.$pic_url_php.'">^</a></span>';
		}
		$htmlbody.="<br>\n";
	}
	$w_chk=1;
	$htmlbody2.= "[$FFF_ct][$have_img]";
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
    $logfile_size=filesize($logfile);
    if($logfile_size==0){die("[x]".$logfile."=0");}
    $save_where="存檔=<a href='$logfile'>$logfile</a>".$logfile_size."\n";
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

	if(0){
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
	if(0){//???
		function countdown(count) {      // declare the countdown function.
			(function step(){
				count=count+1;
				alert(count);
				setTimeout(step, 2000); 
			})();
		}
		var FFF=0;
		countdown(FFF);
	}
});



//window.onload = function () { }
// onkeypress="check(event)"
</script>

</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
EOT;
/*


*/
$x="\n".$x."\n";
return $x;
}
//echo htmlhead();
function htmlhead2(){
$phphost=$GLOBALS['phphost'];
$x=<<<EOT
<html><head>
<title>$phphost</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css">
body2 { font-family:'Courier New',"細明體",'MingLiU'; }
</STYLE>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">

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