<?php
// Create a curl handle
$ch = curl_init();
if(!$ch){die('[x]ch');}
$ret = curl_setopt($ch, CURLOPT_URL,            "http://web.komica.org/");
$ret = curl_setopt($ch, CURLOPT_HEADER,         1);//是否顯示header信息
$ret = curl_setopt($ch, CURLOPT_NOBODY,         0);//是否隱藏body頁面內容
$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
$ret = curl_setopt($ch, CURLOPT_TIMEOUT,        10);
//$ret = curl_setopt($ch, CURLOPT_REFERER,        "http://eden.komica.org/");//自訂來路頁面 用來獲取目標
//
//$ret = curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
//
$return = curl_exec($ch);//抓取URL並把它傳遞給瀏覽器
print_r($return);
//

$return = curl_getinfo($ch);
echo "\n".'curl_getinfo'."\n";
print_r($return);
$return = curl_getinfo($ch,CURLINFO_HTTP_CODE);//文件狀態
echo "\n".'CURLINFO_HTTP_CODE'."\n";
print_r($return);
$return = curl_getinfo($ch,CURLINFO_CONTENT_TYPE);//文件類型
echo "\n".'CURLINFO_CONTENT_TYPE'."\n";
print_r($return);
$return = curl_getinfo($ch,CURLINFO_TOTAL_TIME);//消耗時間
echo "\n".'CURLINFO_TOTAL_TIME'."\n";
print_r($return);
$return = curl_getinfo($ch,CURLINFO_CONTENT_LENGTH_DOWNLOAD);//消耗時間
echo "\n".'CURLINFO_CONTENT_LENGTH_DOWNLOAD'."\n";
print_r($return);

$return = curl_errno($ch);
echo "\n".'curl_errno'."\n";
print_r($return);


curl_close($ch);
?>