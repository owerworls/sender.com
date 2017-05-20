<?php
//$link = mysqli_connect('172.20.128.22:3306', 'viberchat', 'v1b3r4@t', 'viber');
include_once ($_SERVER['DOCUMENT_ROOT']."/config.php");

include_once ($_SERVER['DOCUMENT_ROOT']."/classes/Chat.php");
$chat=new Chat($link);

function showEmoji($text){
    $text=str_replace('(smiley)','<img class="emoji" src="/emoji/(smiley).png">',$text);
    $text=str_replace(':)','<img class="emoji" src="/emoji/(smiley).png">',$text);
    $text=str_replace(':-)','<img class="emoji" src="/emoji/(smiley).png">',$text);
    $text=str_replace('(sad)','<img class="emoji" src="/emoji/(sad).png">',$text);
    $text=str_replace(':(','<img class="emoji" src="/emoji/(sad).png">',$text);
    $text=str_replace('(wink)','<img class="emoji" src="/emoji/(wink).png">',$text);
    $text=str_replace(';)','<img class="emoji" src="/emoji/(wink).png">',$text);
    $text=str_replace('(angry)','<img class="emoji" src="/emoji/(angry).png">',$text);
    $text=str_replace('(inlove)','<img class="emoji" src="/emoji/(inlove).png">',$text);
    $text=str_replace('(yummi)','<img class="emoji" src="/emoji/(yummi).png">',$text);
    $text=str_replace('(laugh)','<img class="emoji" src="/emoji/(laugh).png">',$text);
    $text=str_replace('(surprised)','<img class="emoji" src="/emoji/(surprised).png">',$text);
    $text=str_replace('(moa)','<img class="emoji" src="/emoji/(moa).png">',$text);
    $text=str_replace('(happy)','<img class="emoji" src="/emoji/(happy).png">',$text);
    $text=str_replace('(cry)','<img class="emoji" src="/emoji/(cry).png">',$text);
    $text=str_replace('(sick)','<img class="emoji" src="/emoji/(sick).png">',$text);
    $text=str_replace('(shy)','<img class="emoji" src="/emoji/(shy).png">',$text);
    $text=str_replace('(teeth)','<img class="emoji" src="/emoji/(teeth).png">',$text);
    $text=str_replace('(tongue)','<img class="emoji" src="/emoji/(tongue).png">',$text);
    $text=str_replace('(money)','<img class="emoji" src="/emoji/(money).png">',$text);
    $text=str_replace('(mad)','<img class="emoji" src="/emoji/(mad).png">',$text);
    $text=str_replace('(flirt)','<img class="emoji" src="/emoji/(flirt).png">',$text);
    $text=str_replace('(crazy)','<img class="emoji" src="/emoji/(crazy).png">',$text);
    $text=str_replace('(confused)','<img class="emoji" src="/emoji/(confused).png">',$text);
    $text=str_replace('(depressed)','<img class="emoji" src="/emoji/(depressed).png">',$text);
    $text=str_replace('(scream)','<img class="emoji" src="/emoji/(scream).png">',$text);
    $text=str_replace('(nerd)','<img class="emoji" src="/emoji/(nerd).png">',$text);
    $text=str_replace('(not_sure)','<img class="emoji" src="/emoji/(not_sure).png">',$text);
    $text=str_replace('(cool)','<img class="emoji" src="/emoji/(cool).png">',$text);
    $text=str_replace('(huh)','<img class="emoji" src="/emoji/(huh).png">',$text);
    $text=str_replace('(happycry)','<img class="emoji" src="/emoji/(happycry).png">',$text);
    $text=str_replace('(mwah)','<img class="emoji" src="/emoji/(mwah).png">',$text);
    $text=str_replace('(exhausted)','<img class="emoji" src="/emoji/(exhausted).png">',$text);
    $text=str_replace('(eek)','<img class="emoji" src="/emoji/(eek).png">',$text);
    $text=str_replace('(dizzy)','<img class="emoji" src="/emoji/(dizzy).png">',$text);
    $text=str_replace('(dead)','<img class="emoji" src="/emoji/(dead).png">',$text);
    $text=str_replace('(straight)','<img class="emoji" src="/emoji/(straight).png">',$text);
    $text=str_replace('(yo)','<img class="emoji" src="/emoji/(yo).png">',$text);
    $text=str_replace('(wtf)','<img class="emoji" src="/emoji/(wtf).png">',$text);
    $text=str_replace('(ohno)','<img class="emoji" src="/emoji/(ohno).png">',$text);
    $text=str_replace('(oh)','<img class="emoji" src="/emoji/(oh).png">',$text);
    $text=str_replace('(wink2)','<img class="emoji" src="/emoji/(wink2).png">',$text);
    $text=str_replace('(what)','<img class="emoji" src="/emoji/(what).png">',$text);
    $text=str_replace('(weak)','<img class="emoji" src="/emoji/(weak).png">',$text);
    $text=str_replace('(upset)','<img class="emoji" src="/emoji/(upset).png">',$text);
    $text=str_replace('(ugh)','<img class="emoji" src="/emoji/(ugh).png">',$text);
    $text=str_replace('(teary)','<img class="emoji" src="/emoji/(teary).png">',$text);
    $text=str_replace('(singing)','<img class="emoji" src="/emoji/(singing).png">',$text);
    $text=str_replace('(silly)','<img class="emoji" src="/emoji/(silly).png">',$text);
    $text=str_replace('(meh)','<img class="emoji" src="/emoji/(meh).png">',$text);
    $text=str_replace('(mischievous)','<img class="emoji" src="/emoji/(mischievous).png">',$text);
    $text=str_replace('(ninja)','<img class="emoji" src="/emoji/(ninja).png">',$text);
    return $text;
}

$target = $_POST['target'];
switch ($target) {
    case 'getChat':
        $chat->getChat();
        break;
    case 'updateChat':
        $chat->updateChat();
        
        break;
    case 'updateCorrespondents':
        $chat->updateCorrespondents();
        break;
    case 'updateNewChats':
        $q = mysqli_query($link, "select * from viberchat where `api_key`='{$_COOKIE['chat_key']}' AND my_seen=0  GROUP BY sender_name ");
        $r = mysqli_num_rows($q);
        $count = $r > 0 ? $r : "";
        echo $count;
        break;
    case 'sendMessage':
        $chat->sendMessage();
        break;
    case 'sendMessageWithImg':
        $chat->sendMessageWithImg();
        break;
}