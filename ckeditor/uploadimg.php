<?php

function ru2Lat($string)
{
    $rus = array(
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
        'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
        'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
        'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
        'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
        'І', 'і', 'Ї', 'ї', 'Є', 'є', ' '
    );
    $lat = array(
        'A', 'B', 'V', 'G', 'D', 'E', 'YO', 'ZH', 'Z', 'I', 'I',
        'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F',
        'H', 'C', 'CH', 'SH', 'SH', '`', 'Y', '`', 'E', 'YU', 'YA',
        'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'i',
        'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f',
        'h', 'c', 'ch', 'sh', 'sh', '`', 'y', '`', 'e', 'yu', 'ya',
        'I', 'i', 'I', 'i', 'E', 'e', '_'
    );
    $string = str_replace($rus, $lat, $string);

    return ($string);
}

$path = $_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/pictures/";
$filename = $_FILES['upload']['name'];
$filename = ru2Lat($filename);
if (copy($_FILES['upload']['tmp_name'], $path . $filename))
    echo $_FILES['upload']['name'];
else
    echo 'false';
