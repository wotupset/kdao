<?php
	if(!$url){die('x');}

	//print_r($html_get);exit;
	//$html_get = iconv( "Shift_JIS" , "UTF-8//IGNORE", $html_get);//轉成UTF8
	$html = str_get_html($html_get) or die('simple_html_dom失敗');//simple_html_dom
	//$html = file_get_html($url) or die('simple_html_dom失敗');//simple_html_dom
	//
	$chat_array='';
	$chat_array = $html->outertext;
	$chat_array=htmlspecialchars($chat_array);//HTML特殊字元
	//
	$cc=0;
	foreach($html->find('blockquote') as $k => $v){
		$cc++;
	}
	//echo $cc;exit;
	if(!$cc){print_r($chat_array);die('[0]沒有找到blockquote');}
	//unset($content);
	$chat_array=array();
	$cc=0;
	foreach($html->find('blockquote') as $k => $v){
		$cc++;
		$chat_array[$k]['text']=$v->outertext;
		$v->outertext='';
		$vv = $v->parent;
		//
		foreach($vv->find('img') as $k2 => $v2){
			$FFF=$v2->alt;
			//
			if(preg_match("/^[0-9]+/U",$FFF)){
				$FFF=$v2->parent->href;
				$FFF="http:".$FFF;
				$chat_array[$k]['image']=$FFF;
				$v2->parent->outertext="";
			}
		}
		foreach($vv->find('span.name') as $k2 => $v2){
			$chat_array[$k]['name']=$v2->plaintext;
			$v2->outertext="";
		}
		foreach($vv->find('span.dateTime') as $k2 => $v2){
			$chat_array[$k]['time']=$v2->plaintext;
			$v2->outertext="";
		}
		foreach($vv->find('span.postNum') as $k2 => $v2){
			$chat_array[$k]['no']=$v2->plaintext;
			$v2->outertext="";
		}
		//
		$chat_array[$k]['zzz_text']=$vv->outertext;
		$vv->outertext='';
	}
	
	if(!$cc){die('找不到blockquote');}
	
	//非首篇//
	ksort($chat_array);//排序
	//exit;//檢查點
	//echo print_r($chat_array,true);exit;//檢查點
	//批次輸出html資料
	foreach($chat_array as $k => $v){
		$FFF_name =$chat_array[$k]['name'];
		$FFF_time =$chat_array[$k]['time'];
		$FFF_no   =$chat_array[$k]['no'];
		$FFF_text =$chat_array[$k]['text'];
		$FFF_pic  =$chat_array[$k]['image'];
		
		//
		$have_text++;
		$htmlbody.= '<span class="name">'.$FFF_name."</span>"."\n";//內文
		//$htmlbody.= '<span class="title">'.strip_tags($chat_array[$k]['font'][0])."</span>"."\n";//內文
		$htmlbody.= '<span class="idno">';
		$htmlbody.= $FFF_time;
		$htmlbody.= $FFF_no;
		$htmlbody.= '</span>';
		//
		$FFF_text = strip_tags($FFF_text,"<br>");
		$htmlbody.= "<span class='text'><blockquote>".$FFF_text."</blockquote></span>\n";//內文
		//有圖
		if($FFF_pic){
			$have_pic++;//計算圖片數量
			$pic_url=$FFF_pic;
			$img_filename=img_filename($pic_url);//圖檔檔名
			$htmlbody.= "<br/>\n";
			$FFF='';
			$FFF='[<a href="./src/'.$img_filename.'" target="_blank"><img class="zoom" src="./src/'.$img_filename.'"/></a>]';//  border="1"
			$htmlbody.= '<span class="pic">'.$FFF.'</span>';
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			if(1){
				$htmlbody2.=$have_pic.'<img id="pic'.$have_pic.'" src="./index.gif" style="width:5px; height:10px;border:1px solid blue;" /><span id="pn'.$have_pic.'">'.$img_filename."</span><br/>"."\n";
				$htmlbody2_js.="myArray[".$have_pic."]='".$pic_url_php."';\n";
			}else{
				//$htmlbody2.='<span style="background-image: url(\''.$pic_url_php.'\'); "><a href="'.$pic_url_php.'">^</a></span>';
				//$htmlbody2.='<img id="pic'.$have_pic.'" src="'.$pic_url_php.'" style="width:5px; height:10px;border:1px solid blue;" />'.$img_filename."<br/>"."\n";
			}
		}
		$htmlbody.="<br>\n";
	}
	////DOM/
	$w_chk=1;//寫入到檔案
	$pre_fix="4c";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%\/([\w]+)\/thread\/([0-9]+)%";
	preg_match($pattern, $url_p['path'], $matches_url);//抓首串編號
	$dm=$matches_url[1];//首篇編號
	$no=$matches_url[2];//首篇編號
?>