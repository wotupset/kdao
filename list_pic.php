<?php
$query_string=$_SERVER['QUERY_STRING'];
 //���}���Ѽ�
if($query_string){
	$url=$query_string;
}else{
	die('url=&page=');
}
//
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$url = $_GET["url"];
if(!preg_match("/http/",$url) ){die('���}?');}
$page = $_GET["page"];
$page = ceil($page);
//

$url_p=parse_url($url);
//echo print_r($url_p,true);exit;//�ˬd�I
/*
    [scheme] => http
    [host] => board.futakuro.com
    [path] => /jk2/res/541314.htm
*/
$url_p_host=$url_p['host'];
//
$url_i=pathinfo($url_p['path']);
//echo print_r($url_i,true);exit;//�ˬd�I
/*
    [dirname] => /jk2/res
    [basename] => 541314.htm
    [extension] => htm
    [filename] => 541314
*/
$url_i_dirname=$url_i['dirname'];
//
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec��������X������e
$FFF=curl_exec($ch);
$return = curl_getinfo($ch);
curl_close($ch);
//
//print_r($return);exit;
if($return['http_code']>=400){die('���}���e������~');}
//include('./simple_html_dom.php');//v1.5
//$html = str_get_html($html_get) or die('�S��������');//simple_html_dom

//
$html_get=$FFF;
//echo $html_get;exit;//�ˬd�I
if(!preg_match("/html/i",substr($html_get,0,500))){die('���OHTML�ɮ�');}
//�L�o����
//preg_match_all('/http.{1,30}[0-9]{13}\.(jpg|png|gif)/U',$html_get,$match);
preg_match_all('/http.{1,10}2chan.net\/[a-z]{3}\/[a-z]{1}\/src\/[0-9]{13}\.(jpg|png|gif)/U',$html_get,$match);
//print_r($match);

//
//header('Content-type: text/plain; charset=utf-8');
header('Content-type: text/html; charset=utf-8');
echo $head=<<<EOT
<html>
<head>
</head>
<body>
EOT;

$FFF='';$cc=0;
foreach($match[0] as $k => $v){
	$cc=$cc+1;
	if($cc %100 ==0){$FFF.="<hr/>";}
	echo $FFF='<img src="'.$v.'">'."\n";
	//
	//if($cc > 10){break;}
}
//echo $FFF;
echo $endd=<<<EOT
</body>
</html>
EOT;

?>