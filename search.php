<?php
require 'vendor/autoload.php';
use Alfred\Workflow;

$query = $argv[1];

$w = new Workflow();
$resultArray = array(
    'uid'          => 'itemuid',
    // 'arg'          => 'itemarg',
    'valid'        => 'yes',
    'autocomplete' => 'autocomplete',
    'icon'         => 'icon.png',
);
$result = array();

if (mb_strlen($query) <= 2) {
    $resultArray['title'] = 'EZTABLE restaurant search';
    $resultArray['subtitle'] = '...';
    $w->result($resultArray);
    echo $w->toXML();
    exit;
}
$ch = curl_init( 'http://api.localhost/v2/search/search_restaurant/2001-01-01/2/?q='.$query);
$options = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
);

curl_setopt_array( $ch, $options );
$restaurantInfo =  json_decode(curl_exec($ch), true);

if ($restaurantInfo['status'] == "OK" && $restaurantInfo['data']['numFound'] > 0) {
    $docs = $restaurantInfo['data']['docs'];
    foreach ($docs as $doc) {
        $resultTemplate = $resultArray;
        $resultTemplate['title'] = $doc['name'];
        $resultTemplate['subtitle'] = $doc['id'];
        $resultTemplate['arg'] = $doc['id'];
        $w->result($resultTemplate);
    }
} else {
    $resultArray['title'] = 'EZTABLE restaurant search';
    $resultArray['subtitle'] = 'Not found!';
    $w->result($resultArray);
}

echo $w->toXML();
