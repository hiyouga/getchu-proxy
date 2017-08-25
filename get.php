<?php
header("Content-Type:text/html;charset=UTF-8");
if($_GET['type'] == "info"){
	$html = curl();
	//标题
	preg_match("#style=\"margin:0;\">(.+?)</h1>#is",$html,$title);
	$title = strip_tags($title[1]);
	$title = str_replace("（このタイトルの関連商品）","",$title);
	$title = str_replace("\n","",$title);
	$title = trim($title," ");
	//echo $title;
	//分类
	preg_match("#style=\"padding:1px;\">(.+?)</TABLE>#is",$html,$info1);
	$info1[1] = str_replace("<TD colspan=\"2\" style=\"border-top: 1px #CCC solid;margin:5px;\"></TD>","",$info1[1]);
	$info1[1] = preg_replace("#<style>(.+?)</style>#is","",$info1[1]);
	$info1[1] = preg_replace("#<form(.+?)</form>#is","",$info1[1]);
	//var_dump($info1[1]);exit;
	preg_match_all("#<TD(.+?)</TD>#is",$info1[1],$info2);
	$info = '';
	foreach($info2[0] as $k => $v){
		$v = strip_tags($v);
		$v = str_replace("[一覧]","",$v);
		$v = str_replace("（このブランドの作品一覧）","",$v);
		$v = str_replace("\n","",$v);
		$v = str_replace(" ","",$v);
		if($k % 2 == 0){
			$info .= '<td width="20%">'.$v.'</td>';
		}else{
			$info .= '<td>'.$v.'</td>|';
		}
	}
	$info = trim($info,"|");
	//echo $info;
	//故事
	preg_match("#ストーリー</div>(.+?)<BR clear#is",$html,$story);
	preg_match("#href=\".(.+?).jpg#",$story[1],$storyimg);
	$storyimg = 'http://www.getchu.com'.$storyimg[1].'.jpg';
	$story = strip_tags($story[1]);
	$story = trim($story,"\n");
	//echo $story;
	$content = array("title"=>$title,"info"=>$info,"story"=>$story,"storyimg"=>$storyimg);
	echo json_encode($content,true);
}
if($_GET['type'] == "chara"){
	$id = $_GET['id'];
	$html = curl();
	preg_match_all("#chara-text\">(.+?)</TR>#is",$html,$charat);
	foreach($charat[1] as $k => $v){
		$v = strip_tags($v);
		$v = trim($v,"\n");
		$charatext[$k] = $v;
	}
	$n = count($charatext);
	$chara = '';
	for($i=1;$i<=$n;$i++){
		$chara .= "http://www.getchu.com/brandnew/".$id."/c".$id."chara$i.jpg#".$charatext[$i-1]."|";
	}
	$chara = trim($chara,"|");
	$content = array("chara"=>$chara);
	echo json_encode($content,true);
}
if($_GET['type'] == "cg"){
	$id = $_GET['id'];
	$html = curl();
	preg_match_all("#alt=\"SAMPLE(.+?)\"#",$html,$cgs);
	$n = count($cgs[1]);
	$cg = '';
	for($i=1;$i<=$n;$i++){
		$cg .= "http://www.getchu.com/brandnew/".$id."/c".$id."sample$i.jpg|";
	}
	$cg = trim($cg,"|");
	$content = array("cg"=>$cg);
	echo json_encode($content,true);
}
function curl(){
	$id = $_GET['id'];
	$url = "http://www.getchu.com/soft.phtml?id=".$id;
	$timeout = 10;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0');
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
	curl_setopt($ch,CURLOPT_PROXY,"0.0.0.0"); 
	curl_setopt($ch,CURLOPT_PROXYPORT,2333);
	curl_setopt($ch,CURLOPT_PROXYTYPE,CURLPROXY_HTTP);
	$contents = curl_exec($ch);
	curl_close($ch);
	return mb_convert_encoding($contents,"UTF-8","EUC-JP");
}
