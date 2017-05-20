<?php
$data = file("http://172.20.128.3:8080/messenger/billing_notification?notification={$_GET['id']}");
$file = explode('=', $data[0]);
$filepath =  trim($file[1]);
echo ($filepath);
