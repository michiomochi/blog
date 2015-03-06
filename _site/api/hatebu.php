<?php

header('Content-Type: application/json; charset=utf-8');

$url = urlencode($_GET['url']);
$api = 'http://api.b.st-hatena.com/entry.count?url=';
$requestUrl = $api . $url;

$response = file_get_contents($requestUrl);
$hatebuCount = array();
$hatebuCount['url'] = $_GET['url'];
if (trim($response) !== '') {
    $hatebuCount['count'] = $response;
} else {
    $hatebuCount['count'] = 0;
}
echo json_encode($hatebuCount);

