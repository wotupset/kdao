<?php 
error_reporting(E_ALL & ~E_NOTICE); //
date_default_timezone_set("Asia/Taipei");//
$time=time();
$ym=date("ym",$time);
$dir_mth="./_".$ym."/"; //
//
$query_string=$_SERVER['QUERY_STRING'];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//
$FFF=pathinfo($phpself);//主檔名
$phpself_basename=$FFF['filename'];
$log=$phpself_basename.'.log';
if(!preg_match('/^[\w]+$/', $query_string )){die('x');}
//
if($query_string == 'int'){
	//遍歷資料夾
	$url="./";
	$cc = 0;
	$FFF_arr2=array();
	$handle=opendir($url); 
	while(($file = readdir($handle))!==false) { 
		$chk=0;
		if( is_dir($file) && preg_match("/^_([0-9]{4})$/",$file,$match) ){$chk=2;}
		if($chk==2){$FFF_arr2[]=$match[1];}//列出存圖的資料夾
		$cc = $cc + 1;
	} 
	closedir($handle); 
	if( $cc == 0 ){die('x');}//沒資料夾時停止
	//arsort($FFF_arr2);//排序新的在前
	//
	$cc=0;$content='';
	$total_size=0;$all_size=0;
	foreach($FFF_arr2 as $k => $v){
		//echo $v;
		$url="./_".$v."/src/";
		$handle=opendir($url); 
		while(($file = readdir($handle))!==false) { 
			$ufile=$url.$file;
			if($file=='.'||$file == '..'){continue;}
			if(is_dir($ufile)){continue;}//
			$FFF=pathinfo($file);//副檔名
			$ext=$FFF['extension'];
			if($ext == "jpg"){$img_count['jpg']++;$img=1;}//只要圖
			if($ext == "png"){$img_count['png']++;$img=1;}//只要圖
			if($ext == "gif"){$img_count['gif']++;$img=1;}//只要圖
			$FFF=filesize($ufile);
			if($img==1){$total_size=$total_size+$FFF;}//累計圖檔大小
			//
		}
		$total_size=$total_size/1024; //byte -> kb
		$total_size=$total_size/1024; //  kb -> mb
		$total_size=number_format($total_size,2);//取到小數後兩位
		//echo $total_size."\n";
		$all_size=$all_size+$total_size;
		//
		$yy=substr($v,0,2);
		$mm=substr($v,2,2);
		//echo $yy.'+'.$mm."\n";
		//
		$FFF=$total_size.','.$yy.','.$mm.','.'01'.','.'123456';
		echo $FFF."\n";
		$content=$FFF."\n".$content;
		//
		$cc++;
	}
	echo $all_size."\n";
	$yn = file_put_contents($log,$content);
	//
	exit;
}
//**********
if(!is_dir($dir_mth)){die('x');}//沒資料夾時停止
$url=$dir_mth.'src/';
//echo $url."\n";
$cc = 0;
$img_count=array('jpg'=>0,'png'=>0,'gif'=>0);
$total_size=0;
$handle=opendir($url); 
while(($file = readdir($handle))!==false) { 
	$ufile=$url.$file;
	if($file=='.'||$file == '..'){
		//沒事
	}else{
		//只處理檔案
		if(is_dir($ufile)){continue;}//
		//$ext=substr($file,strrpos($file,".")+1); //副檔名
		$FFF=pathinfo($file);//副檔名
		$ext=$FFF['extension'];
		//echo $ext."*";
		if($ext == "jpg"){$img_count['jpg']++;$img=1;}//只要圖
		if($ext == "png"){$img_count['png']++;$img=1;}//只要圖
		if($ext == "gif"){$img_count['gif']++;$img=1;}//只要圖
		$FFF=filesize($ufile);
		//echo $FFF."*";
		if($img==1){$total_size=$total_size+$FFF;}//累計圖檔大小
		//echo $file."\n";
	}
	//$tmp[$cc] = substr($file,0,strpos($file,"."));
	$cc = $cc + 1;//
} 
closedir($handle); 
//
$total_size=$total_size/1024; //byte -> kb
$total_size=$total_size/1024; //  kb -> mb
$total_size2='mb';
$total_size=number_format($total_size,2);//取到小數後兩位
//echo $total_size;exit;
//
//if(!preg_match('/^[0-9]+$/', $query_string )){die('x');}
if(!is_file($log)){//若記錄檔不在 就建立
	$FFF=$total_size.','.date("y",$time).','.date("m",$time).','.date("d",$time).','.date("His",$time);
	$yn = file_put_contents($log,$FFF);
}
$content = file_get_contents($log); //取得log內容
//print_r($content);
//print_r($content);exit;
//$yn = file_put_contents($log,$content);
$content_array=explode("\n",$content);
$array=array();
$line=count($content_array); //行數
$cc=0;
foreach($content_array as $k => $v){
	if($v==''){continue;}//空白行=跳過
	array_push($array, explode(",",$v) ); //解析log
	//array_push($array, str_getcsv($v) );
	//echo $v;//
	$cc++;
}
//echo $cc."\n";
//arsort($array);//排序新的在前
//print_r($array);
//
$addnew='yes';//是否增加新行
foreach($content_array as $k => $v){
	if($array[$k][1] == date("y",$time)){
		if($array[$k][2] == date("m",$time)){ //同月資料只更新不新增
			$key=$k;
			$addnew='no';//是否增加新行
			if($array[$k][3] == date("d",$time)){ //同天資料只更新一次
				//??
			}
		}
	}
}
//echo $addnew."\n";print_r($array[$addnew]);exit;
//

//

if($addnew=='yes'){ //直接新增
	//echo 'yes';exit;
	$FFF=$total_size.','.date("y",$time).','.date("m",$time).','.date("d",$time).','.date("His",$time);
	$content=$FFF."\n".$content;
	$yn = file_put_contents($log,$content);
}else{ //更新資料
	//echo 'no';exit;
	//unset($array[$addnew]);//刪除舊資料
	$FFF=$total_size.','.date("y",$time).','.date("m",$time).','.date("d",$time).','.date("His",$time);
	$array[$key]=explode(",",$FFF);//新資料
	//print_r($array[$key]);exit;
	//$array[0]=str_getcsv($FFF);
	//
	$all_size='';
	foreach($array as $k => $v){
		$all_size=$all_size+$array[$k][0]; //把每個資料夾大小加起來
		$array[$k]=implode(",",$array[$k]);
	}
	$array=implode("\n",$array);
	//$all_size=floor($all_size);//取整數
	$content=$array;
	//
	//var_dump($content);
	//var_dump($all_size);
	//
	$yn = file_put_contents($log,$content);
	//
}
//
$ver='140906.0724d';//版本號
//$ver=md5($time.sha1($ver));
$ver=md5_file($phpself);
$ver_color_r=hexdec( substr($ver,0,2) );//版本號的顏色
$ver_color_g=hexdec( substr($ver,2,2) );//版本號的顏色
$ver_color_b=hexdec( substr($ver,4,2) );//版本號的顏色
//echo $ver_color_r;echo "\n";echo $ver_color_g;echo "\n";echo $ver_color_b;echo "\n";
//
$all_size=sprintf('%01.2f',$all_size); //小數後兩位補零
//
//此區段 要靠左到底
$FFF=<<<EOF
$content
$all_size
EOF;
//此區段 要靠左到底//
//print_r($FFF);exit;
//exit;
Header("Content-type: image/png");//指定文件類型為PNG
$moji=$all_size;
$moji_len=strlen($moji);
$moji_len_px=$moji_len*9;
//$moji=printf("%s",$moji);
$xx=90;
$yy=15;
$img = imagecreatetruecolor($xx,$yy);
$color = imageColorAllocate($img, 255, 255, 255);
imageFill($img, 0, 0, $color);
$color = imageColorAllocate($img, $ver_color_r, $ver_color_g, $ver_color_b);
imagestring($img,5, $xx-$moji_len_px ,0, $moji, $color);
imagePng($img);
imageDestroy($img);
//

?> 
