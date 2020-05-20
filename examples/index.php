<?php
require_once "../vendor/autoload.php";


$deviceInfo = [
  'iid'       => '',
  'openudid'  => '',
  'device_id' => ''
];


$t = new \TikTokAPI\TikTok('username','password',$deviceInfo);
print_r($t->login());
