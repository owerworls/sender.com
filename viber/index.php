<?php
ini_set('error_reporting', E_ALL);//E_ALL & ~E_NOTICE
ini_set('display_errors', 1);//Определяет, должны ли ошибки быть выведены на экран как часть вывода или они должны быть скрыты от пользователя.
ini_set('display_startup_errors', 1);
ini_set('date.timezone', 'Europe/Kiev');
header("Content-Type: text/html; charset=utf-8");
$link = mysqli_connect('172.20.128.22:3306', 'viberchat', 'v1b3r4@t', 'viber');

$i = implode(",", $_POST);

$input = json_decode(file_get_contents('php://input'), true);

$fp = fopen('input.log', "a"); // Открываем файл в режиме записи
fwrite($fp, $input . "\n".mb_internal_encoding ($input['sender']['name']). "\n");
fclose($fp); //Закрытие файла

switch ($input['event']):
    case 'message':
        mysqli_query($link, "
                    insert into viberchat
                    set `event`=    '{$input['event']}',
                    `message_token`= '{$input['message_token']}', 
                    `sender_id`=    '{$input['sender']['id']}',
                    `sender_name`=  '".mb_convert_encoding($input['sender']['name'],'UTF-8')."',
                    `sender_avatar`='{$input['sender']['avatar']}',
                    `message_text`= '<img src=\"{$input['media']}\"><br>h".iconv('UTF-8','UTF-8',$input['message']['text'])."',
                    `message_type`= '{$input['message']['type']}'
                    ");
        break;
endswitch;

        mysqli_close($link);
