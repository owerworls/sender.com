<?php

/**
 * Created by PhpStorm.
 * User: I
 * Date: 01.04.17
 * Time: 11:35
 */
class Chat
{
    private $link;

    function __construct($link)
    {
        $this->link = $link;
    }

    function sendMessage()
    {
        file_put_contents('ddeeeeddd',$_POST['text']);
        if ($_POST['text'] == '') return;
        $ch1 = curl_init('https://chatapi.viber.com/pa/send_message');
        curl_setopt($ch1, CURLOPT_HEADER, 0);//выводить заголовки
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
        curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");

        curl_setopt($ch1, CURLOPT_POSTFIELDS, "{
           \"auth_token\": \"" . $_COOKIE['chat_key'] . "\",
           \"receiver\": \"" . $_POST['receiver_id'] . "\",
           \"type\": \"text\",
           \"text\": \"" . addslashes($_POST['text']) . "\"}");
        $html = curl_exec($ch1); // получили главную страницу со свернуты меню
        curl_close($ch1);
        $input = json_decode($html, true);
        mysqli_query($this->link, "insert into viberchat
                    set `event`=    'sendMessage',
                    `message_token`=  '{$input['message_token']}', 
                    `sender_id`=    '{$_POST['receiver_id']}',
                    `sender_name`=  '',
                    `sender_avatar`='',
                    `message_text`= '" . addslashes($_POST['text']) . "',
                    `message_type`= 'text',
                    `api_key`='{$_COOKIE['chat_key']}'
                    ");
    }

    //для добавления непрочитаных сообщений в текущий диалог
    function updateChat()
    {
        $q = mysqli_query($this->link, "select *, DATE_FORMAT(`timestamp`,'%k:%i ') AS time from viberchat 
                                  where `api_key`='{$_COOKIE['chat_key']}' 
                                  AND sender_id='" . $_POST['sender_id'] . "' 
                                  and my_seen=0");
        mysqli_query($this->link, "update viberchat set my_seen=1 where `api_key`='{$_COOKIE['chat_key']}' AND sender_id='" . $_POST['sender_id'] . "' and my_seen=0");
        mysqli_query($this->link, "
                                            update viberuser
                                            set new_message=0
                                            where 
                                            `api_key`='{$_COOKIE['chat_key']}' AND 
                                            `sender_id`='".$_POST['sender_id']."'

                                        ");
        while ($r = mysqli_fetch_array($q)) {
            $message_text = showEmoji($r['message_text']);
            if ($r['event'] == 'sendMessage')
                echo "<div class='sendMessage'>" . $message_text . "<span>" . $r['time'] . "</span></div>";
            else
                echo "<div class='message'>" . $message_text . "<span>" . $r['time'] . "</span></div>";
        }
    }

    function countCorrespondents(){
        $q = mysqli_query($this->link, "
                    select * from viberuser
                    where `api_key`='{$_COOKIE['chat_key']}' 
                    GROUP BY sender_id 
                   ");

        return mysqli_num_rows($q);
    }

    function updateCorrespondents()
    {
        //                    AND id in (SELECT max(id) FROM `viberchat` WHERE event='message' GROUP by  sender_id)

        $sql="
                    select * from viberuser
                    where `api_key`='{$_COOKIE['chat_key']}'
                
                    ORDER BY `message_date` DESC
                    ";

//        mysqli_query($this->link,"TRUNCATE TABLE `viberuser`");

        $query = mysqli_query($this->link, $sql);
        while ($r = mysqli_fetch_array($query)):
//            $temp_q=mysqli_query($this->link,"select sender_id from viberuser wehre sender_id='".$r['sender_id']."'");
//            if(!$temp_q){
//                mysqli_query($this->link,"insert into viberuser set sender_id='".$r['sender_id']."',  sender_name='".$r['sender_name']."', sender_avatar='".$r['sender_avatar']."', message_date='".$r['timestamp']."', api_key='".$r['api_key']."' ");
//            }

//            $count_unseen = mysqli_fetch_array(mysqli_query($this->link, "select count(id) from viberchat where my_seen=0 and sender_id='{$r['sender_id']}'"));
            $count = $r['new_message']==0?"":$r['new_message'];
            if ($r['sender_avatar'] == "")
                $sender_avatar = '/img/man.png';
            else {
                $sender_avatar = $r['sender_avatar'];
            }

            echo "<a href=\"#\" class=\"list-group-item\" data-sender-id=\"{$r['sender_id']}\" 
                  data-id=\"{$r['id']}\" onclick=\"getChat('{$r['id']}')\"><img src='$sender_avatar'> 
                  {$r['sender_name']}  <span class=\"badge\">" . $count . "</span></a>";
        endwhile;
    }

    function sendMessageWithImg()
    {
        $ch1 = curl_init('https://chatapi.viber.com/pa/send_message');
        curl_setopt($ch1, CURLOPT_HEADER, 0);//выводить заголовки
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
        curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");

        curl_setopt($ch1, CURLOPT_POSTFIELDS, "{
           \"auth_token\": \"" . $_COOKIE['chat_key'] . "\",
           \"receiver\": \"" . $_POST['receiver_id'] . "\",
           \"type\": \"picture\",
           \"text\": \"" . $_POST['text'] . "\",
           \"media\": \"http://2dto3dservice.com/objects/544/f5-12-29(20-).jpg\",
           \"thumbnail\": \"http://2dto3dservice.com/objects/544/thumbnail/f5-12-29(20-).jpg\"
           }");
        $html = curl_exec($ch1); // получили главную страницу со свернуты меню
        curl_close($ch1);
        $input = json_decode($html, true);
        mysqli_query($this->link, "insert into viberchat
                    set `event`=    'sendMessage',
                    `message_token`=  '{$input['message_token']}', 
                    `sender_id`=    '{$_POST['receiver_id']}',
                    `sender_name`=  '',
                    `sender_avatar`='',
                    `message_text`= '<img src=\"http://p83.mobisoftline.com.ua/img/).png\"><br>" . $_POST['text'] . "',
                    `message_type`= 'text',
                    `api_key`='{$_COOKIE['chat_key']}'
                    ");
    }

    function getChat()
    {
        mysqli_query($this->link, "
                        update viberuser set 
                        new_message=0 
                        where 
                            api_key='{$_COOKIE['chat_key']}' AND 
                            sender_id='" . $_POST['sender_id'] . "'"
                      );
        mysqli_query($this->link, "update viberchat set my_seen=1 where `api_key`='{$_COOKIE['chat_key']}' AND sender_id='" . $_POST['sender_id'] . "' and my_seen=0");

        $q = mysqli_query($this->link, "
                        select 
                            *, 
                            DATE_FORMAT(`timestamp`,'%k:%i ') AS time, 
                            DATE_FORMAT(`timestamp`,'%d.%m.%Y') AS `date` 
                        from viberchat 
                        where 
                          api_key='{$_COOKIE['chat_key']}' AND 
                          sender_id='" . $_POST['sender_id'] . "'"
        );

        $date = '';
        while ($r = mysqli_fetch_array($q)) {
            if ($r['date'] != $date) {
                $date = $r['date'];
                $datePrint = "<div class='date'>$date</div>";
            } else {
                $datePrint = '';
            }
            echo $datePrint;
            $message_text = showEmoji($r['message_text']);
            if ($r['event'] == 'sendMessage')
                echo "<div class='sendMessage'>" . $message_text . "<span title='{$r['date']}'>" . $r['time'] . "</span></div>";
            else
                echo "<div class='message'>" . $message_text . "<span title='{$r['date']}'>" . $r['time'] . "</span></div>";
        }
    }
}
