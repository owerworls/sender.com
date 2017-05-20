<?php

$data1 = file('/var/www/html/sharing/' . $_GET['filename']);
$countOnPage = 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

//===================== PAGINATOR 1 =======================//
$numbersList = '';

$count = ceil(count($data1) / $countOnPage);
$allowTotal = 12;
$allowLeft = max($allowTotal / 2, $allowTotal - ($count - $current_page + 1));
$allowRight = max($allowTotal / 2, $allowTotal - $current_page) * -1;

for ($i = 1; $i <= $count; $i++) {
    $active = ($current_page == $i) ? "class='active'" : "";

    if ((($current_page - $i) < $allowLeft) and (($current_page - $i) > $allowRight))
        $numbersList .= "<li $active><a href=\"#\" data-page=\"$i\" onclick=\"getDetailReportSMS({$_GET['id']},$i); return false;\">$i</a></li>";
    else {
        if (($i == 1))
            $numbersList .= "<li><a href=\"#\" aria-label=\"...\" onclick=\"return false;\"><span aria-hidden=\"true\">...</span></a></li>";
        if (($i == $count))
            $numbersList .= "<li><a href=\"#\" aria-label=\"...\" onclick=\"return false;\"><span aria-hidden=\"true\">...</span></a></li>";
    }
}

if ($current_page > 1)
    $href_prev = $current_page - 1;
else
    $href_prev = $current_page;

if ($current_page < $count)
    $href_next = $current_page + 1;
else
    $href_next = $current_page;
?>
<div class="row">
    <div class="col-xs-12 text-right">
        <a href="/sharing/<?=$_GET['filename']?>" class="btn btn-default" style="margin-bottom: 20px;" title="Скачать отчет"><i class="fa fa-download"></i> Скачать</a>
    </div>
</div>

<?
if ($count > 1) {
    ?>

    <nav aria-label="Page navigation">
        <ul class="pagination" style="margin-top: 0;">
            <li>
                <a href="#" aria-label="First" onclick="getDetailReportSMS(<?= $_GET['id'] ?>,1); return false;">
                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                </a>
            </li>
            <li>
                <a href="#" aria-label="Previous" onclick="getDetailReportSMS(<?= $_GET['id'] ?>,<?= $href_prev ?>); return false;">
                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                </a>
            </li>

            <?= $numbersList ?>

            <li>
                <a href="#" aria-label="Next" onclick="getDetailReportSMS(<?= $_GET['id'] ?>,<?= $href_next ?>);  return false;">
                    <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                </a>
            </li>
            <li>
                <a href="#" aria-label="Last" onclick="getDetailReportSMS(<?= $_GET['id'] ?>,<?= $count ?>); return false;">
                    <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                </a>
            </li>
        </ul>
    </nav>
    <?
}
//===================== /PAGINATOR 1 =======================//

echo "<table class='table table-condensed table-hover layout-fixed'>";
$active = ' active';
$i = -1;
foreach ($data1 as $line_num => $line) {

    $line = str_replace('&amp;', '&', $line);
    $line = iconv('cp1251', 'UTF-8', $line);
    $r = explode(";", $line);
    if ($line_num == 0)
        echo "<tr class='active'><th style='width:10%'>{$r[0]}</th><th style='width:15%'>{$r[1]}</th><th style='width:55%'>" . trim($r[2]) . "</th><th style='width:10%'>{$r[3]}</th><th style='width:10%'>{$r[4]}</th></tr>";

    $i++;
    if ((($current_page * $countOnPage - ($countOnPage - 1)) > $i) or (($current_page * $countOnPage) < $i)) continue;

    if ($line_num > 0) {
        echo "<tr><td>{$r[0]}</td><td>{$r[1]}</td><td onclick='if($(this).css(\"display\")==\"block\") $(this).css(\"display\",\"table-cell\"); else $(this).css(\"display\",\"block\")' style='max-height:47px; display: block; overflow: hidden; color: #0e84b5; cursor: pointer; '>" . trim($r[2]) . "</td><td>{$r[3]}</td><td>{$r[4]}</td></tr>";
    }

    $active = '';
}
echo " </table> ";

//===================== PAGINATOR 2 =======================//
$numbersList = '';

$count = ceil(count($data1) / $countOnPage);
$allowTotal = 12;//count displayed pages
$allowLeft = max($allowTotal / 2, $allowTotal - ($count - $current_page + 1));
$allowRight = max($allowTotal / 2, $allowTotal - $current_page) * -1;

for ($i = 1; $i <= $count; $i++) {
    $active = ($current_page == $i) ? "class='active'" : "";

    if ((($current_page - $i) < $allowLeft) and (($current_page - $i) > $allowRight))
        $numbersList .= "<li $active><a href=\"#\" data-page=\"$i\" onclick=\"getDetailReportSMS({$_GET['id']},$i); return false;\">$i</a></li>";
    else {
        if (($i == 1))
            $numbersList .= "<li><a href=\"#\" aria-label=\"...\" onclick=\"return false;\"><span aria-hidden=\"true\">...</span></a></li>";
        if (($i == $count))
            $numbersList .= "<li><a href=\"#\" aria-label=\"...\" onclick=\"return false;\"><span aria-hidden=\"true\">...</span></a></li>";
    }
}

if ($current_page > 1)
    $href_prev = $current_page - 1;
else
    $href_prev = $current_page;

if ($current_page < $count)
    $href_next = $current_page + 1;
else
    $href_next = $current_page;
if ($count > 1) {
    ?>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li>
                <a href="#" aria-label="First" onclick="getDetailReportSMS(<?= $_GET['id'] ?>,1); return false;">
                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                </a>
            </li>
            <li>
                <a href="#" aria-label="Previous" onclick="getDetailReportSMS(<?= $_GET['id'] ?>,<?= $href_prev ?>); return false;">
                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                </a>
            </li>

            <?= $numbersList ?>

            <li>
                <a href="#" aria-label="Next" onclick="getDetailReportSMS(<?= $_GET['id'] ?>,<?= $href_next ?>);  return false;">
                    <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                </a>
            </li>
            <li>
                <a href="#" aria-label="Last" onclick="getDetailReportSMS(<?= $_GET['id'] ?>,<?= $count ?>); return false;">
                    <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                </a>
            </li>
        </ul>
    </nav>
    <?
}
?>
