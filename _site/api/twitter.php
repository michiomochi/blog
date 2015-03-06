<?php

header('Content-Type: application/json; charset=utf-8');

$url = $_GET['url'];
$api = 'http://urls.api.twitter.com/1/urls/count.json?url=';
$requestUrl = $api . urlencode($url);

$response = file_get_contents($requestUrl);
$response = json_decode($response);
$twitterCount = array();
$twitterCount['url'] = $url;
$twitterCount['count'] = $response->count;
echo json_encode($twitterCount);
