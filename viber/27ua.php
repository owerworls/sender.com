<?php
include_once ($_SERVER['DOCUMENT_ROOT']."/config.php");


$i = implode(",", $_POST);

$input = json_decode(file_get_contents('php://input'), true);

//$fp = fopen('input.log', "a"); // Открываем файл в режиме записи
//fwrite($fp, file_get_contents('php://input') . "\n".mb_internal_encoding ($input['sender']['name']). "\n");
switch ($input['event']):
    case 'message':
        if(isset($input['message']['media'])){
            $current = file_get_contents($input['message']['media']);
            file_put_contents ('../sharing/pictures/'.$input['message_token'].'.jpg',$current);
            $img="<img src=\"/sharing/pictures/{$input['message_token']}.jpg\" style=\"max-width:100%\"><br>";
        }
        else
            $img='';
        $sql="  insert into viberchat
                set `event`=    '{$input['event']}',
                `message_token`='{$input['message_token']}', 
                `sender_id`=    '{$input['sender']['id']}',
                `sender_name`=  '".mb_convert_encoding($input['sender']['name'],'UTF-8')."',
                `sender_avatar`='{$input['sender']['avatar']}',
                `message_text`= '$img".iconv('UTF-8','UTF-8',$input['message']['text'])."',
                `message_type`= '{$input['message']['type']}',
                `api_key`='453ca5e9e3721c2d-9ad4891ec1fcb088-64923ee05238cfa3'";
        mysqli_query($link, $sql);
        $fp = fopen('27ua.log', "a"); // Открываем файл в режиме записи
        fwrite($fp,$sql); // Запись в файл
        fclose($fp); //Закрытие файла
        break;
endswitch;

        //fclose($fp); //Закрытие файла
        mysqli_close($link);
