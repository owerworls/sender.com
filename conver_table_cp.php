<?php
header("Content-Type: text/html; charset=utf-8");

$link1 = mysqli_connect('172.20.128.22:3306', 'viberchat', 'v1b3r4@t', 'viber');

$link2 = mysqli_connect('172.20.128.22:3306', 'viberchat', 'v1b3r4@t', 'viber');
mysqli_query($link2, "SET NAMES 'utf8'");

$q = mysqli_query($link1, "select * from viberchat_r where 1");
while ($r = mysqli_fetch_array($q)) {
    mysqli_query($link2, "
        insert into viberchat SET
            `event`='{$r['event']}',
            `timestamp`='{$r['timestamp']}',
            `message_token`='{$r['message_token']}',
            `sender_id`='{$r['sender_id']}',
            `sender_name`='{$r['sender_name']}',
            `sender_avatar`='{$r['sender_avatar']}',
            `message_text`='".addslashes($r['message_text'])."',
            `message_type`='{$r['message_type']}',
            `tracking_data`='{$r['tracking_data']}',
            `my_seen`='{$r['my_seen']}',
            `api_key`='{$r['api_key']}'
");
}