<?php
require_once "../vendor/autoload.php";


$deviceInfo = [
  'iid'       => 'asd',
  'openudid'  => 'dddaaaaaaaaaaaa',
  'device_id' => 'aaa'
];

try{
    $t = new \TikTokAPI\TikTok('mt.ks','qweqwe11',$deviceInfo);
}catch (Exception $e)
{
    print_r($e->getMessage());
}