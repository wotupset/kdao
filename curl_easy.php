<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://imgur.com/vsA2VlZ.gif");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec��������X������e
$FFF=curl_exec($ch);
curl_close($ch);

$return = curl_getinfo($ch);
echo "\n".'curl_getinfo'."\n";
print_r($return);
?>