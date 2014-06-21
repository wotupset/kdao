<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
date_default_timezone_set("Asia/Taipei");//時區設定
$time = time();
$ym=date("ym",$time);
$ymdhis=date('_ymd_His_',$time);//輸出的檔案名稱
//
$url_dir=substr($url,0,strrpos($url,"/")+1); //根目錄
//
$htmlbody='';
$htmlbody_output='';
$chat_array=array();
$have_img=0;
//
if($url){
	include('./simple_html_dom.php');//v1.5
	$html = file_get_html($url);//simple_html_dom
	//
		foreach($html->find('img') as $k2 => $v2){//分析
			$have_img++;
			$FFF='';
			//$FFF=$v2->parent->href;
			$FFF=$v2->src;
			if(preg_match("/^http/",$FFF)){
				//$FFF=$FFF;
			}else{
				$FFF=$url_dir."../".$FFF;
			}
			$chat_array['image'][$k2] = $FFF;
			$pic_url=$chat_array['image'][$k2];
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			//
			$htmlbody.='<span style="background-image: url(\''.$pic_url_php.'\'); "><a href="'.$pic_url_php.'">^</a></span>';
			$img_filename=img_filename($pic_url);
			$htmlbody_output.= '<hr/>'.$k2.'[<span class="image"><a href="./src/'.$img_filename.'" target="_blank"><img class="zoom" src="./src/'.$img_filename.'"/></a></span>]'."\n\n";//  border="1"
			if($k2>0){$img_all.=",";}
			$img_all.=$img_filename;
		}
	//print_r($chat_array);exit;
	if($chat_ct && 1){//打包功能 很吃流量 慎用//0=停用
		$htmlbody.="<br/>\n";
		$htmlbody.="<a href='./zip.php?a1=".$time."&a2=".$img_all."'>zip</a>";
	}
	$htmlbody.="<br/>\n";
	$htmlbody.="[$have_img]";
	////寫入到檔案
	$w_chk=1;
	if($w_chk){
		$output='';
		$output.=pack("CCC", 0xef,0xbb,0xbf);//UTF8檔頭
		$output.=htmlhead_output();
		$output.=$url;
		$output.=$htmlbody_output;
		$output.=htmlend();
		//
		$dir_mth="./_".$ym."/"; //存放該月檔案
		$logfile=$dir_mth."all".$time.".htm";//接頭(prefix)接尾(suffix)
		$cp = fopen($logfile, "a+") or die('failed');// 讀寫模式, 指標於最後, 找不到會嘗試建立檔案
		ftruncate($cp, 0); //砍資料至0
		fputs($cp, $output);
		fclose($cp);
		////////
		$logfile_size=filesize($logfile);
		if($logfile_size==0){die("[x]".$logfile."=0");}
		$htmlbody="<br/>存檔=<a href='$logfile'>$logfile</a>".$logfile_size."<br/>\n".$htmlbody;
	}////寫入到檔案/

}else{
	//
}
echo htmlhead();
echo form();
echo "<a href='./'>根</a>\n";
echo "<a href='./$phpself'>返</a>\n";
echo $htmlbody;
echo htmlend();


////
function htmlhead(){
$x=<<<EOT
<html>
<head></heaad>
<body>
EOT;
$x="\n".$x."\n";
return $x;
}
function htmlhead_output(){
$x=<<<EOT
<html>
<head>
<STYLE TYPE="text/css">
body2 { font-family:'Courier New',"細明體",'MingLiU'; }
img.zoom {
height:auto; width:auto; 
min-width:20px; min-height:20px;
max-width:250px; max-height:250px;
border:1px solid blue;
}
</STYLE>
</heaad>
<body>
EOT;
$x="\n".$x."\n";
return $x;
}

function htmlend(){
$x=<<<EOT
</body>
</html>
EOT;
$x="\n".$x."\n";
return $x;
}
function form(){
$phpself=$GLOBALS['phpself'];
$x=<<<EOT
<form id='form140406' action='$phpself' method="get" autocomplete="off">
<input type="text" name="url" size="20" placeholder="url" value=""><br/>
<input type="submit" value="送出"/>
</form>
EOT;

$x="\n".$x."\n";
return $x;
}
//echo form();
function img_filename($x){
	$url=$x;
	$url2=substr($url,0,strrpos($url,"/")+1); //根目錄
	$tmp_str=strlen($url2)-strlen($url);
	$url3=substr($url,$tmp_str);//圖檔檔名
	return $url3;
}
//echo img_filename();
?>