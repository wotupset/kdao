<?php
	if(!$url){die('x');}
	////////////
	$html = file_get_html($url);//simple_html_dom
	$chat_array='';
	$chat_array=$html->outertext;
	if(preg_match("/cloudflare/i",$chat_array)){die('[x]cloudflare');}
	//echo print_r($chat_array,true);exit;//�ˬd�I
	$chat_array=array();
	foreach($html->find('div.quote') as $k => $v){//���R
		$vv=$v->parent;
		//��l���e
		$chat_array[$k]['org_text']=$vv->outertext;
		//���D
		foreach($vv->find('span.title') as $k2 => $v2){
			$chat_array[$k]['title'] =$v2->plaintext;
			$v2->outertext="";
		}
		//�W��
		foreach($vv->find('span.name') as $k2 => $v2){
			$chat_array[$k]['name']  =$v2->plaintext;
			$v2->outertext="";
		}
		//�����K��
		$chat_array[$k]['image']=array();
		foreach($vv->find('img.img') as $k2 => $v2){
			$chat_array[$k]['image'][] = $v2->parent->href;
			$v2->parent->outertext="";
		}
		//���e
		foreach($vv->find('div.quote') as $k2 => $v2){
			foreach($v2->find('div.pushpost') as $k3 => $v3){
				$chat_array[$k]['push']  =$v3->innertext;//����
				$v3->outertext="";
			}
			foreach($v2->find('img') as $k3 => $v3){//���夤������?
				$FFF=$v3->src;//
				if(in_array($FFF,$chat_array[$k]['image'])){}else{
					$chat_array[$k]['image'][]=$FFF;
				}
				$v3->outertext="";
			}
			$chat_array[$k]['script']=array();
			foreach($v2->find('script') as $k3 => $v3){
				$FFF=$v3->outertext;
				$pattern="/(\[.*\])/";
				preg_match($pattern, $FFF, $matches);//�쭺��s��
				$FFF = json_decode($matches[1] , true);
				$chat_array[$k]['script'][] = $FFF[0]['title']." ".$FFF[0]['url']; //
				$v3->outertext="";
			}
			//array_unique($chat_array[$k]['image']);//�i�঳���ƹϤ�//����
			$chat_array[$k]['text']  =$v2->innertext;//����
			$v2->outertext="";
		}
		//ID
		foreach($vv->find('span.trip_id') as $k2 => $v2){
			$chat_array[$k]['trip_id']  =$v2->plaintext;
			$v2->outertext="";
		}
		//time
		foreach($vv->find('span.now') as $k2 => $v2){
			$chat_array[$k]['time']  =$v2->children(0)->plaintext;
			$v2->outertext="";
		}
		//no
		foreach($vv->find('span.controls') as $k2 => $v2){
			$chat_array[$k]['no']  =$v2->find('a.qlink',0)->plaintext;
			$v2->outertext="";
		}
		//
		$chat_array[$k]['zzz_text']  =$vv->outertext;
	}
	ksort($chat_array);//�Ƨ�
	//echo print_r($chat_array,true);exit;//�ˬd�I
	$chat_ct=count($chat_array);//�p��
	//�ͦ��������e
	foreach($chat_array as $k => $v){
		$have_text++;//�p��d���ƶq
		$htmlbody.= '<span class="title">'.$chat_array[$k]['title']."</span>"."\n";//���D
		$htmlbody.= '<span class="name">'.$chat_array[$k]['name']."</span>"."\n";//�W��
		$htmlbody.= '<span class="idno">';
		$htmlbody.=$chat_array[$k]['time'];
		$htmlbody.=$chat_array[$k]['trip_id'];
		$htmlbody.=$chat_array[$k]['no'];
		$htmlbody.= '</span>';
		//
		$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$htmlbody.= "<span class='text'><blockquote>".$chat_array[$k]['text']."</blockquote></span>\n";//����
		foreach($chat_array[$k]['script'] as $k2 => $v2){//���夤��flash
			$htmlbody.=$v2;
			$htmlbody.="<br/>\n";
		}
		$chat_array[$k]['push']=strip_tags($chat_array[$k]['push'],"<br>");
		$htmlbody.= "<span class='push'><small>".$chat_array[$k]['push']."</small></span>\n";//����
		//����
		//if($chat_array[$k]['image']){
		foreach($chat_array[$k]['image'] as $k2 => $v2){//���夤���� 
			$have_pic++;//�p��Ϥ��ƶq
			$pic_url=$v2;
			$img_filename=img_filename($pic_url);//�����ɦW
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
	$w_chk=1;//�g�J���ɮ�
	$pre_fix="myk";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%res=([0-9]+)%";
	preg_match($pattern, $url, $matches_url);//�쭺��s��
	$no=$matches_url[1];//���g�s��
	//
	$pattern="%page_num=([0-9]+)%";
	preg_match($pattern, $url, $matches_url2);//�쭺�ꭶ���s��
	$no_pg=ceil($matches_url2[1]);//����
	//
	$pattern="%\/([\w]+)\.mykomica.org\/([\w]+)\/%";
	preg_match($pattern, $url, $matches_sub);//�쭺��s��
	$dm=$matches_sub[1].$matches_sub[2];//���g�s��
	//
?>