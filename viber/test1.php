<?php

print_r($_REQUEST);
$body = file_get_contents('php://input');
print_r($body);
print "\n";


$fp = fopen('requesst.log', "a"); // Открываем файл в режиме записи
foreach($_REQUEST as $d=>$b){
    fwrite($fp, $d."=>".$b); // Запись в файл
}

fclose($fp); //Закрытие файла