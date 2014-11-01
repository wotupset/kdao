<?php
die('die');
header('Content-type: text/plain; charset=utf-8');
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
//**********
$url="./";
$handle=opendir($url); 
$cc = 0;
$img_count=array(); //類型 計數 檔名
$img_count[0][0]='jpg';
$img_count[1][0]='png';
$img_count[2][0]='gif';
$total_size=0;
$FFF_c='';
$log='filemap';
while(($file = readdir($handle))!==false) { 
	if($file=="."||$file == ".."){
		//沒事
	}else{
		if(is_file($file)){//只處理檔案
			//$ext=substr($file,strrpos($file,".")+1); //副檔名
			$ext=pathinfo($file,PATHINFO_EXTENSION);
			if($ext == $img_count[0][0]){ $img_count['jpg']++;$img=1;}//只要圖
			if($ext == $img_count[1][0]){ $img_count['png']++;$img=1;}//只要圖
			if($ext == $img_count[2][0]){ $img_count['gif']++;$img=1;}//只要圖
			if($img==1){$total_size=$total_size+filesize($file);}//只計算圖檔大小
		}
	}
	$cc = $cc + 1;
	//$tmp[$cc] = substr($file,0,strpos($file,"."));
	if($img ==1){
		//
		$FFF_a=ceil(($cc-1)/10); // 0+1 , 1+1,
		$FFF_b=ceil(($cc)/10);
		$FFF_c.='思'.$cc.'墨'.$phpdir.$file."\n";
		//echo $FFF_a." ".$FFF_b."\n";
		if($FFF_a !=0 && $FFF_a != $FFF_b ){
			if(!is_file($log)){
				echo $FFF_a."\n".$FFF_c."\n";
				//file_put_contents($log,$FFF_c);
				$FFF_c='';
			}
		}
		//
	}
} 
closedir($handle); 
?>