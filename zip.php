<?php
header('Content-type: text/html; charset=utf-8');

ob_start();
//**********
$zip = new ZipArchive; //首先实例化这个类
$zip_fn="test.zip";
$res = $zip->open($zip_fn, ZipArchive::CREATE);
if ($res === TRUE) {  //然后查看是否存在test.zip这个压缩包
    $zip->addFile('index.php');
    $zip->addFile('readme.txt'); //将too.php和test.php两个文件添加到test.zip压缩包中
    $zip->close(); //关闭
    echo 'ok';
    echo '<a href="'.$zip_fn.'">'.$zip_fn.'</a>';
} else {
    echo 'failed';
////
        switch($res){
            case ZipArchive::ER_EXISTS: 
                $ErrMsg = "File already exists.";
                break;

            case ZipArchive::ER_INCONS: 
                $ErrMsg = "Zip archive inconsistent.";
                break;
                
            case ZipArchive::ER_MEMORY: 
                $ErrMsg = "Malloc failure.";
                break;
                
            case ZipArchive::ER_NOENT: 
                $ErrMsg = "No such file.";
                break;
                
            case ZipArchive::ER_NOZIP: 
                $ErrMsg = "Not a zip archive.";
                break;
                
            case ZipArchive::ER_OPEN: 
                $ErrMsg = "Can't open file.";
                break;
                
            case ZipArchive::ER_READ: 
                $ErrMsg = "Read error.";
                break;
                
            case ZipArchive::ER_SEEK: 
                $ErrMsg = "Seek error.";
                break;
            
            default: 
                $ErrMsg = "Unknow (Code $rOpen)";
                break;
                
            
        }
////
}
//**********
$htmlbody=ob_get_clean();
echo htmlhead();
echo $htmlbody;
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