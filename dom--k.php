<?php
	if(!$url){die('x');}
	//
	$html = file_get_html($url) or die('�S��������');//simple_html_dom
	$chat_array='';
	$chat_array=$html->outertext;
	if(preg_match("/cloudflare/i",$chat_array)){die('[x]cloudflare');}
	//echo print_r($chat_array,true);exit;//�ˬd�I
	//�妸��d��
	$chat_array=array();
	foreach($html->find('blockquote') as $k => $v){
		//���g�t�~�B�z
		if($k == 0 ){
			//XX
		}else{
			$vv=$v->parent;
			//��l���e
			$chat_array[$k]['org_text']=$vv->outertext;
			//���D
			foreach($vv->find('font') as $k2 => $v2){
				if($k2==0){//���D
					$chat_array[$k]['title'] =$v2->plaintext;
					$v2->outertext="";
				}
				if($k2==1){//�W��
					$chat_array[$k]['name'] =$v2->plaintext;
					$v2->outertext="";
				}
			}
			//���e
			foreach($vv->find('blockquote') as $k2 => $v2){
				$chat_array[$k]['text']  =$v2->innertext;//����
				$v2->outertext="";
			}
			//�Ϥ�
			foreach($vv->find('a') as $k2 => $v2){
				foreach($v2->find('img') as $k3 => $v3){
					$chat_array[$k]['image']  =$v3->parent->href;//
				}
				$v2->outertext="";
			}
			//�R����
			foreach($vv->find('a.del') as $k2 => $v2){
				$v2->outertext="";
			}
			//�Ѿl��
			$chat_array[$k]['zzz_text']=$vv->outertext;
			//
			//$chat_array[$k]['time']=strip_tags($chat_array[$k]['zzz_text']);
			preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+ /U",$chat_array[$k]['zzz_text'],$chat_array[$k]['time']);
			$chat_array[$k]['time'] = implode("",$chat_array[$k]['time']);
			//��z�L���M��
			$vv->outertext='';
		}
	}
	//echo print_r($chat_array,true);exit;//�ˬd�I
	//���g�t�~�B�z
	$first_post = $html->find('form',1)->outertext;
	$first_post = str_get_html($first_post);//���s��r��ѪR//��BUG?
	//
	$chat_array[0]['org_text'] = $first_post->outertext;//��l���e
	//
	foreach($first_post->find('font') as $k2 => $v2){
		if($k2==0){//���D
			$chat_array[0]['title'] =$v2->plaintext;
			$v2->outertext="";
		}
		if($k2==1){//�W��
			$chat_array[0]['name'] =$v2->plaintext;
			$v2->outertext="";
		}
	}
	//���e
	foreach($first_post->find('blockquote') as $k2 => $v2){
		$chat_array[0]['text']  =$v2->innertext;//����
		$v2->outertext="";
	}
	//�Ϥ�
	foreach($first_post->find('a') as $k2 => $v2){
		foreach($v2->find('img') as $k3 => $v3){
			$chat_array[0]['image']  =$v3->parent->href;//
		}
		$v2->outertext="";
	}
	//
	$chat_array[0]['zzz_text'] = $first_post->outertext;//�Ѿl�����e//�D�ˬd�I//�U�観�Ψ�
	//
	preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}.*ID.*No\.[0-9]+ /U",$chat_array[0]['zzz_text'],$chat_array[0]['time']);
	$chat_array[0]['time'] = implode("",$chat_array[0]['time']);
	//
	ksort($chat_array);//�Ƨ�
	$chat_ct=count($chat_array);//�p��
	//echo print_r($chat_array,true);exit;//�ˬd�I
	//
	//�妸��Xhtml���
	foreach($chat_array as $k => $v){
		$have_text++;
		$htmlbody.= '<span class="name">'.$chat_array[$k]['name']."</span>"."\n";//����
		$htmlbody.= '<span class="title">'.$chat_array[$k]['title']."</span>"."\n";//����
		$htmlbody.='<span class="idno">'.$chat_array[$k]['time']."</span>"."\n";//����
		$chat_array[$k]['text']=strip_tags($chat_array[$k]['text'],"<br>");
		$htmlbody.= '<span class="text"><blockquote>'.$chat_array[$k]['text']."</blockquote></span>\n";//����
		if($chat_array[$k]['image']){
			$have_pic++;//�p��Ϥ��ƶq
			$pic_url=$chat_array[$k]['image'];
			$img_filename=img_filename($pic_url);//�����ɦW
			if($k >0){$zip_pic.=",";}
			$zip_pic.=$img_filename;
			$htmlbody.= '[<span class="image"><a href="./src/'.$img_filename.'" target="_blank"><img class="zoom" src="./src/'.$img_filename.'"/></a></span>]';//  border="1"
			if($input_b){
				$pic_url_php="./140319-1959-pic.php?url=".$pic_url;
			}else{
				$pic_url_php="./140319-1959-pic.php?".$pic_url;
			}
			//�O�_�ϥκ��iŪ��
			if($input_c){//���ϥ�(�w�])
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
	$pre_fix="k";
	$htmlbody2.= "[$have_pic][$have_text]";//
	//
	$pattern="%res=([0-9]+)%";
	preg_match($pattern, $url, $matches_url);//�쭺��s��
	$no=$matches_url[1];//���g�s��
	//
	$pattern="%\/\/([\w]+)\.[\w\.]+\/([\w]+)\/%";
	preg_match($pattern, $url, $matches_sub);//��������
	$dm=$matches_sub[1].$matches_sub[2];//��������
?>