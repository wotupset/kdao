<?php
	if(!$url){die('x');}
	////////////
	//$html = file_get_html($url) or die('沒有收到資料');//simple_html_dom
	$html = str_get_html($html_get) or die('沒有收到資料');//simple_html_dom
	//迴圈批次處理
	$cc=0;
	foreach($html->find('div.quote') as $k => $v){//分析
		//內文中的影片
		$chat_array[$k]['movie']=array();
		foreach($v->find('embed') as $k2 => $v2){//分析
			$chat_array[$k]['movie'][]=$v2->src;
			$v2->outertext='';
		}
		//$chat_array[$k]['movie'] = implode("<br/>",$chat_array[$k]['movie']);
		//
		foreach($v->find('div.movie_td') as $k2 => $v2){//分析
			$v2->outertext='';
		}
		//
		foreach($v->find('script') as $k2 => $v2){//分析
			$v2->outertext='';
		}
		foreach($v->find('small') as $k2 => $v2){//分析
			$v2->outertext='';
		}
		//
		$chat_array[$k]['movie_pic']=array();
		foreach($v->find('img') as $k2 => $v2){//分析
			$FFF=$v2->style;
			preg_match("/URL\((http.*)\)/U",$FFF,$FFF);
			$chat_array[$k]['movie_pic'][]=$FFF[1];
			$v2->parent->outertext='';
		}
		$chat_array[$k]['text']=$v->outertext;
		$v->outertext='';
		//
		foreach($v->parent->find('span.title') as $k2 => $v2){//分析
			$chat_array[$k]['title']=$v2->plaintext;
			$v2->outertext='';
		}
		foreach($v->parent->find('span.name') as $k2 => $v2){//分析
			$chat_array[$k]['name']=$v2->plaintext;
			$v2->outertext='';
		}
		foreach($v->parent->find('img.img') as $k2 => $v2){
			$chat_array[$k]['image'] = $v2->parent->href;
			$v2->parent->outertext='';
		}
		foreach($v->parent->find('a.qlink') as $k2 => $v2){
			$chat_array[$k]['no'] = $v2->plaintext;
			$v2->outertext='';
		}
		//清掉沒用到的連結
		foreach($v->parent->find('a') as $k2 => $v2){
			$v2->outertext='';
		}
		$chat_array[$k]['zzz_text']=$v->parent->outertext;
		preg_match("/\[[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*\]/U",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
		$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
	}
	ksort($chat_array);//排序
	//echo print_r($chat_array,true);exit;//檢查點
	$chat_ct=count($chat_array);//計數
	//生成網頁內容
	foreach($chat_array as $k => $v){
		$have_text++;//計算留言數量
		$htmlbody.= '<span class="title">'.$chat_array[$k]['title']."</span>"."\n";//內文
		$htmlbody.= '<span class="name">'.$chat_array[$k]['name']."</span>"."\n";//內文
		$htmlbody.= '<span class="idno">';
		$htmlbody.=$chat_array[$k]['time'];
		$htmlbody.=$chat_array[$k]['no'];
		$htmlbody.= '</span>';
		//有影片
		$FFF='';
		if($chat_array[$k]['movie']){
			foreach($chat_array[$k]['movie'] as $k2 => $v2){
				if($k2>0){$FFF.="<br/>";}
				$FFF.='<img src="'.$chat_array[$k]['movie_pic'][$k2].'" height="240"/>'.$chat_array[$k]['movie'][$k2];
			}
		}
		//
		$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$chat_array[$k]['text'].='<br/>'.$FFF;
		$htmlbody.= "<span class='text'><blockquote>".$chat_array[$k]['text']."</blockquote></span>\n";//內文
		//$chat_array[$k]['push']=strip_tags($chat_array[$k]['push'],"<br>");
		//$htmlbody.= "<span class='push'><small>".$chat_array[$k]['push']."</small></span>\n";//推文
		//有圖
		if($chat_array[$k]['image']){
			$have_pic++;//計算圖片數量
			$pic_url=$chat_array[$k]['image'];
			$img_filename=img_filename($pic_url);//圖檔檔名
			$htmlbody.= "<br/>\n";
			$htmlbody.= '[<a href="./src/'.$img_filename.'" target="_blank"><img class="zoom" src="./src/'.$img_filename.'"/></a>]';//  border="1"
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
	$pre_fix="yuc";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%res=([0-9]+)%";
	preg_match($pattern, $url_p['query'], $matches_url);//抓首串編號
	$no=$matches_url[1];//首篇編號
	//
	$pattern="%page_num=([\w]+)%";
	preg_match($pattern, $url_p['query'], $matches);//抓首串編號
	$no_pg=$matches[1];//首篇編號
	//
	$pattern="%\/([\w]+)\/.*%";
	preg_match($pattern, $url_p['path'], $matches_sub);//抓網域辨識
	$dm=$matches_sub[1];//抓網域辨識
?>