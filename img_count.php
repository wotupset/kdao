<?php
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);



function form(){
$phpself=$GLOBALS['phpself'];
$x=<<<EOT
<html>
<head></heaad>
<body>
<form id='form140406' action='$phpself' method="get" autocomplete="off">
<input type="text" name="url" size="20" placeholder="url" value=""><br/>
<input type="submit" value="送出"/>
</form>
</body>
</html>
EOT;

$x="\n".$x."\n";
return $x;
}
//echo form();

?>