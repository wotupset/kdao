<?php
	if(!$url){die('x');}
	////////////
	$html = file_get_html($url);//simple_html_dom
	//�j��妸�B�z
	$cc=0;
	foreach($html->find('div.quote') as $k => $v){//���R
		$chat_array[$k]['text']=$v->outertext;
		$v->outertext="";
		$vv = $v->parent;
		foreach($vv->find('span.title') as $k2 => $v2){
			$chat_array[$k]['title']=$v2->plaintext;
			$v2->outertext="";
		}
		foreach($vv->find('span.name') as $k2 => $v2){
			$chat_array[$k]['name']=$v2->plaintext;
			$v2->outertext="";
		}
		//
		$chat_array[$k]['zzz_text'] = $vv->outertext;
		$FFF=str_get_html($chat_array[$k]['zzz_text']);
		foreach($FFF->find('.qlink') as $k2 => $v2){
			$chat_array[$k]['no']=$v2->plaintext;
			$v2->outertext="";
		}
		foreach($FFF->find('input') as $k2 => $v2){
			$v2->outertext="";
		}
		foreach($FFF->find('img.img') as $k2 => $v2){
			$chat_array[$k]['image']=$v2->parent->href;
			$v2->parent->outertext="";
		}
		foreach($FFF->find('a') as $k2 => $v2){
			$v2->outertext="";
		}
		$chat_array[$k]['zzz_text'] = $FFF->outertext;
		//
		preg_match("/\[[0-9]{2}\/[0-9]{2}\/[0-9]{2}.* ID.*\]/U",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
		$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
		//
	}
	ksort($chat_array);//�Ƨ�
	//echo print_r($chat_array,true);exit;//�ˬd�I
	$chat_ct=count($chat_array);//�p��
	//�ͦ��������e
	foreach($chat_array as $k => $v){
		$have_text++;//�p��d���ƶq
		$htmlbody.= '<span class="title">'.$chat_array[$k]['title']."</span>"."\n";//����
		$htmlbody.= '<span class="name">'.$chat_array[$k]['name']."</span>"."\n";//����
		$htmlbody.= '<span class="idno">';
		$htmlbody.=$chat_array[$k]['time'];
		$htmlbody.=$chat_array[$k]['no'];
		$htmlbody.= '</span>';
		//
		$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$htmlbody.= "<span class='text'><blockquote>".$chat_array[$k]['text']."</blockquote></span>\n";//����
		//����
		if($chat_array[$k]['image']){
			$have_pic++;//�p��Ϥ��ƶq
			$pic_url=$chat_array[$k]['image'];
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
	$pre_fix="naga";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%res=([0-9]+)%";
	preg_match($pattern, $url, $matches_url);//�쭺��s��
	$no=$matches_url[1];//���g�s��
	//echo print_r($matches_url,true);exit;

?>