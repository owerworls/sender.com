<?php
$user = $_COOKIE['id'];
$dateFrom = str_replace('.', '', $_GET['dateFrom']) . '000000';
$dateTo = str_replace('.', '', $_GET['dateTo']) . '235959';


//Получаем общий список для вывода на экран. В разрезе отправки
if ((isset($_COOKIE['detailSMSReport'])) && ($_GET['requestSource'] == 'false') && (file_exists($_SERVER['DOCUMENT_ROOT'] . '/sharing/' . $_COOKIE['detailSMSReport']))) {
    $source = $_COOKIE['detailSMSReport'];
    file_put_contents('checkin.txt', 'true' . PHP_EOL);
    file_put_contents('checkin.txt', 'requestSource ' . $_GET['requestSource'], FILE_APPEND);
} else {
    $data = file("http://172.20.128.3:8080/messenger/billing_notification_detail?user=$user&start_notification=$dateFrom&finish_notification=$dateTo");
    list(, $source) = explode("=", trim($data[0]));
    setcookie('detailSMSReport', $source);
    file_put_contents('checkin.txt', 'false' . PHP_EOL);
    file_put_contents('checkin.txt', 'requestSource ' . $_GET['requestSource'], FILE_APPEND);
}


$data = file($_SERVER['DOCUMENT_ROOT'] . '/sharing/' . $source);
$delivered_sum = 0;
$rejected_sum = 0;
$postponed_sum = 0;
$sent_sum = 0;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$i = -1;
foreach ($data as $line_num => $line) {
    if ($line_num == 0) {
        $tmp = explode(chr(9), $line);
        $table .= "
                <tr class='active'>
                <th style='width:3%'>" . $tmp[0] . "</th>
                <th style='width:9%'>" . $tmp[1] . "</th>
                <th style='width:56%'>" . $tmp[2] . "</th>
                <th style='width:8%;'>" . $tmp[3] . "</th>
                <th style='width:8%;'>" . $tmp[4] . "</th>
                <th style='width:8%;'>" . $tmp[5] . "</th>
                <th style='width:8%;'>" . $tmp[6] . "</th>
                </tr>";
    }
    $i++;
    if ((($current_page * 20 - 19) > $i) or (($current_page * 20) < $i)) continue;

    $tmp = explode(chr(9), $line);
    if ($line_num > 0) {
        $table .= "
                    <tr>
                    <td>
                    <a href='#' onclick='getDetailReportSMSFileName({$tmp[0]}); getDetailReportSMS({$tmp[0]}); return false;'><i class=\"fa fa-search fa-fw\" aria-hidden=\"true\"></i></a>
                    <a href='#' onclick='return getDetailSMSReportByDispatch({$tmp[0]},$(this))' ><i class=\"fa fa-download fa-fw\" aria-hidden=\"true\"></i></a>
                    </td>
                    <td>" . $tmp[1] . "</td>
                    <td style='max-height: 47px;
                    display: block;
                    overflow: hidden;
                    color: rgb(14, 132, 181);
                    cursor: pointer;'
                    onclick='if($(this).css(\"display\")==\"block\") $(this).css(\"display\",\"table-cell\"); else $(this).css(\"display\",\"block\")'>" . $tmp[2] . "</td>
                    <td>" . $tmp[3] . "</td>
                    <td>" . $tmp[4] . "</td>
                    <td>" . $tmp[5] . "</td>
                    <td>" . $tmp[6] . "</td>
                    </tr>";
        $delivered_sum = $delivered_sum + $tmp[3];
        $rejected_sum = $rejected_sum + $tmp[4];
        $postponed_sum = $postponed_sum + $tmp[5];
        $sent_sum = $sent_sum + $tmp[6];
    }
}
$table .= "<tr class='active' style='font-weight: bold'><td colspan='3'>Всего</td><td>$delivered_sum</td><td>$rejected_sum</td><td>$postponed_sum</td><td>$sent_sum</td></tr>";
?>
<div class="panel panel-default">
    <div class="panel-body" style="min-height: 300px; position: relative">

        <h3>Таблица отсылки</h3>
        <table class="table table-condensed table-hover layout-fixed">
            <?php echo $table; ?>
        </table>


        <?
        $numbersList = '';
        $count = ceil(count($data) / 20);
        for ($i = 1; $i <= $count; $i++) {
            if ((isset($_GET['page'])) and ($_GET['page'] == $i))
                $active = "  class='active'";
            else
                if ((!isset($_GET['page'])) and ($i == 1))
                    $active = "  class='active'";
                else
                    $active = "";

            $allowTotal = 12;
            $allowLeft = max($allowTotal / 2, $allowTotal - ($count - $_GET['page'] + 1));
            $allowRight = max($allowTotal / 2, $allowTotal - $_GET['page']) * -1;

            if ((($_GET['page'] - $i) < $allowLeft) and (($_GET['page'] - $i) > $allowRight))
                $numbersList .= "<li $active><a href=\"#\" onclick='return ajaxDetailSMSReport($i);'>$i</a></li>";
            else {
                if (($i == 1)) $numbersList .= "<li><a href=\"#\" onclick=\"return false;\">...</a></li>";
                if (($i == $count)) $numbersList .= "<li><a href=\"#\" onclick=\"return false;\">...</a></li>";
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
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li>
                    <a href="#" onclick="return ajaxDetailSMSReport(1);">
                        <i class="fa fa-angle-left" aria-hidden="true"></i><i class="fa fa-angle-left" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="return ajaxDetailSMSReport(<?= $href_prev ?>);">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                    </a>
                </li>
                <?= $numbersList ?>

                <li>
                    <a href="#" onclick="return ajaxDetailSMSReport(<?= $href_next ?>);">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                    <a href="#" onclick="return ajaxDetailSMSReport(<?= $count ?>);">
                        <i class="fa fa-angle-right" aria-hidden="true"></i><i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</div>