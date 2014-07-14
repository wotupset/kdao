<?php
	if(!$url){die('x');}
	////////////
	$html = file_get_html($url);//simple_html_dom
	$array_post=array();
	$cc=0;
	//echo print_r($array_post,true);exit;//檢查點
	//迴圈批次處理

	foreach($html->find('div.quote') as $k => $v){//分析
		$vv=$v->parent;
		//去掉不需要的資訊
		//$v->parent->find('div.pushpost',0)->outertext="";//文章的推文
		$chat_array[$k]['org_text']=$vv->outertext;
		//歸類
		//圖
		foreach($vv->find('.img') as $k2 => $v2){
			$chat_array[$k]['image'] .= $v2->parent->href;
			$v2->parent->outertext="";
		}
		//內容
		foreach($vv->find('div.quote') as $k2 => $v2){
			foreach($v2->find('div.pushpost') as $k3 => $v3){
				$chat_array[$k]['push']  =$v3->innertext;//推文
				$v3->outertext="";
			}
			$chat_array[$k]['text']  =$v2->innertext;//內文
			$v2->outertext="";
		}
		//標題
		foreach($vv->find('span.title') as $k2 => $v2){
			$chat_array[$k]['title'] =$v2->plaintext;
			$v2->outertext="";
		}
		//名稱
		foreach($vv->find('span.name') as $k2 => $v2){
			$chat_array[$k]['name']  =$v2->plaintext;
			$v2->outertext="";
		}
		//no
		foreach($vv->find('.qlink') as $k2 => $v2){
			$chat_array[$k]['no']    =$v2->plaintext;
			$v2->outertext="";
		}
		foreach($vv->find('a[onclick]') as $k2 => $v2){$v2->outertext="";}
		foreach($vv->find('a[rel]') as $k2 => $v2){$v2->outertext="";}
		//
		$chat_array[$k]['zzz_text']  =$vv->outertext;
		//$chat_array[$k]['time']     = substr(strip_tags($chat_array[$k]['zzz_text']),0,strrpos( strip_tags($chat_array[$k]['zzz_text']) ,"&nbsp;"));//存到陣列中
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
		//
		$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$htmlbody.= "<span class='text'><blockquote>".$chat_array[$k]['text']."</blockquote></span>\n";//內文
		$chat_array[$k]['push']=strip_tags($chat_array[$k]['push'],"<br>");
		$htmlbody.= "<span class='push'><small>".$chat_array[$k]['push']."</small></span>\n";//推文
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
				$htmlbody2.='<span style="background-image: url(\''.$pic_url_php.'\'); "><a href="'.$pic_url_php.'">^</a></span>';
				//$htmlbody2.='<img id="pic'.$have_pic.'" src="'.$pic_url_php.'" style="width:5px; height:10px;border:1px solid blue;" />'.$img_filename."<br/>"."\n";
			}else{
				$htmlbody2.=$have_pic.'<img id="pic'.$have_pic.'" src="./index.gif" style="width:5px; height:10px;border:1px solid blue;" /><span id="pn'.$have_pic.'">'.$img_filename."</span><br/>"."\n";
				$htmlbody2_js.="myArray[".$have_pic."]='".$pic_url_php."';\n";
			}
		}
		$htmlbody.="<br>\n";
	}
	////DOM/
	$w_chk=1;//寫入到檔案
	$htmlbody2.= "[$have_pic][$have_text]";//
	$pre_fix="wsf";
	//
	$pattern="%res=([0-9]+)%";
	preg_match($pattern, $url, $matches_url);//抓首串編號
	$no=$matches_url[1];//首篇編號
	//
	$pattern="%page_num=([\w]+)%";
	preg_match($pattern, $url, $matches_url2);//抓首串頁面編號
	$no_pg=ceil($matches_url2[1]);//頁數
	//
	$pattern="%\/\/([\w]+)\.[\w\.]+\/([\w]+)\/%";
	preg_match($pattern, $url, $matches_sub);//抓網域辨識
	$dm=$matches_sub[1].$matches_sub[2];//抓網域辨識
?>