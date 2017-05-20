<html>
<head>
    <script src="js/jquery-1.12.4.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.css">

</head>
<body>
<button onclick="sendRequest()" class="btn btn-primary">Try</button>
<?
$ch1 = curl_init('https://chatapi.viber.com/pa/set_webhook');
curl_setopt($ch1, CURLOPT_HEADER, 0);//выводить заголовки
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");

//curl_setopt($ch1, CURLOPT_POSTFIELDS, "username=Palatka&passwd=Palatka&remember=yes&login=op2");
curl_setopt($ch1, CURLOPT_POSTFIELDS, "{\"auth_token\": \"45247af6b7f484a9-de66108f8bf31a39-58b7520d267093bc\",\"url\": \"https://p83.mobisoftline.com.ua:8443/\",
\"event_types\": [\"delivered\", \"seen\", \"failed\", \"conversation_started\"]}");
$html = curl_exec($ch1); // получили главную страницу со свернуты меню
curl_close($ch1);
?>
<p><?= $html ?></p>
<?
$ch1 = curl_init('https://chatapi.viber.com/pa/get_account_info');
curl_setopt($ch1, CURLOPT_HEADER, 0);//выводить заголовки
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");

//curl_setopt($ch1, CURLOPT_POSTFIELDS, "username=Palatka&passwd=Palatka&remember=yes&login=op2");
curl_setopt($ch1, CURLOPT_POSTFIELDS, "{\"auth_token\": \"45247af6b7f484a9-de66108f8bf31a39-58b7520d267093bc\"}");
$html = curl_exec($ch1); // получили главную страницу со свернуты меню
curl_close($ch1);
?>
<p><?= $html ?></p>
<?
$ch1 = curl_init('https://chatapi.viber.com/pa/send_message');
curl_setopt($ch1, CURLOPT_HEADER, 0);//выводить заголовки
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");

//curl_setopt($ch1, CURLOPT_POSTFIELDS, "username=Palatka&passwd=Palatka&remember=yes&login=op2");
curl_setopt($ch1, CURLOPT_POSTFIELDS, "{
   \"event\": \"subscribed\",     
   \"timestamp\": 1457764197627,
   \"user\": {
       \"id\": \"VRvLGGudEvu3GduAlmx/sg==\", 
       \"name\": \"yarden\",      
       \"avatar\": \"http://avatar_url\",
       \"country\": \"IL\",       
       \"language\": \"en\"       
   }
}");
$html = curl_exec($ch1); // получили главную страницу со свернуты меню
curl_close($ch1);
?>
<p>
    <?= $html ?>
</p>
</body>
</html>
<script>
    function sendRequest() {
        $.post('http://mobisoftline.com.ua', function (data) {
            alert(data);
        })
    }
</script>
<?php
/**
 * Created by PhpStorm.
 * User: I
 * Date: 24.11.16
 * Time: 12:40
 */ ?>