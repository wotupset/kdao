<?php
$query_string=$_SERVER['QUERY_STRING'];
 //���}���Ѽ�
if($query_string){
	$url=$query_string;
}else{
	die('x');
}
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
include('./simple_html_dom.php');//v1.5
//
$html_get=$FFF;
//echo $html_get;exit;//�ˬd�I
if(!preg_match("/html/i",substr($html_get,0,500))){die('���OHTML�ɮ�');}
$html = str_get_html($html_get) or die('�S��������');//simple_html_dom
$chat_array=array();
$cc=0;
foreach($html->find('a') as $k => $v){
	$FFF=$v->href;
	if(!preg_match("/(jpg|gif|png)$/i",$FFF) ){continue;}
	if(!in_array($FFF,$chat_array) ){
		$cc++;
		$chat_array[$cc]=$FFF;
	}
}
//
//echo "\n".'curl_getinfo'."\n";
//print_r($return);
//
ksort($chat_array);//�Ƨ�
//echo print_r($chat_array,true);exit;//�ˬd�I
echo "<pre>";
foreach($chat_array as $k => $v){
	$FFF=$v;
	if(!preg_match("/".$url_p_host."/i",$FFF) ){
		$FFF='http://'.$url_p_host.''.$url_i_dirname.'/../'.$FFF;
	}
	echo $FFF."\n";
}
echo "</pre>";
//
?>