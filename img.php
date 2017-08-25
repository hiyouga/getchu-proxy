<?php
require_once 'config.php';
$url = $_GET['url'];
$ch = curl_init();
$timeout = 60;
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0');
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
if($is_cn){
	curl_setopt($ch,CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
	curl_setopt($ch,CURLOPT_PROXY,$ip);
	curl_setopt($ch,CURLOPT_PROXYPORT,$port);
	curl_setopt($ch,CURLOPT_PROXYTYPE,CURLPROXY_HTTP);
}
header("Content-type:image/jpeg");
echo curl_exec($ch);
curl_close($ch);
