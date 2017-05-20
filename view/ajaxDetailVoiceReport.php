<?php
$user = $_COOKIE['id'];
$page = $_GET['page'];
$limit = $page * 20 - 20;

// замена теефонов
$phoneReplace=[
    "380445008770"=>"839",
    "0445682158"=>"839",
    "380445002167"=>"5545",
    "0442336853"=>"5545",
    "839"=>['0445008770', '0445682158'],
    "5545"=>['0445002167', '0442336853']
];

$dateFrom = DateTime::createFromFormat('d.m.Y', $_GET['dateFrom']);
$dateTo = DateTime::createFromFormat('d.m.Y', $_GET['dateTo']);
$filtering.=$_GET['outgoing']!=""?" AND src like '%{$_GET['outgoing']}%'":"";
if($_GET['incoming']){
    $filtering.=" AND (rdr_src like '%".$phoneReplace[$_GET['incoming']][0]."%'";
    $filtering.=" or rdr_src like '%".$phoneReplace[$_GET['incoming']][1]."%')";
}


// Выполнение SQL запроса
$query = "select * from taxi WHERE calldate BETWEEN '" . $dateFrom->format('Y.m.d') . "' and  '" . $dateTo->format('Y.m.d ') . " 23:59:59' $filtering limit  20 OFFSET $limit";
$result = pg_query($link_postgre, $query) or die('Ошибка запроса: ' . pg_last_error());

$query = "select * from taxi WHERE calldate BETWEEN '" . $dateFrom->format('Y.m.d') . "' and  '" . $dateTo->format('Y.m.d ') . " 23:59:59' $filtering";
$countQueryRows = pg_num_rows(pg_query($link_postgre, $query));

$delivered_sum = 0;
$rejected_sum = 0;
$postponed_sum = 0;
$sent_sum = 0;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$i = -1;



$table .= "<tr class='active'>";
$table .= "<th>Дата/Время</th>";
$table .= "<th style='text-align: right'>Исходящий</th>";
$table .= "<th style='text-align: right'>Входящий номер</th>";
$table .= "<th style='text-align: right'>Продолжительность</th>";
$table .= "<th style='text-align: right'>Тарификация</th>";
$table .= "<th>Статус</th>";
$table .= "</tr>";


$table .= "<tr><td></td><td style='text-align: right'>
    <div class=\"input-group input-group-sm\">
      <input type=\"text\" class=\"form-control outgoing\" onchange='ajaxDetailVoiceReport(1,true)' value='".($_GET['outgoing']!=''?$_GET['outgoing']:'')."' placeholder=\"Фитровать...\">
      <span class=\"input-group-btn\">
        <a class=\"btn btn-default\" type=\"button\"><i class='fa fa-filter'></i></a>
      </span>
    </div>
</td>
<td style='text-align: right'>
        <select class='form-control input-sm incoming' onchange='ajaxDetailVoiceReport(1,true)' >
        <option value='0'></option>
        <option value='839' ".($_GET['incoming']=='839'?'selected':'').">839</option>
        <option value='5545' ".($_GET['incoming']=='5545'?'selected':'').">5545</option>
</select>
</td><td style='text-align: right'></td><td style='text-align: right'></td><td></td></tr>";

$rdr_src=[];
// Вывод результатов в HTML
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $table .= "<tr>";
    $table .= "<td>{$line['calldate']}</td>";
    $table .= "<td style='text-align: right'>{$line['src']}</td>";
    $table .= "<td style='text-align: right'>".$phoneReplace[$line['rdr_src']]."</td>";
    $table .= "<td style='text-align: right'>{$line['duration']}</td>";
    $table .= "<td style='text-align: right'>{$line['billsec']}</td>";
    $table .= "<td>{$line['disposition']}</td>";

    $table .= "</tr>";
}


?>
<div class="panel panel-default">
    <div class="panel-body" style="min-height: 300px; position: relative">

        <h3>Таблица отсылки</h3>
        <table class="table table-condensed ">
            <?php echo $table; ?>
        </table>


        <?
        $numbersList = '';
        $count = ceil($countQueryRows / 20);
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
                $numbersList .= "<li $active><a href=\"#\" onclick='return ajaxDetailVoiceReport($i);'>$i</a></li>";
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
                    <a href="#" onclick="return ajaxDetailVoiceReport(1);">
                        <i class="fa fa-angle-left" aria-hidden="true"></i><i class="fa fa-angle-left" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="return ajaxDetailVoiceReport(<?= $href_prev ?>);">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                    </a>
                </li>
                <?= $numbersList ?>

                <li>
                    <a href="#" onclick="return ajaxDetailVoiceReport(<?= $href_next ?>);">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                    <a href="#" onclick="return ajaxDetailVoiceReport(<?= $count ?>);">
                        <i class="fa fa-angle-right" aria-hidden="true"></i><i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</div>