<?php

use TikTokAPI\TikTok;

require "../vendor/autoload.php";

$deviceInfo = [
  'iid'       => '',
  'openudid'  => '',
  'device_id' => ''
];

$t = new TikTok('username','password',$deviceInfo);