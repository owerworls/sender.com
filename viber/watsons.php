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
                set `event`     = '{$input['event']}',
                `message_token` = '{$input['message_token']}', 
                `sender_id`     = '".$input['sender']['id']."',  
                `message_text`  = '$img".iconv('UTF-8','UTF-8',$input['message']['text'])."',
                `message_type`  = '{$input['message']['type']}',
                `api_key`  = '452c0e93e034951a-e55235953d920284-13ef8f3a9edb2067'
                
                ";

        mysqli_query($link, $sql);
//        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/$ssq.txt',$sql);
        $sql="";

        $userExist=mysqli_query($link, "select sender_id from viberuser where sender_id='".$input['sender']['id']."'");
        if($userExist){
            mysqli_query($link,"
                    update viberuser set message_date=now(), new_message=new_message+1 where sender_id='".$input['sender']['id']."'
                    ");
        }
        else
        {
            mysqli_query($link,"
                    insert into viberuser set 
                        sender_id='".$input['sender']['id']."',  
                        sender_name='".mb_convert_encoding($input['sender']['name'],'UTF-8')."', 
                        sender_avatar='".$input['sender']['avatar']."',
                        new_message=new_message+1
                    ");
        }


        break;
endswitch;

        //fclose($fp); //Закрытие файла
        mysqli_close($link);
