<?php
//
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
$php_info=pathinfo($_SERVER["PHP_SELF"]);//被執行的文件檔名
$php_dir=$php_info['dirname'];
$phpself=$php_info['basename'];
//
date_default_timezone_set("Asia/Taipei");//時區設定
$time = time();//UNIX時間時區設定
$query_string=$_SERVER['QUERY_STRING'];
//
$url=$query_string;
if(strlen($url)==0){$url='http://codepad.org/lvszFXtk/raw.txt';}
//$content = file_get_contents($url);//echo $content;
//
$x=curl_FFF($url);
$getdata=$x_0=$x[0];
$getinfo=$x_1=$x[1];
//
if($phpself == 'curl_getinfo.php'){
	if($getinfo['http_code'] == '200'){
		if(0){
			//echo $getinfo['content_type'];
			header("content-Type: ".$getinfo['content_type'].""); //語言強制
			echo $getdata;
		}else{
			header('content-Type: text/plain; charset=utf-8 '); //語言強制
			echo $getdata;
			//
			echo "\n".print_r($getinfo,true);
			echo "\n".$url;
			echo "\n".$phpself;
		}
		//
	}else{//錯誤時
		echo "\n".print_r($getinfo,true);
	}
}else{//由外部檔案呼叫時的反應
	if($getinfo['http_code'] == '200'){
		if(0){
			//echo $getinfo['content_type'];
			header("content-Type: ".$getinfo['content_type'].""); //語言強制
			echo $getdata;
		}else{
			header('content-Type: text/plain; charset=utf-8 '); //語言強制
			echo $getdata;
			//
			echo "\n".print_r($getinfo,true);
			echo "\n".$url;
			echo "\n".$phpself;
			echo "\n".'外部';
		}
		//
	}else{//錯誤時
		echo "\n".print_r($getinfo,true);
	}
}

//exit;die('http_code');

function curl_FFF($url){
	// Create a curl handle
	$ch = curl_init();
	if(!$ch){die('[x]curl');}
	$ret = curl_setopt($ch, CURLOPT_URL,            $url);
	$ret = curl_setopt($ch, CURLOPT_HEADER,         0);//是否顯示header信息
	$ret = curl_setopt($ch, CURLOPT_NOBODY,         0);//是否隱藏body頁面內容
	$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
	$ret = curl_setopt($ch, CURLOPT_TIMEOUT,        10);//超時
	$ret = curl_setopt($ch, CURLOPT_FAILONERROR,    1);//發生錯誤時不回傳內容
	$ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//跟随重定向页面
	$ret = curl_setopt($ch, CURLOPT_MAXREDIRS,      3);//跟随重定向页面的最大次數
	$ret = curl_setopt($ch, CURLOPT_AUTOREFERER,    1);//重定向页面自动添加 Referer header 
	
	//$ret = curl_setopt($ch, CURLOPT_REFERER,        "http://eden.komica.org/");//自訂來路頁面 用來獲取目標
	//$ret = curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	//
	$getdata  = curl_exec($ch);//抓取URL並把它傳遞給變數
	$getinfo  = curl_getinfo($ch);//結果資訊
	$geterror = curl_errno($ch);
	//
	if($getinfo['redirect_url']){
		$url=$getinfo['redirect_url'];
		$x        = curl_FFF($url);
		$getdata  = $x[0];
		$getinfo  = $x[1];
		$geterror = $x[2];
	}
	//
	/*
	$return = curl_getinfo($ch,CURLINFO_HTTP_CODE);//文件狀態
	echo "\n".'CURLINFO_HTTP_CODE'."\n";print_r($return);
	$return = curl_getinfo($ch,CURLINFO_CONTENT_TYPE);//文件類型
	echo "\n".'CURLINFO_CONTENT_TYPE'."\n";print_r($return);
	$return = curl_getinfo($ch,CURLINFO_TOTAL_TIME);//消耗時間
	echo "\n".'CURLINFO_TOTAL_TIME'."\n";print_r($return);
	$return = curl_getinfo($ch,CURLINFO_CONTENT_LENGTH_DOWNLOAD);//消耗時間
	echo "\n".'CURLINFO_CONTENT_LENGTH_DOWNLOAD'."\n";print_r($return);

	echo "\n".'curl_errno'."\n";print_r($geterror);
	*/
	curl_close($ch);
	//
	$x[0]=$getdata;
	$x[1]=$getinfo;
	$x[2]=$geterror;
	return $x;
}
?> 
