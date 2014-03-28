<?php
header("content-Type: text/html; charset=utf-8"); //語言強制
$query_string=$_SERVER['QUERY_STRING'];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$phphost=$_SERVER["SERVER_NAME"];
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time = time();
//$tim = $time.substr(microtime(),2,3);
$tim = microtime(true);
$ver="v131224.1306";
$ver_md5=md5(sha1($ver));//依版本號加密成MD5
$ver_color="#".substr($ver_md5,-6);//版本號的顏色
//**********
$url="./";
$handle=opendir($url); 
$cc = 0;
while(($file = readdir($handle))!==false) { 
	if(1) { 
		$tmp[0][$cc] = $file; 
		if($file=="."||$file == ".."){
			$tmp[1][$cc] = "0";
			$tmp[2][$cc] = "y";//系統功能的資料夾
		}else{
			if(is_dir($file)){
				$tmp[1][$cc] = "0";
				$tmp[2][$cc] = "y";
			}else{
				$tmp[1][$cc] = filesize($file);
				$tmp[2][$cc] = "n";
			}
		}
		//$tmp[$cc] = substr($file,0,strpos($file,"."));
	} 
	$cc = $cc + 1;
} 
closedir($handle); 
//**********
//排序 //rsort($tmp);
$array_lowercase = array_map('strtolower', $tmp[0]);
array_multisort($array_lowercase, SORT_ASC, SORT_STRING, $tmp[0],$tmp[1],$tmp[2]);
//array_multisort($tmp[0],$tmp[2]);
//**********

$httphead = <<<EOT
<html><head>
<title>$phphost</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<META NAME='ROBOTS' CONTENT='noINDEX, noFOLLOW'>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<STYLE TYPE="text/css"><!--
body { 
font-family:"細明體",'MingLiU'; 
background-color:#FFFFEE;
color:#800000;
}
A,A:active,A:link,A:visited {color:#0000EE;}
A:hover  {color:#000080;background-color:#fafad2;}
tr:hover {color:#000080;background-color:#fafad2;}
--></STYLE>
</head>
<body>
EOT;

$httpend = <<<EOT
</body></html>
EOT;


//$u = "http://".$_SERVER["SERVER_NAME"]."".$_SERVER["PHP_SELF"]."";
//echo $u."<br>";
//$url2=substr($u,0,strrpos($u,"/")+1).$url;
//echo $url2."<br>";

$httpbody="";//echo
$date_now=date("y/m/d H:i:s", $time);
$ver_info= <<<EOT
<span style='color:$ver_color;'>$ver</span>
$date_now
$tim
EOT;
$httpbody.="\n";
$line = count($tmp[0]);
if($line>=1000){$line=1000;}else{$line=$line;}
$tmp_str=<<<EOT
<blockquote>
$ver_info
<table style="width: 500px">
<thead>
<tr>
<th style='text-align: right;width: 80px'>size</th>
<th>name</th>
</tr>
</thead>
EOT;
$httpbody.=$tmp_str;
$httpbody.="<tbody>";
for($i = 0; $i < $line; $i++){//從頭
	$tmp_0_i=$tmp[0][$i];
	$tmp_1_i=$tmp[1][$i];
	$tmp_2_i=$tmp[2][$i];
	
	if($tmp[2][$i]=="y"){//是資料夾
$httpbody.=<<<EOT
<tr>
<td style='text-align: right;'>$tmp_1_i</td>
<td><a href='./$tmp_0_i/'>$tmp_0_i</a>◆</td>
</tr>

EOT;
	}else{//不是資料夾
$httpbody.=<<<EOT
<tr>
<td style='text-align: right;'>$tmp_1_i</td>
<td><a href='./$tmp_0_i'>$tmp_0_i</a></td>
</tr>

EOT;
	}
}//
$httpbody.="</tbody></table></blockquote>\n";

$httpbody= "\n".$httpbody."\n";
echo $httphead."\n" ;
echo $httpbody."\n" ;
echo $httpend."\n" ;



?>