<?php
require 'vendor/autoload.php';
use Alfred\Workflow;

$query = intval($argv[1]);

$ch = curl_init( 'http://api.localhost/v2/restaurant/get_info/'.$query.'/');
$options = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
);

curl_setopt_array( $ch, $options );
$restaurantInfo =  json_decode(curl_exec($ch), true);

// Pass a Bundle ID
$w = new Workflow();
$resultArray = array(
    'uid'          => 'itemuid',
    'arg'          => 'itemarg',
    'valid'        => 'yes',
    'autocomplete' => 'autocomplete',
    'icon'         => 'icon.png',
    'title'        => 'EZTABLE restaurant name',
);

if ($restaurantInfo['status'] == "OK") {
    $resultArray['subtitle'] = $restaurantInfo['data']['name'];
} else {
    $resultArray['subtitle'] = 'Not found!';
}

$w->result($resultArray);
echo $w->toXML();

