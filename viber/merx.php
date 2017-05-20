<?php
include_once ($_SERVER['DOCUMENT_ROOT']."/config.php");


$i = implode(",", $_POST);

$input = json_decode(file_get_contents('php://input'), true);

/*$fp = fopen('merx.log', "a"); // Открываем файл в режиме записи
$a=implode('.',$input);
fwrite($fp, file_get_contents('php://input') . "\n".mb_internal_encoding ($input['sender']['name']). "\n");
fclose($fp); //Закрытие файла*/

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
                `api_key`='453ca83c5771b10b-7cc31ace8a4b8a88-6305a48fd38956de'";
        mysqli_query($link, $sql);

        break;
endswitch;

        mysqli_close($link);
