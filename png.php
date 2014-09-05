<?php 
error_reporting(E_ALL & ~E_NOTICE); //
date_default_timezone_set("Asia/Taipei");//
$time=time();
$ym=date("ym",$time);
$dir_mth="./_".$ym."/"; //
//
$query_string=$_SERVER['QUERY_STRING'];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//
//
//**********
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
$FFF=pathinfo($phpself);//主檔名
$phpself_basename=$FFF['filename'];
//print_r($phpself_basename);exit;
$log=$phpself_basename.'.log';
if(!is_file($log)){
	$FFF=$total_size.','.date("y",$time).','.date("m",$time).','.date("d",$time).','.date("His",$time).','."\n";
	$yn = file_put_contents($log,$FFF);
}
$content = file_get_contents($log);
//print_r($content);
//print_r($content);exit;
//$yn = file_put_contents($log,$content);
$content_array=explode("\n",$content);
$array=array();
$line=count($content_array); //行數
$cc=0;
foreach($content_array as $k => $v){
	if($v==''){continue;}//空白行=跳過
	array_push($array, explode(",",$v) );
	//array_push($array, str_getcsv($v) );
	//echo $v;//
	$cc++;
}
//echo $cc."\n";
//print_r($array);
//
$addnew=1;//是否增加新行
if($array[0][1] == date("y",$time)){
	if($array[0][2] == date("m",$time)){ //同月資料只更新不新增
		$addnew=0;
		if($array[0][3] == date("d",$time)){ //同天資料只更新一次
			//??
		}
	}
}

//

//

if($addnew){
	$FFF=$total_size.','.date("y",$time).','.date("m",$time).','.date("d",$time).','.date("His",$time).','."\n";
	$content=$FFF.$content;
}else{
	$FFF=$total_size.','.date("y",$time).','.date("m",$time).','.date("d",$time).','.date("His",$time).','."\n";
	$array[0]=explode(",",$FFF);
	//$array[0]=str_getcsv($FFF);
}
//
$all_size='';
foreach($array as $k => $v){
	$all_size=$all_size+$array[$k][0];
	$array[$k]=implode(",",$array[$k]);
}
$array=implode("\n",$array);
$all_size=floor($all_size);
$content=$array;
//
$FFF=<<<EOF
$content
$all_size
EOF;
print_r($FFF);
//
$yn = file_put_contents($log,$content);

exit;

Header("Content-type: image/png");//指定文件類型為PNG
$moji=$line;
//$moji=printf("%s",$moji);
$xx=27;
$yy=15;
$img = imageCreate($xx,$yy);
$color = imageColorAllocate($img, 255, 255, 255);
imageFill($img, 0, 0, $color);
$color = imageColorAllocate($img, 0, 0, 0);
imagestring($img,5,0,0, $moji, $color);
imagePng($img);
imageDestroy($img);
//

?> 
