<div>
    <table>
        <tr>
            <td style="vertical-align: top; padding-top: 6px;"><span class="btn btn-primary" onclick="refreshWindowImg()"><i class="fa fa-refresh" aria-hidden="true"></i></span></td>
            <td style="vertical-align: top"><iframe src="/ckeditor/uploadimg2.php" style="border-width: 0; width:400px; height:60px;"></iframe></td>
        </tr>
    </table>
    </div>
<div class="alert alert-info">При рассылке Viber изображения должны соответствувать следующим требованиям:
    <ul>
        <li>формат файла jpg, png, gif</li>
        <li>ширина равна высоте изображения</li>
        <li>размер файла не больше 300 Кб</li>
    </ul>
</div>
<?php
function human_filesize($bytes, $decimals = 2)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

$path = $_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/pictures/";
$pathURL = "http://" . $_SERVER['HTTP_HOST'] . "/accounts/" . $_COOKIE['login'] . "/viber/pictures/";
$directory_handle = opendir($path);
while (false !== ($file = readdir($directory_handle))):
    if ($file != "." && $file != "..") {
        $size = getimagesize($path . $file);
        $certified = (($size[0] == $size[1]) and (filesize($path . $file) < 300 * 1024));
        ?>
        <div class="imgDialogItem "
            <? echo 'onclick="insertImgViber(\''.$pathURL . rawurlencode($file).'\')"'  ?> >
            <div class="imgDialogPrev"
                 style="background-image:url(<?php echo $pathURL . rawurlencode($file); ?>);">
            </div>
            <? print_r($file); ?>
            <br>

            <span class="<?= $size[0] == $size[1] ? 'label  text-black' : 'label label-danger'; ?>">
                <?= $size[0] . "x" . $size[1]; ?>
            </span>
            <span class="<?= filesize($path . $file) < 300 * 1024 ? 'label  text-black' : 'label label-danger'; ?>">
                <?= human_filesize(filesize($path . $file), 1); ?>
            </span>
        </div>

        <?php
    };
endwhile;
closedir($directory_handle);
?>
