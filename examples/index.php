<?php
require_once "../vendor/autoload.php";


$deviceInfo = [
    'device_brand' => 'TCL',
    'device_type' => '9027F',
    'openudid' => 'd08546a0831cbd82',
    'device_id' => '6823339987348145669',
    'android_id' => 'd08546a0831cbd82',
    'iid' => '6823340449647740678',
    'useragent' => 'com.zhiliaoapp.musically/2019091803 (Linux; U; Android 10; tr_TR; SM-A505F; Build/QP1A.190711.020; Cronet/58.0.2991.0)'
];

try {
    $t = new \TikTokAPI\TikTok('mt.ks', '123456', $deviceInfo);
    print_r($t->registerDevice());
} catch (Exception $e) {
    print_r($e->getMessage());
    print_r($e->getFile());
    print_r($e->getLine());
}