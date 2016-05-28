<?php
if($_GET['book']){
	$url = "http://api.douban.com/v2/book/isbn/".$_GET['book'];
	$res = http_get($url);
	
	echo $res;
}

//get请求

function http_get($url){

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // 要求结果为字符串且输出到屏幕上

    curl_setopt($ch, CURLOPT_TIMEOUT, 50);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $output = curl_exec($ch);

    curl_close($ch);



    return $output;

}
?>