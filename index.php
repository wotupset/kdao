<?php
header("content-Type: text/html; charset=utf-8"); //語言強制
$query_string=$_SERVER['QUERY_STRING'];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
$phphost=$_SERVER["SERVER_NAME"];
date_default_timezone_set("Asia/Taipei");//時區設定 Etc/GMT+8
$time = time();
//$tim = $time.substr(microtime(),2,3);
//$tim = microtime(true);
$ver="v140417.1646";
$ver_md5=md5(sha1($ver));//依版本號加密成MD5
$ver_color="#".substr($ver_md5,-6);//版本號的顏色
$md5_file=md5_file("./".$phpself."") or die("[x]md5_file");
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
				$tmp[1][$cc] = filesize($file);//檔案大小
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
<META NAME='ROBOTS' CONTENT='noINDEX, FOLLOW'>
<STYLE TYPE="text/css"><!--
/*
border-width:0px 0px 0px 10px;
border-color:pink;
border-style:solid;
border-left:12px solid green;
position:relative;left:-12px;top:0px;z-index:2;
td {white-space: nowrap; overflow: hidden; }
position:relative;right:0px;top:0px;z-index:2;
border-width:0px 20px 0px 0px;
border-color:pink;
border-style:solid;
*/
body {}
table {
font-family:"細明體",'MingLiU';
font-size:16px;
border-collapse:collapse;
border-spacing:0;
table-layout: fixed;
}
a {text-decoration:none;}
a:hover {text-decoration:underline;}
.td_left {color:#eeaa88;}
tr:hover{background-color:#F0E0D6;
}
tr:hover .span_left {
color:#000000;
border-width:0px 20px 0px 0px;
border-color:pink;
border-style:solid;
}
tr:hover .span2_left {
position:relative;right:-20px;top:0px;z-index:2;
}
tr:hover .span_right {
visibility:visible;
}
.td_left {
overflow: hidden; 
width: 80px;
}
.td_right{
overflow: hidden; 
width: 420px;
}

--></STYLE>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#003333">

EOT;

$httpend = <<<EOT
</body></html>
EOT;


$httpbody="";//echo
$date_now=date("y/m/d H:i:s", $time);
$ver_info= <<<EOT
<blockquote><pre><span style='color:$ver_color;'>$ver</span> $md5_file</pre></blockquote>
EOT;
$httpbody.="\n";
$line = count($tmp[0]);
if($line>=1000){$line=1000;}else{$line=$line;}
$tmp_str=<<<EOT

$ver_info
<table style="width: 500px">
<thead>
<tr>
<th style='text-align: right;width: 80px;'>size</th>
<th style='text-align: left;width: 420px;'>name</th>
</tr>
</thead>
EOT;
$httpbody.=$tmp_str;
$httpbody.="\n<tbody>\n";
for($i = 0; $i < $line; $i++){//從頭
	$tmp_0_i=$tmp[0][$i];
	$tmp_1_i=$tmp[1][$i];
	$tmp_2_i=$tmp[2][$i];
	
	if($tmp[2][$i]=="y"){//是資料夾
		$tmp_0_i_dirmark="◆";
	}else{//不是資料夾
		$tmp_0_i_dirmark="";
	}
$httpbody.=<<<EOT
<tr>
<td class="td_left" style='text-align: right;'><span class="span_left"><span class="span2_left">$tmp_1_i</span></span></td>
<td class="td_right" style='text-align: left;' ><span class="span_right"><a href='./$tmp_0_i'>$tmp_0_i</a>$tmp_0_i_dirmark</span></td>
</tr>

EOT;
}//
$cc2=$cc-2;
$httpbody.="</tbody>\n</table>\n";
$httpbody.="\n<blockquote><pre>$date_now $cc2</pre></blockquote>\n";

$httpbody= "\n".$httpbody."\n";
echo $httphead."\n" ;
echo $httpbody."\n" ;
echo $httpend."\n" ;



?>