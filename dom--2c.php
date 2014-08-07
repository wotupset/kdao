<?php
	if(!$url){die('x');}
	//2chan.net
	////////////
	//$html = file_get_html($url) or die('沒有收到資料');//simple_html_dom
	//die($html_get);
	$html_get = iconv( "Shift_JIS" , "UTF-8//IGNORE", $html_get);//轉成UTF8
	$html = str_get_html($html_get) or die('沒有收到資料');//simple_html_dom
	//unset($content);
	$chat_array=array();
	foreach($html->find('blockquote') as $k => $v){
		//首篇另外處理
		//非首篇
		if($k == 0 ){
			//$chat_array[$k]['text']=$v->outertext;
		}else{
			$chat_array[$k]['text']=$v->outertext;
			$v->outertext="";
			$vv = $v->parent;
			//
			//標題
			foreach($vv->find('font') as $k2 => $v2){
				if($k2==0){//標題
					$chat_array[$k]['title'] =$v2->plaintext;
					$v2->outertext="";
				}
				if($k2==1){//名稱
					$chat_array[$k]['name'] =$v2->plaintext;
					$v2->outertext="";
				}
			}
			//圖片
			foreach($vv->find('img') as $k2 => $v2){
				$chat_array[$k]['image']  =$v2->parent->href;//
				$v2->parent->outertext="";
			}
			//
			$chat_array[$k]['zzz_text'] = $v->parent->outertext;
			$v->parent->outertext='';
			preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*No\.[0-9]+ /U",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
			$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
		}
	}
	//非首篇//
	//首篇
	foreach($html->find('form') as $k => $v){
		if($k ==1){
			//$chat_array[0]['zzz_text']=$html->outertext;
			$chat_array[0]['org_text']=$v->outertext;
		}
	}
	$html = str_get_html($chat_array[0]['org_text']);//simple_html_dom
	foreach($html->find('iframe') as $k => $v){
		//$chat_array[0]['iframe'][]  =$v->outertext;//
		$v->outertext='';
	}
	foreach($html->find('div') as $k => $v){
		//$chat_array[0]['div'][]  =$v->outertext;//
		$v->outertext='';
	}
	foreach($html->find('table') as $k => $v){
		//$chat_array[0]['table'][]  =$v->outertext;//
		$v->outertext='';
	}
	foreach($html->find('script') as $k => $v){
		//$chat_array[0]['script'][]  =$v->outertext;//
		$v->outertext='';
	}
	foreach($html->find('comment') as $k => $v){
		//$chat_array[0]['comment'][]  =$v->outertext;//
		$v->outertext='';
	}
	//圖片
	foreach($html->find('img') as $k => $v){
		$FFF=$v->parent->href;//
		if(preg_match("/2chan\.net/",$FFF)){
			$chat_array[0]['image']=$FFF;
		}
		$v->parent->outertext='';
	}
	//標題 名稱
	foreach($html->find('font') as $k => $v){
		if($k ==0){$chat_array[0]['title'] =$v->plaintext;}
		if($k ==1){$chat_array[0]['name']  =$v->plaintext;}
		$v->outertext='';
	}
	//內文
	foreach($html->find('blockquote') as $k => $v){
		$chat_array[0]['text']  =$v->outertext;//
		$v->outertext='';
	}
	$chat_array[0]['org_text']='';
	$chat_array[0]['zzz_text']=$html->outertext;
	preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*No\.[0-9]+ /U",$chat_array[0]['zzz_text'],$chat_array[0]['time']);
	$chat_array[0]['time'] = implode("",$chat_array[0]['time']);
	//首篇//
	ksort($chat_array);//排序
	//echo print_r($chat_array,true);exit;//檢查點
	//批次輸出html資料
	foreach($chat_array as $k => $v){
		$have_text++;
		$htmlbody.= '<span class="name">'.$chat_array[$k]['name']."</span>"."\n";//內文
		$htmlbody.= '<span class="title">'.$chat_array[$k]['title']."</span>"."\n";//內文
		$chat_array[$k]['time']=strip_tags($chat_array[$k]['time'],"<br>");
		$htmlbody.= '<span class="idno">';
		$htmlbody.=$chat_array[$k]['time'];
		$htmlbody.= '</span>';
		
		$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$htmlbody.= "<span class='text'><blockquote>".$chat_array[$k]['text']."</blockquote></span>\n";//內文
		//有圖
		if($chat_array[$k]['image']){
			$have_pic++;//計算圖片數量
			$pic_url=$chat_array[$k]['image'];
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
	$pre_fix="2c";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%\/([0-9]+)\.htm%";
	preg_match($pattern, $url, $matches_url);//抓首串編號
	$no=$matches_url[1];//首篇編號
	//
	$pattern="%([\w]+)\.[\w\.]+%";
	preg_match($pattern, $url_p['host'], $matches_sub);//抓網域辨識
	$dm=$matches_sub[1].$matches_sub[2];//抓網域辨識
?>