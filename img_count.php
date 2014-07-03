<?php
header('Content-type: text/html; charset=utf-8');
//**********
$url="./";
$handle=opendir($url); 
$cc = 0;
$img_count=array('jpg'=>0,'png'=>0,'gif'=>0);
while(($file = readdir($handle))!==false) { 
	if($file=="."||$file == ".."){
		//沒事
	}else{
		if(is_dir($file)){
			//沒事
		}else{
			$ext=substr($file,strrpos($file,".")+1); //副檔名
			if($ext == "jpg"){$img_count['jpg']++;}//只要圖
			if($ext == "png"){$img_count['png']++;}//只要圖
			if($ext == "gif"){$img_count['gif']++;}//只要圖
		}
	}
	//$tmp[$cc] = substr($file,0,strpos($file,"."));
	$cc = $cc + 1;
} 
closedir($handle); 
//**********
echo htmlhead();
echo "<pre>";
echo "jpg\t".$img_count['jpg']."\n";
echo "png\t".$img_count['png']."\n";
echo "gif\t".$img_count['gif']."\n";
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