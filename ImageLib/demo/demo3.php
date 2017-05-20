<?php

/*
 * Центральный пропорциональный скроп
 */

require_once '../AcImage.php';

//$filePath = 'img/4na3.jpg';
$savePath = 'out/'.rand(0, 1000).'.jpg';
$filePath = 'img/P60508-150639.jpg';


$width = '130px';
$height = '130px';

$image = AcImage::createImage($filePath);

$image
	->resizeByWidth(200)
	->save($savePath);

?>

<h3>Оригинал</h3>
<img src="<?=$filePath; ?>" />

<h3>Центральный пропорциональный кроп</h3>
<img src="<?=$savePath; ?>" />
