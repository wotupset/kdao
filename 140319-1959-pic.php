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

$url_p=parse_url($url);
//print_r($url_p);
/*
Array
(
    [scheme] => http
    [host] => sage.komica.org
    [path] => /00/src/1406616621815.jpg
)
*/
$url_i=pathinfo($url_p['path']);
//print_r($url_i);
/*
Array
(
    [dirname] => /00/src
    [basename] => 1406616621815.jpg
    [extension] => jpg
    [filename] => 1406616621815
)
*/
//檢查副檔名
$FFF=0;
$allow_ext=array('png', 'jpg', 'gif'); //允許的副檔名//,'jpeg'
foreach($allow_ext as $k => $v){
	if($v == strtolower( $url_i['extension'] ) ){$FFF++;}
}
if($FFF == 0){die('副檔名異常?');}
//檢查主檔名
if( preg_match("/[^\w\.\-]/",$url_i['filename']) ){ //只接受英數底線負號句號
	die('主檔名異常?');
}

//建立資料夾
//if(!is_writeable("./")){ die("根目錄沒有寫入權限，請修改權限"); }
$dir_path="./_".$ym."/"; //存放該月檔案
if(!is_dir($dir_path)){mkdir($dir_path, 0777);}
if(!is_dir($dir_path)){die('建立資料夾失敗');}
$dir_path=$dir_path."src/";//存放圖檔位置
if(!is_dir($dir_path)){mkdir($dir_path, 0777);}
if(!is_dir($dir_path)){die('建立資料夾失敗');}
//
//圖片資料夾的統計php
$img_count=$dir_path."index.php";
if(!is_file($img_count)){copy("img_count.php", $img_count);}
if(!is_file($img_count)){die("複製檔案失敗");}
//
$src=$dir_path.$url_i['filename'].'.'.$url_i['extension'];//圖檔存放的位置
//echo $url;echo $url3;echo $src;exit;
//$src2=$dir_path_src.$time.'-'.$ymd.'.'.$fn_b;//存放檔案的位置
////
if($sss){//單張讀圖
	//刪掉舊檔
	if(is_file($src)){unlink($src);}
	if(is_file($src)){die('[x]is');}
	//
	$content = file_get_contents($url);
	$content = file_put_contents($src,$content);
	//本地檔案內容
	$info_array=getimagesize($src);
	//if(floor($info_array[2]) == 0 ){$chk="0";}//檢查檔案內容是不是圖片
	//本地檔案大小
	$info_filesize=filesize($src);
	$FFF='';
	if($info_filesize >1024){$info_filesize=$info_filesize/1024;$FFF='kb';} //byte -> kb
	if($info_filesize >1024){$info_filesize=$info_filesize/1024;$FFF='mb';} //byte -> kb
	if($info_filesize >1024){$info_filesize=$info_filesize/1024;$FFF='gb';} //byte -> kb
	$info_filesize=number_format($info_filesize,2);
	$info_filesize=$info_filesize.$FFF;
	//本地檔案資訊
	$info_pathinfo=pathinfo($src);
	//
	
	//
	echo "<a href='".$phpself."'>".$phpself."</a>";
	echo "<a href='./'>目</a>";
	echo "<br/>\n";
	echo "<a href='".$src."'>".$src."</a> ";
	echo "<br/>\n".$url."<br/>\n";
	echo "<div><pre>";
	echo print_r($url_p,true);
	echo print_r($info_array,true);
	echo print_r($info_pathinfo,true);
	echo "</pre></div>";
	echo "<br/>\n".$info_filesize."<br/>\n";
	echo "<img src='".$src."'/>";
	exit;
}
////

if(is_file($src)){//圖檔存在
	if($re_get){//是=強制重新下載
		unlink($src);if(is_file($src)){die('刪除檔案失敗(權限?)');}
		$FFF=curl_get($url,$src);
		$chk="2b";//重新下載(紫色)
	}else{//檢查本地檔案內容是不是圖片
		$info_array=getimagesize($src);
		if(floor($info_array[2])==0){
			unlink($src);if(is_file($src)){die('刪除檔案失敗(權限?)');}
			$FFF=curl_get($url,$src);
			$chk="2b";//重新下載(紫色)
		}else{
			$chk="2a";//圖檔存在(藍色)//跳過
		}
	}
}else{//圖檔不存在//新的檔案
	$FFF=curl_get($url,$src);
	if(is_file($src)){$chk='1';}//有建立圖檔
}
//檢查檔案內容是不是圖片
$info_array=getimagesize($src);if(floor($info_array[2]) == 0 ){$chk="0";}//檢查本地檔案內容是不是圖片
//echo print($info_array,true);
//echo $src.'<hr><img src="'.$src.'"/>';exit;
//$info_array=filesize($src);if(floor($info_array)==0){die("沒有內容");}
$img = imageCreate(20,20);
switch($chk){
	case '0'://失敗=0
		$bg_color = imageColorAllocate($img, 255, 0, 0);
	break;
	case '1'://成功=1
		$bg_color = imageColorAllocate($img, 0, 255, 0);
	break;
	case '2a'://圖檔存在//跳過
		$bg_color = imageColorAllocate($img, 0, 0, 255);
	break;
	case '2b'://圖檔存在//重新下載
		$bg_color = imageColorAllocate($img, 255, 0, 255);
	break;
	default:
		//不會執行
		echo "default";
		die('x-chk');
	break;
}
/////////////////
Header("Content-type: image/png");//指定文件類型為PNG
//$img = imageCreate(20,20);
//$bg_color = imageColorAllocate($img, 255, 0, 0);
imageFill($img, 0, 0, $bg_color);
imagePng($img);
imageDestroy($img);

/////////////////
exit;//結束
/////////////////
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
<a href='./'>目</a>
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
function curl_get($url,$src){
	if( function_exists('curl_version') ){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
		$return = array();
		$return = curl_getinfo($ch);//文件狀態
		if( !( $return['CURLINFO_HTTP_CODE'] < 400 ) ){die('CURLINFO_HTTP_CODE');}//狀態錯誤就停止
		$content = curl_exec($ch);
		//print_r($html_get);
		$return = curl_getinfo($ch);
		//print_r($return);
		curl_close($ch);
		//exit;
	}else{
		$content = file_get_contents($url);
	}
	$yn = file_put_contents($src,$content);
	//
	$x='1';
	return $x;
}
?>