<?php
	if(!$url){die('x');}
	////////////
	$html = file_get_html($url);//simple_html_dom
	$array_post=array();
	$cc=0;
	//echo print_r($array_post,true);exit;//�ˬd�I
	//�j��妸�B�z

	foreach($html->find('div.quote') as $k => $v){//���R
		$vv=$v->parent;
		//�h�����ݭn����T
		//$v->parent->find('div.pushpost',0)->outertext="";//�峹������
		$chat_array[$k]['org_text']=$vv->outertext;
		//�k��
		//��
		foreach($vv->find('.img') as $k2 => $v2){
			$chat_array[$k]['image'] .= $v2->parent->href;
			$v2->parent->outertext="";
		}
		//���e
		foreach($vv->find('div.quote') as $k2 => $v2){
			foreach($v2->find('div.pushpost') as $k3 => $v3){
				$chat_array[$k]['push']  =$v3->innertext;//����
				$v3->outertext="";
			}
			$chat_array[$k]['text']  =$v2->innertext;//����
			$v2->outertext="";
		}
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
		//no
		foreach($vv->find('.qlink') as $k2 => $v2){
			$chat_array[$k]['no']    =$v2->plaintext;
			$v2->outertext="";
		}
		foreach($vv->find('a[onclick]') as $k2 => $v2){$v2->outertext="";}
		foreach($vv->find('a[rel]') as $k2 => $v2){$v2->outertext="";}
		//
		$chat_array[$k]['zzz_text']  =$vv->outertext;
		//$chat_array[$k]['time']     = substr(strip_tags($chat_array[$k]['zzz_text']),0,strrpos( strip_tags($chat_array[$k]['zzz_text']) ,"&nbsp;"));//�s��}�C��
		preg_match("/\[[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*\]/U",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
		$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
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
		$chat_array[$k]['push']=strip_tags($chat_array[$k]['push'],"<br>");
		$htmlbody.= "<span class='push'><small>".$chat_array[$k]['push']."</small></span>\n";//����
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
	$htmlbody2.= "[$have_pic][$have_text]";//
	$pre_fix="wsf";
	//
	$pattern="%res=([0-9]+)%";
	preg_match($pattern, $url, $matches_url);//�쭺��s��
	$no=$matches_url[1];//���g�s��
	//
	$pattern="%page_num=([\w]+)%";
	preg_match($pattern, $url, $matches_url2);//�쭺�ꭶ���s��
	$no_pg=ceil($matches_url2[1]);//����
	//
	$pattern="%\/\/([\w]+)\.[\w\.]+\/([\w]+)\/%";
	preg_match($pattern, $url, $matches_sub);//��������
	$dm=$matches_sub[1].$matches_sub[2];//��������
?>