<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://web.komica.org/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
$FFF=curl_exec($ch);
$return = curl_getinfo($ch);
curl_close($ch);

echo "<pre>";

echo "\n".'curl_getinfo'."\n";
print_r($return);

echo "</pre>";
?>