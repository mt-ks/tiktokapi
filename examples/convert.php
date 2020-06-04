<?php

require '../vendor/autoload.php';
header("Content-Type: text/plain");

$data = '{"status_code":0,"follow_status":1,"watch_status":1,"extra":{"logid":"202006042020300101890370223C471FD0","now":1591302030000,"fatal_item_ids":[]},"log_pb":{"impr_id":"202006042020300101890370223C471FD0"}}';
$jtc = new \EJM\JsonToClass($data,'FollowResponse');
foreach ($jtc->createClassBody() as $cs)
{
    print_r($cs);
    echo "\n\n";
};