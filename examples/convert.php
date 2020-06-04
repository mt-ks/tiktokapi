<?php

require '../vendor/autoload.php';
header("Content-Type: text/plain");

$data = '{"ret":200,"action":"close","msg_type":"success","msg":"Ba\u015far\u0131l\u0131","data":[]}';

$jtc = new \EJM\JsonToClass($data,'Captcha');
foreach ($jtc->createClassBody() as $cs)
{
    print_r($cs);
    echo "\n\n";
};