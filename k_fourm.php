<?php
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time=time();
$ym=date("ym",$time);
$dir_mth="./_".$ym."/src/"; //存放該月檔案
$query_string=$_SERVER['QUERY_STRING'];
$phplink="http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."";
$phphost=$_SERVER["SERVER_NAME"];
$phpdir="http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
$phpdir=substr($phpdir,0,strrpos($phpdir,"/")+1); //根目錄
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//**********
$FFF_arr=array();
if(is_dir($dir_mth)){
	$url=$dir_mth;
	$handle=opendir($url); 
	$cc = 0;
	while(($file = readdir($handle))!==false) { 
		//echo $file;
		$chk=0;
		if(preg_match("/\.jpg$/i",$file)){$chk=1;}
		if(preg_match("/\.png$/i",$file)){$chk=1;}
		if(preg_match("/\.gif$/i",$file)){$chk=1;}
		if($chk==1){
			$FFF_arr[0][$cc]=$file;
			$FFF_arr[1][$cc]=filectime($url.$file);
		}
		$cc = $cc + 1;
	} 
	closedir($handle); 
}else{
	$FFF_arr[0][0]='x';
	$FFF_arr[1][0]='x';
}
rsort($FFF_arr);
//print_r($FFF_arr);exit;
array_multisort(
$FFF_arr[1], SORT_DESC,SORT_NUMERIC,
$FFF_arr[0]
);

ob_start();
$ct=count($FFF_arr[0]);//攔截到的項目
//echo $ct;
//**********
//檢查是否支援 allow_url_fopen
echo $allow_url_fopen = ini_get('allow_url_fopen');
$ct2=ceil($ct/10);
echo "<a href='./'>目</a>"."\n";
echo "<a href='./".$phpself."'>返</a>"."\n";
echo "<a href='./".$phpself."?a'>01</a>"."\n";
echo "<pre>";
$cc=0;
foreach($FFF_arr[0] as $k => $v ){
	if($cc>300){break;}
	$album_link=$phpdir."k_fourm2.php?".$ym."!".$ct2;//相簿位置(絕對位置)
	$pic_src=$phpdir.$dir_mth.$v;//圖片位置(絕對位置)
	switch($query_string){
		case 'a': //html
			if($cc == 0){
				echo "&lt;a href='".$album_link."'&gt;".$phphost."&lt;/a&gt; &lt;br/&gt;";
				echo "\n";
			}
			echo $k;
			echo "&lt;img src='".$pic_src."'&gt; &lt;br/&gt;";
		break;
		default: //預設
			if($cc == 0){
				echo "[url=".$album_link."]".$phphost."[/url]";
				echo "\n";
			}
			echo $cc;
			echo "[img]".$pic_src."[/img]";
		break;
	}
	echo "\n";
	$cc=$cc+1;
}
echo "</pre>";
echo "<br/>\n";
$htmlbody=ob_get_clean();

echo htmlhead();
echo $htmlbody;
echo htmlend();

function htmlhead(){
$x=<<<EOT
<html><head>
<title>guten morgen</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<META HTTP-EQUIV="EXPIRES" CONTENT="Thu, 15 Jan 2009 05:12:01 GMT">
<META NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
<STYLE TYPE="text/css">
</STYLE>
</head>
<body>
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