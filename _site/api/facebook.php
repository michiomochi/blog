<?php

header('Content-Type: application/json; charset=utf-8');

$url = $_GET['url'];
$api = 'http://api.facebook.com/restserver.php?method=links.getStats&urls=';
$requestUrl = $api . urlencode($url);

$response = file_get_contents($requestUrl);
$response = simplexml_load_string($response);
$facebookCount = array();
$facebookCount['url'] = $url;
$facebookCount['count'] = (Int)$response->link_stat->like_count;
echo json_encode($facebookCount);
