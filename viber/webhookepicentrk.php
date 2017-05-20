<?
setcookie('chat_keyw', 'ssss', time() + 60 * 60*8, '/');
echo $_COOKIE['chat_keyw'];
echo $_COOKIE['chat_key'];
$ch1 = curl_init('https://chatapi.viber.com/pa/set_webhook');
curl_setopt ($ch1, CURLOPT_HEADER, 0);//выводить заголовки
curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
curl_setopt ($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");
//curl_setopt($ch1, CURLOPT_POSTFIELDS, "username=Palatka&passwd=Palatka&remember=yes&login=op2");
curl_setopt($ch1, CURLOPT_POSTFIELDS, '{
"auth_token": "453ca38f65b21d97-40664d37f6038616-cfae4b239818de06",
"url": "https://p83.mobisoftline.com.ua:8443/epicentrk.php",
"event_types": ["delivered", "seen", "failed", "conversation_started"]
}');
$html = curl_exec($ch1);

curl_close($ch1);
?>
<p><?=$html?></p>
<?
$ch1 = curl_init('https://chatapi.viber.com/pa/get_account_info');
curl_setopt ($ch1, CURLOPT_HEADER, 0);//выводить заголовки
curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
curl_setopt ($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");

//curl_setopt($ch1, CURLOPT_POSTFIELDS, "username=Palatka&passwd=Palatka&remember=yes&login=op2");
curl_setopt($ch1, CURLOPT_POSTFIELDS, "{\"auth_token\": \"453ca38f65b21d97-40664d37f6038616-cfae4b239818de06\"}");
$html = curl_exec($ch1);
curl_close($ch1);
?>
<p><?=$html?></p>
