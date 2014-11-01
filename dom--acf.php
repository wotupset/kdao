<?php
	if(!$url){die('x');}
	//2chan.net
	////////////
	//$html_get = iconv( "Shift_JIS" , "UTF-8//IGNORE", $html_get);//轉成UTF8
	$html = str_get_html($html_get) or die('沒有收到資料');//simple_html_dom
	//unset($content);
	$chat_array=array();
	$cc=0;
	foreach($html->find('blockquote') as $k => $v){
		$cc++;
		if($k==0){continue;} //首篇先跳過 之後再處理
		$chat_array[$k]['text']=$v->outertext;
		$v->outertext="";
		$vv = $v->parent;
		foreach($vv->find('font') as $k2 => $v2){
			$chat_array[$k]['font'][$k2]=$v2->outertext;
			$v2->outertext="";
		}
		foreach($vv->find('img') as $k2 => $v2){
			$chat_array[$k]['image'][]=$v2->parent->href;
			$v2->parent->outertext="";
		}
		foreach($vv->find('a.r') as $k2 => $v2){
			$chat_array[$k]['no'][$k2]=$v2->outertext;
			$v2->outertext="";
		}
		foreach($vv->find('a') as $k2 => $v2){
			$chat_array[$k]['a'][$k2]=$v2->outertext;
			$v2->outertext="";
		}
		foreach($vv->find('span') as $k2 => $v2){
			$chat_array[$k]['span'][$k2]=$v2->outertext;
			$v2->outertext="";
		}
		$chat_array[$k]['zzz_text']=$vv->outertext;
		$vv->outertext='';
	}
	$html = $html->outertext;
	$html = str_get_html($html);
	//首篇另外處理
	foreach($html->find('blockquote') as $k => $v){
		$cc++;
		$chat_array[$k]['text']=$v->outertext;
		$v->outertext="";
		$vv = $v->parent;
		foreach($vv->find('font') as $k2 => $v2){
			$chat_array[$k]['font'][$k2]=$v2->outertext;
			$v2->outertext="";
		}
		foreach($vv->find('img') as $k2 => $v2){
			$chat_array[$k]['image'][$k2]=$v2->parent->href;
			$v2->parent->outertext="";
		}
		foreach($vv->find('a.r') as $k2 => $v2){
			$chat_array[$k]['no'][$k2]=$v2->outertext;
			$v2->outertext="";
		}
		foreach($vv->find('a') as $k2 => $v2){
			$chat_array[$k]['a'][$k2]=$v2->outertext;
			$v2->outertext="";
		}
		foreach($vv->find('span') as $k2 => $v2){
			$chat_array[$k]['span'][$k2]=$v2->outertext;
			$v2->outertext="";
		}
		$chat_array[$k]['zzz_text']=$vv->outertext;
	}
	if(!$cc){die('找不到blockquote');}
	
	//非首篇//
	ksort($chat_array);//排序
	//exit;//檢查點
	//echo print_r($chat_array,true);exit;//檢查點
	//批次輸出html資料
	foreach($chat_array as $k => $v){
		$have_text++;
		$htmlbody.= '<span class="name">'.strip_tags($chat_array[$k]['font'][1])."</span>"."\n";//內文
		$htmlbody.= '<span class="title">'.strip_tags($chat_array[$k]['font'][0])."</span>"."\n";//內文
		$htmlbody.= '<span class="idno">';
		$htmlbody.=strip_tags($chat_array[$k]['span'][0]);
		$htmlbody.=strip_tags($chat_array[$k]['span'][1]);
		$htmlbody.=strip_tags($chat_array[$k]['no'][1]);
		$htmlbody.= '</span>';
		//
		$FFF=$chat_array[$k]['text'];
		$FFF = str_replace("<br/>","<br>",$FFF);//preg_replace
		$FFF = strip_tags($FFF,"<br>");
		$chat_array[$k]['text']=$FFF;
		$htmlbody.= "<span class='text'><blockquote>".$chat_array[$k]['text']."</blockquote></span>\n";//內文
		//有圖
		if(count($chat_array[$k]['image'])){
			$have_pic++;//計算圖片數量
			$pic_url=$chat_array[$k]['image'][0];
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
	$pre_fix="acf";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%\/([\w]+)/([0-9]+)%";
	preg_match($pattern, $url_p['path'], $matches_url);//抓首串編號
	$dm=$matches_url[1];//首篇編號
	$no=$matches_url[2];//首篇編號
	
	$pattern="%page=([0-9]+)%";
	preg_match($pattern, $url_p['query'], $matches_url2);//抓首串頁面編號
	$no_pg=ceil($matches_url2[1]);//頁數

?>