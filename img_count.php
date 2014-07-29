<?php
header('Content-type: text/html; charset=utf-8');
//**********
$url="./";
$handle=opendir($url); 
$cc = 0;
$img_count=array('jpg'=>0,'png'=>0,'gif'=>0);
$total_size=0;
while(($file = readdir($handle))!==false) { 
	if($file=="."||$file == ".."){
		//沒事
	}else{
		if(is_file($file)){//只處理檔案
			//$ext=substr($file,strrpos($file,".")+1); //副檔名
			$ext=pathinfo($file,PATHINFO_EXTENSION);
			if($ext == "jpg"){$img_count['jpg']++;$img=1;}//只要圖
			if($ext == "png"){$img_count['png']++;$img=1;}//只要圖
			if($ext == "gif"){$img_count['gif']++;$img=1;}//只要圖
			if($img==1){$total_size=$total_size+filesize($file);}//只計算圖檔大小
		}
	}
	//$tmp[$cc] = substr($file,0,strpos($file,"."));
	$cc = $cc + 1;
} 
closedir($handle); 
$FFF='';
if($total_size >1024){$total_size=$total_size/1024;$FFF='kb';} //byte -> kb
if($total_size >1024){$total_size=$total_size/1024;$FFF='mb';} //byte -> kb
if($total_size >1024){$total_size=$total_size/1024;$FFF='gb';} //byte -> kb
$total_size=number_format($total_size,2);
$total_size=$total_size.$FFF;
//**********
echo htmlhead();
echo "<pre>";
echo "jpg\t".$img_count['jpg']."\n";
echo "png\t".$img_count['png']."\n";
echo "gif\t".$img_count['gif']."\n";
echo "total\t".$total_size."\n";
echo "</pre>";
echo htmlend();


function htmlhead(){
$x=<<<EOT
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<meta name="Robots" content="index,follow">
<STYLE TYPE="text/css"><!--
body { font-family:'Courier New',"細明體",'MingLiU'; }
--></STYLE>
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

?>