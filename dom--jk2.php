<?php
	if(!$url){die('x');}
	//
	$html = file_get_html($url);//simple_html_dom
	$chat_array='';
	$chat_array=$html->outertext;
	if(preg_match("/cloudflare/i",$chat_array)){die('[x]cloudflare');}
	//echo print_r($chat_array,true);exit;//檢查點
	//批次找留言
	$chat_array=array();
	foreach($html->find('blockquote') as $k => $v){
		$chat_array[$k]['text']=$v->outertext;
		$v->outertext="";
		$vv = $v->parent;
		foreach($vv->find('.s1') as $k2 => $v2){
			$chat_array[$k]['title']=$v2->plaintext;
			$v2->outertext="";
		}
		foreach($vv->find('.s2') as $k2 => $v2){
			$chat_array[$k]['name']=$v2->plaintext;
			$v2->outertext="";
		}
		foreach($vv->find('.del') as $k2 => $v2){
			$v2->outertext="";
		}
		foreach($vv->find('input') as $k2 => $v2){
			$v2->outertext="";
		}
		foreach($vv->find('.s13') as $k2 => $v2){
			$v2->outertext="";
		}
		foreach($vv->find('.s10') as $k2 => $v2){
			$v2->outertext="";
		}
		foreach($vv->find('.s7') as $k2 => $v2){
			$v2->outertext="";
		}
		foreach($vv->find('img') as $k2 => $v2){
			$chat_array[$k]['image']=$v2->parent->href;
			$FFF=$url;
			$FFF=substr($FFF,0,strrpos($FFF,"/")); //根目錄
			$FFF=substr($FFF,0,strrpos($FFF,"/")+1); //根目錄
			$chat_array[$k]['image']=$FFF.$chat_array[$k]['image'];
			$v2->parent->outertext="";
		}
		//
		$chat_array[$k]['zzz_text'] = $v->parent->outertext;
		//
		preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.* ID.* No\.[0-9]+ /U",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
		$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
		//
	}
	//echo print_r($chat_array,true);exit;//檢查點
	//
	ksort($chat_array);//排序
	$chat_ct=count($chat_array);//計數
	//echo print_r($chat_array,true);exit;//檢查點
	//
	//批次輸出html資料
	foreach($chat_array as $k => $v){
		$have_text++;
		$htmlbody.= '<span class="name">'.$chat_array[$k]['name']."</span>"."\n";//內文
		$htmlbody.= '<span class="title">'.$chat_array[$k]['title']."</span>"."\n";//內文
		//$chat_array[$k]['time']=strip_tags($chat_array[$k]['time'],"<br>");
		$htmlbody.='<span class="idno">'.$chat_array[$k]['time']."</span>"."\n";//內文
		//$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$htmlbody.= '<span class="text">'.$chat_array[$k]['text']."</span>\n";//內文
		//有圖
		if($chat_array[$k]['image']){
			$have_pic++;//計算圖片數量
			$pic_url=$chat_array[$k]['image'];
			$img_filename=img_filename($pic_url);//圖檔檔名
			if($k >0){$zip_pic.=",";}
			$zip_pic.=$img_filename;
			$htmlbody.= "<br/>\n";
			$htmlbody.= '[<a href="./src/'.$img_filename.'" target="_blank"><img class="zoom" src="./src/'.$img_filename.'"/></a>]';//  border="1"
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			if($input_c){
				$htmlbody2.=$have_pic.'<img id="pic'.$have_pic.'" src="./index.gif" style="width:5px; height:10px;border:1px solid blue;" /><span id="pn'.$have_pic.'">'.$img_filename."</span><br/>"."\n";
				$htmlbody2_js.="myArray[".$have_pic."]='".$pic_url_php."';\n";
			}else{
				$htmlbody2.='<span style="background-image: url(\''.$pic_url_php.'\'); "><a href="'.$pic_url_php.'">^</a></span>';
				//$htmlbody2.='<img id="pic'.$have_pic.'" src="'.$pic_url_php.'" style="width:5px; height:10px;border:1px solid blue;" />'.$img_filename."<br/>"."\n";
			}
		}
		$htmlbody.="<br>\n";
	}
	////DOM/
	$w_chk=1;//寫入到檔案
	$pre_fix="jk2";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%\/([0-9]+)\.htm%";
	preg_match($pattern, $url, $matches_url);//抓首串編號
	$no=$matches_url[1];//首篇編號
	//echo print_r($matches_url,true);exit;


?>