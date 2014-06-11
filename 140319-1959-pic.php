<?php
//echo set_time_limit();
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$query_string=$_SERVER['QUERY_STRING'];
date_default_timezone_set("Asia/Taipei");//時區設定
$time = (string)time();
$ymd=date("ymd",$time);
//$tmp_arr=explode("!",$query_string);
$url = $_GET["url"];
$sss = $_GET["sss"];
//
if(!ignore_user_abort()){ignore_user_abort(true);}
//
$form=<<<EOT
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

////
if(!$query_string){die($form);}
$re_get=0;
if(strlen($url)){//使用get取得網址
	$re_get=1;
}else{//不是使用get取得網址
	$url=$query_string;
}
////
$url2=substr($url,0,strrpos($url,"/")+1); //根目錄
$tmp_str=strlen($url2)-strlen($url);
$url3=substr($url,$tmp_str);//圖檔檔名
$fn=$url3;
$fn_a=substr($fn,0,strrpos($fn,".")); //主檔名
$fn_b=strrpos($fn,".")+1-strlen($fn);
$fn_b=strtolower(substr($fn,$fn_b)); //副檔名
////
$dir_path="./_".date("ym",$time)."/"; //存放該月檔案
if(!is_dir($dir_path)){mkdir($dir_path, 0777);}
$dir_path_src=$dir_path."src/";//存放圖檔位置
if(!is_dir($dir_path_src)){mkdir($dir_path_src, 0777);}
$img_count=$dir_path_src."index.php";
if(!is_file($img_count)){
	$chk=copy("img_count.php", $img_count) or die('[x]img_count.php');
}
$src=$dir_path_src.$url3;//存放檔案的位置
//$src2=$dir_path_src.$time.'-'.$ymd.'.'.$fn_b;//存放檔案的位置
////
if($sss){
$content = file_get_contents($url);
$content = file_put_contents($src,$content);
echo "<a href='".$phpself."'>".$phpself."</a>";
echo "<br/>\n";
echo "<a href='".$src."'>".$src."</a>";
exit;
}
////
if(is_file($src)){//圖檔存在
	if($re_get){//重新下載
		unlink($src);
		$chk=copy($url,$src);// or die("[error]copy")
		$chk="2b";//重新下載
	}else{//跳過
		$chk="2a";//圖檔存在//跳過
	}
}else{//圖檔不存在
	//$chk=copy($url,$src) or die("[error]copy 0");// 
	//成功=1 失敗=0
	$opts = array('http'=>array('method'=>"GET",'timeout'=>10));
	$context = stream_context_create($opts);
	$max_size=5*1024*1024;//抓取上限
	$cc=0;
	while(1){//重次三次
		$content = file_get_contents($url,NULL,$context,0,$max_size);
		if($content === TRUE){break;}
		if($cc>2){break;}
		$cc=$cc+1;
	}
	if($content === FALSE){//
		$chk=0;
	}else{
		$content=file_put_contents($src,$content) or die("[error]file_put_contents");//放置來源內容;
		$chk=1;
	}
}

//$chk=file_put_contents($src,$content) or die("[error]file_put_contents");//放置來源內容;
//header("refresh:0; url=$src");
//connection_aborted()

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
	break;
}

?>