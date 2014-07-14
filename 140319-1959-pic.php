<?php
//echo set_time_limit();
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$query_string=$_SERVER['QUERY_STRING'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ym=date("ym",$time);
$ymd=date("ymd",$time);
//$tmp_arr=explode("!",$query_string);
$url = $_GET["url"];
$sss = $_GET["sss"];
//
if(!$query_string){die(form());}
$re_get=0;
if(strlen($url)){//使用get取得網址
	$re_get=1;
}else{//不是使用get取得網址
	$url=$query_string;
}
//
if(!ignore_user_abort()){ignore_user_abort(true);}//使用者關閉也要繼續跑完
//處理網址
//$url=rawurlencode($url);
$url2=substr($url,0,strrpos($url,"/")+1); //根目錄
$tmp_str=strlen($url2)-strlen($url);
$url3=substr($url,$tmp_str);//圖檔檔名
$fn=$url3;
$fn_a=substr($fn,0,strrpos($fn,".")); //主檔名
if( preg_match("/[^\w\.\-]/",$fn_a) ){die('檔名異常');}
//$fn_a=preg_replace("/_+/","_",$fn_a);//主檔名
//
$fn_b=strrpos($fn,".")+1-strlen($fn);
$fn_b=strtolower(substr($fn,$fn_b)); //副檔名
$url3=$fn_a.".".$fn_b;
//非圖片的副檔名先排除
$FFF=0;
$allow_ext=array('png', 'jpg', 'gif');
foreach($allow_ext as $k => $v){
	if($v == $fn_b){$FFF++;}
}
if($FFF == 0){die('ban');}
//建立資料夾
$dir_path="./_".$ym."/"; //存放該月檔案
if(!is_dir($dir_path)){mkdir($dir_path, 0777);}
$dir_path_src=$dir_path."src/";//存放圖檔位置
if(!is_dir($dir_path_src)){mkdir($dir_path_src, 0777);}
$img_count=$dir_path_src."index.php";
if(!is_file($img_count)){
	$chk=copy("img_count.php", $img_count) or die('[x]img_count.php');//圖片資料夾的統計php
}
$src=$dir_path_src.$url3;//存放檔案的位置
//echo $url;echo $url3;echo $src;exit;
//$src2=$dir_path_src.$time.'-'.$ymd.'.'.$fn_b;//存放檔案的位置
////
if($sss){//單張讀圖
	if(is_file($src)){unlink($src);}
	if(is_file($src)){die('[x]is');}
	//$info_array=getimagesize($url);if(floor($info_array[2]) == 0 ){die('xpic');}//檢查檔案內容是不是圖片
	$content = file_get_contents($url);
	$content = file_put_contents($src,$content);
	echo "<a href='".$phpself."'>".$phpself."</a>";
	echo "<br/>\n";
	echo "<a href='".$src."'>".$src."</a>";
	echo "<br/>\n";
	echo "<img src='".$src."'/>";
	exit;
}
////

if(is_file($src)){//圖檔存在
	if($re_get){//重新下載
		unlink($src);
		//$info_array=getimagesize($url);if(floor($info_array[2]) == 0 ){die('xpic');}//檢查檔案內容是不是圖片
		$content = file_get_contents($url);
		$content = file_put_contents($src,$content);
		$chk="2b";//重新下載(紫色)
	}else{//跳過
		$chk="2a";//圖檔存在(藍色)//跳過
	}
}else{//圖檔不存在//新的檔案
	//$info_array=getimagesize($url);if(floor($info_array[2]) == 0 ){die('xpic');}//檢查檔案內容是不是圖片
	$content = file_get_contents($url);
	$content = file_put_contents($src,$content);
	if(is_file($src)){
		$chk=1;//成功(綠色)
	}else{
		die("寫入失敗");
	}
	
}
//檢查檔案內容是不是圖片
$info_array=getimagesize($src);
if(floor($info_array[2]) == 0 ){
	die("不是圖片");
}
$info_array=filesize($src);
if(floor($info_array) == 0 ){
	die("沒有內容");
}
switch($chk){
	case '0'://失敗=0
		Header("Content-type: image/png");//指定文件類型為PNG
		$moji=date("ymd",$time);
		$moji=sprintf("%06d",$moji);
		$img = imageCreate(90,15);
		$wd_color =imageColorAllocate($img, 255, 0, 0);//紅色
		$bg_color = imageColorAllocate($img, 255, 0, 0);
		imageFill($img, 0, 0, $bg_color);
		imagestring($img,5,0,0, $moji, $wd_color);
		imagePng($img);
		imageDestroy($img);
	break;
	case '1'://成功=1
		Header("Content-type: image/png");//指定文件類型為PNG
		$moji=date("ymd",$time);
		$moji=sprintf("%06d",$moji);
		$img = imageCreate(90,15);
		$wd_color =imageColorAllocate($img, 0, 255, 0);//綠色
		$bg_color = imageColorAllocate($img, 0, 255, 0);
		imageFill($img, 0, 0, $bg_color);
		imagestring($img,5,0,0, $moji, $wd_color);
		imagePng($img);
		imageDestroy($img);
	break;
	case '2a'://圖檔存在//跳過
		Header("Content-type: image/png");//指定文件類型為PNG
		$moji=date("ymd",$time);
		$moji=sprintf("%06d",$moji);
		$img = imageCreate(90,15);
		$wd_color =imageColorAllocate($img, 0, 0, 255);//藍色
		$bg_color = imageColorAllocate($img, 0, 0, 255);
		imageFill($img, 0, 0, $bg_color);
		imagestring($img,5,0,0, $moji, $wd_color);
		imagePng($img);
		imageDestroy($img);
	break;
	case '2b'://重新下載
		Header("Content-type: image/png");//指定文件類型為PNG
		$moji=date("ymd",$time);
		$moji=sprintf("%06d",$moji);
		$img = imageCreate(90,15);
		$wd_color =imageColorAllocate($img, 255, 0, 255);//紫色
		$bg_color = imageColorAllocate($img, 255, 0, 255);
		imageFill($img, 0, 0, $bg_color);
		imagestring($img,5,0,0, $moji, $wd_color);
		imagePng($img);
		imageDestroy($img);
	break;
	default:
		//不會執行
		echo "default";
	break;
}

function form(){
$phpself=$GLOBALS['phpself'];
$url=$GLOBALS['url'];
$x=<<<EOT
<html>
<head></heaad>
<body>
<form id='form140406' action='$phpself' method="get" autocomplete="off">
<input type="text" name="url" size="20" placeholder="url" value=""><br/>
<label>單張讀圖<input type="checkbox" name="sss" value="1" />(測試時使用)</label>
<input type="submit" value="送出"/>
</form>
</body>
</html>
EOT;

$x="\n".$x."\n";
return $x;
}
//echo form();
function strZHcut($str){ //將檔名中的中文去掉
	$len = strlen($str);
	for($i = 0; $i < $len; $i++){
		$char = $str{0};
		if(ord($char) > 127){
			$i++;
			if($i < $len){
				//$arr[] = substr($str, 0, 3);//取0~3字元的字串到陣列
				$arr[] = "_";//取0~3字元的字串到陣列
				$str = substr($str, 3); //取3字元之後的字串
			}
		}else{
			$arr[] = $char;
			$str = substr($str, 1);
		}
	}
	$str=join($arr); //array_reverse?
	return $str;
}
?>