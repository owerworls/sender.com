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

$path = $_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/pictures/";
$filename = $_FILES['upload']['name'];
$filename = ru2Lat($filename);
if (isset($_FILES['upload'])) {
    $size = getimagesize($_FILES['upload']['tmp_name']);
    $types=[IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG];

    if(($size[0] != $size[1]))
        echo 'Ошибка загрузки: ширина не равна высоте изображения';

    elseif(filesize($_FILES['upload']['tmp_name']) > 300 * 1024)
        echo 'Ошибка загрузки: размер файла больше 300 Кб';

    elseif (exif_imagetype($_FILES['upload']['tmp_name'])===false)
        echo 'Ошибка загрузки: не известный формат файла';

    elseif (in_array(exif_imagetype($_FILES['upload']['tmp_name']),$types)===false)
        echo 'Ошибка загрузки: не поддерживаемый формат файла (jpg, png, gif)';

    elseif (copy($_FILES['upload']['tmp_name'], $path . $filename))
        echo 'Файл успешно загружен. Нажмите "обновить"';

    else
        echo 'Ошибка загрузки: не известная ошибка';
} else {
    ?>
    <form enctype="multipart/form-data" method="post">
        <table style="width: 100%">
            <tr>
                <td><input name="upload" type="file" onchange="$('form').submit()" accept="image/jpeg,image/png,image/gif"></td>
                <td><input type="submit"></td>
            </tr>
        </table>
    </form>
    <?
}
?>