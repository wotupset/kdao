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
$log=$phpself_basename.'.log'; //記錄檔位置
if(!preg_match('/^[\w]+$/', $query_string )){die('x');}
//初始化 會計算全部資料夾的檔案
if($query_string == 'int' ){init($log);}
if(!is_file($log)){init($log);}
//die('初始化成功 寫入log');exit;
if(!is_file($log)){die('log不存在');}
//**********
$url=$dir_mth.'src/'; //本月資料夾
if(is_dir($url)){ //資料夾存在
	$cc = 0;
	$img_count=array('jpg'=>0,'png'=>0,'gif'=>0);
	$total_size=0;
	$handle=opendir($url); 
	while(($file = readdir($handle))!==false) { 
		if($file=='.'||$file == '..'){continue;}
		$ufile=$url.$file;
		if(is_dir($ufile)){continue;}//只處理檔案
		//
		//$ext=substr($file,strrpos($file,".")+1); //副檔名
		$FFF=pathinfo($file);//副檔名
		$ext=$FFF['extension'];
		//echo $ext."*";
		$img=0;
		if($ext == "jpg"){$img_count['jpg']++;$img=1;}//只要圖
		if($ext == "png"){$img_count['png']++;$img=1;}//只要圖
		if($ext == "gif"){$img_count['gif']++;$img=1;}//只要圖
		$FFF=filesize($ufile);
		if($img==1){$total_size=$total_size+$FFF;}//累計圖檔大小
		//
		$cc = $cc + 1;//
	} 
	closedir($handle); 
	//
	$total_size=$total_size/1024; //byte -> kb
	$total_size=$total_size/1024; //  kb -> mb
	//$total_size2='mb';
	$total_size=sprintf('%01.2f',$total_size); //小數後兩位補零
	//$total_size=number_format($total_size,2,'.','');//取到小數後兩位
	//echo $total_size;exit;
}else{//沒資料夾時
	$total_size=0;
}
//echo $url."\n";
//


$content = file_get_contents($log); //取得log內容
$content_array=explode("\n",$content); //以...分割
$line=count($content_array); //行數
$array=array();
$cc=0;
foreach($content_array as $k => $v){
	if($v==''){continue;}//空白行=跳過
	array_push($array, explode("\t",$v) ); //解析log 複製到新陣列
	//array_push($array, str_getcsv($v) ); //舊版本不能用這個函式
	//echo $v;//
	$cc++;
}
//echo $cc."\n";
//print_r($array);exit;
//
$chk=0;$cc=0;
foreach($content_array as $k => $v){
	//echo $cc;
	if( !isset($array[$k][1]) ){continue;}
	if($array[$k][1] == date("y",$time)){ //同年
		if($array[$k][2] == date("m",$time)){ //同月
			unset($array[$k]);//刪除舊資料
			$chk++;
			if($array[$k][3] == date("d",$time)){ //同天資料只更新一次
				//??
			}
		}
	}
	$cc++;
	//echo "\n";
}
//exit;
if($chk ==0){init($log);$array=array();}
//echo $addnew."\n";print_r($array[$addnew]);exit;
//

//
$FFF='';
$FFF=$total_size."\t".date("y",$time)."\t".date("m",$time)."\t".date("d",$time)."\t".date("His",$time);
$array[]=explode("\t",$FFF);//新資料
//
//print_r($array);exit;
$all_size='';
foreach($array as $k => $v){
	$all_size=$all_size+$array[$k][0]; //把每個資料夾大小加起來
	$array[$k]=implode("\t",$array[$k]); //以...組合
}
$array=implode("\n",$array); //以...組合
//$all_size=floor($all_size);//取整數
$content=$array; //新的要寫入的資料

//
//var_dump($content);
//var_dump($all_size);
//寫入
//$yn = file_put_contents($log,$content);
//

//
//$ver='140906.0724d';//版本號
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
$moji_len_px=$moji_len*9; //文字長度
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

exit;



function init($log){
	//遍歷資料夾
	$url="./";
	$cc = 0;
	$FFF_arr2=array();
	$handle=opendir($url); 
	while(($file = readdir($handle))!==false) { 
		if($file=='.'||$file == '..'){continue;}
		$chk=0;
		if( is_dir($file) && preg_match("/^_([0-9]{4})$/",$file,$match) ){$chk=2;}
		if($chk==2){$FFF_arr2[]=$match[1];}//列出存圖的資料夾
		$cc = $cc + 1;
	} 
	closedir($handle); 
	//
	if( count($FFF_arr2) == 0 ){die('沒資料夾');}//沒資料夾時停止
	arsort($FFF_arr2);//排序新的在前
	//
	$cc=0;$content='';
	$total_size=0;//$all_size=0;
	foreach($FFF_arr2 as $k => $v){ //表列所有月份資料夾
		$url="./_".$v."/src/";
		if( !is_dir($url) ){continue;} //若沒有存圖就跳過
		$total_size=0; //歸零
		$handle=opendir($url); 
		while(($file = readdir($handle))!==false) { 
			if($file=='.'||$file == '..'){continue;}
			$ufile=$url.$file;
			if(is_dir($ufile)){continue;}//
			$FFF=pathinfo($file);//副檔名
			$ext=$FFF['extension'];
			$img=0;
			if($ext == "jpg"){$img_count['jpg']++;$img=1;}//只要圖
			if($ext == "png"){$img_count['png']++;$img=1;}//只要圖
			if($ext == "gif"){$img_count['gif']++;$img=1;}//只要圖
			$FFF=filesize($ufile);
			if($img==1){$total_size=$total_size+$FFF;}//累計圖檔大小
			//
		}
		$total_size=$total_size/1024; //byte -> kb
		$total_size=$total_size/1024; //  kb -> mb
		$total_size=sprintf('%01.2f',$total_size); //小數後兩位補零
		//$total_size=number_format($total_size,2);//取到小數後兩位
		//echo $total_size."\n";
		//$all_size=$all_size+$total_size; //$all_size 統一在後面計算
		//
		$yy=substr($v,0,2);
		$mm=substr($v,2,2);
		//echo $yy.'+'.$mm."\n";
		//
		$FFF=$total_size."\t".$yy."\t".$mm."\t".'00'."\t".'123456';
		//echo $FFF."\n";
		$content=$FFF."\n".$content;
		//
		$cc++;
	}
	//echo $all_size."\n";
	//die($content);
	$yn = file_put_contents($log,$content);
	//
	return $content;
}
?> 
