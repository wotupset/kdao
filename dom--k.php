<?php
	if(!$url){die('x');}
	//
	//$html = file_get_html($url) or die('沒有收到資料');//simple_html_dom
	$html = str_get_html($html_get) or die('沒有收到資料');//simple_html_dom
	$chat_array='';
	$chat_array = $html->outertext;
	$chat_array=htmlspecialchars($chat_array);//HTML特殊字元
	if(preg_match("/[^\.]cloudflare/i",$chat_array)){print_r($chat_array);die('[x]cloudflare');}
	//
	$cc=0;
	foreach($html->find('blockquote') as $k => $v){
		$cc++;
	}
	if(!$cc){print_r($chat_array);die('[0]沒有找到blockquote');}
	//echo print_r($chat_array,true);exit;//檢查點
	//批次找留言
	$chat_array=array();
	$cc=0;
	foreach($html->find('blockquote') as $k => $v){
		$cc++;
		//首篇另外處理
		if($k == 0 ){
			//XX
		}else{
			$vv=$v->parent;
			//原始內容
			$chat_array[$k]['org_text']=$vv->outertext;
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
			//內容
			foreach($vv->find('blockquote') as $k2 => $v2){
				$chat_array[$k]['text']  =$v2->innertext;//內文
				$v2->outertext="";
			}
			//圖片
			foreach($vv->find('a') as $k2 => $v2){
				foreach($v2->find('img') as $k3 => $v3){
					$chat_array[$k]['image']  =$v3->parent->href;//
				}
				$v2->outertext="";
			}
			//刪除的
			foreach($vv->find('a.del') as $k2 => $v2){
				$v2->outertext="";
			}
			//剩餘的
			$chat_array[$k]['zzz_text']=$vv->outertext;
			//
			//$chat_array[$k]['time']=strip_tags($chat_array[$k]['zzz_text']);
			preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+ /U",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
			$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
			//整理過的清掉
			$vv->outertext='';
		}
	}
	if(!$cc){die('沒有找到blockquote');}
	//echo print_r($chat_array,true);exit;//檢查點
	//首篇另外處理
	$html = $html->find('form',1)->outertext;
	$html = str_get_html($html);//重新轉字串解析//有BUG?
	//$first_post=$html;
	//
	$chat_array[0]['org_text'] = $html->outertext;//原始內容
	//
	foreach($html->find('font') as $k2 => $v2){
		if($k2==0){//標題
			$chat_array[0]['title'] =$v2->plaintext;
			$v2->outertext="";
		}
		if($k2==1){//名稱
			$chat_array[0]['name'] =$v2->plaintext;
			$v2->outertext="";
		}
	}
	//內容
	foreach($html->find('blockquote') as $k2 => $v2){
		$chat_array[0]['text']  =$v2->innertext;//內文
		$v2->outertext="";
	}
	//圖片
	foreach($html->find('a') as $k2 => $v2){
		foreach($v2->find('img') as $k3 => $v3){
			$chat_array[0]['image']  =$v3->parent->href;//
		}
		$v2->outertext="";
	}
	//
	$chat_array[0]['zzz_text'] = $html->outertext;//剩餘的內容//非檢查點//下方有用到
	//
	preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+ /U",$chat_array[0]['zzz_text'],$chat_array[0]['time']);
	$chat_array[0]['time'] = implode("",$chat_array[0]['time']);
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
		$htmlbody.='<span class="idno">'.$chat_array[$k]['time']."</span>"."\n";//內文
		$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$htmlbody.= '<span class="text"><blockquote>'.$chat_array[$k]['text']."</blockquote></span>\n";//內文
		if($chat_array[$k]['image']){
			$have_pic++;//計算圖片數量
			$pic_url=$chat_array[$k]['image'];
			//
$pic_url_p=parse_url($pic_url);
if(preg_match("%\.komica\.org%",$pic_url_p['host'])){ //繞過
	$FFF=explode(".",$pic_url_p['host']); //以...分割
	if(!strstr($FFF[0],"-")){$FFF[0]=$FFF[0]."-cf";}
	$pic_url_p['host']=implode(".",$FFF); //以...分割
	$pic_url=$pic_url_p['scheme'].'://'.$pic_url_p['host'].''.$pic_url_p['path'].'?'.$pic_url_p['query'];
}
//echo $url;
			//
			$img_filename=img_filename($pic_url);//圖檔檔名
			if($k >0){$zip_pic.=",";}
			$zip_pic.=$img_filename;
			$htmlbody.= '[<span class="image"><a href="./src/'.$img_filename.'" target="_blank"><img class="zoom" src="./src/'.$img_filename.'"/></a></span>]';//  border="1"
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			//是否使用漸進讀圖
			if(1){//不使用(預設)
				$htmlbody2.=$have_pic.'<img id="pic'.$have_pic.'" src="./index.gif" style="width:5px; height:10px;border:1px solid blue;" />'.'<span id="pn'.$have_pic.'">'.$img_filename.'</span><br/>'."\n";
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
	$pre_fix="k";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%res=([0-9]+)%";
	preg_match($pattern, $url, $matches_url);//抓首串編號
	$no=$matches_url[1];//首篇編號
	//
	$pattern="%\/\/([\w\-]+)\.[\w\.]+\/([\w]+)\/%";
	//echo $url.'+';exit;
	preg_match($pattern, $url, $matches_sub);//抓網域辨識
	//print_r($matches_sub);exit;
	$dm=$matches_sub[1].$matches_sub[2];//抓網域辨識
	//echo $dm.'+';exit;
?>