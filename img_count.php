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
			if(preg_match('/[0-9]{13}\.jpg/',$file)){$img_count['jpg']++;}
			if(preg_match('/[0-9]{13}\.png/',$file)){$img_count['png']++;}
			if(preg_match('/[0-9]{13}\.gif/',$file)){$img_count['gif']++;}
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
body { font-family:"細明體",'MingLiU'; }
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