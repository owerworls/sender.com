<?php
if ((isset($_GET['finish_notification'])) and ($_GET['user'] == $_COOKIE['id'])) {
    $start_notification = date('dmYHis', mktime(0, 0, 0, substr($_GET['start_notification'], 3, 2), substr($_GET['start_notification'], 0, 2), substr($_GET['start_notification'], 6, 4)));
    $finish_notification = date('dmYHis', mktime(23, 59, 59, substr($_GET['finish_notification'], 3, 2), substr($_GET['finish_notification'], 0, 2), substr($_GET['finish_notification'], 6, 4)));
} else {
    $start_notification = date('dmYHis', mktime(0, 0, 0, date('m'), date('d') - 7, date('Y')));
    $finish_notification = date('dmYHis', mktime(23, 59, 59, date('m'), date('d'), date('Y')));
}
$user = $_COOKIE['id'];
$data = file("http://172.20.128.3:8080/smtp/billing_notification_detail?user=$user&start_notification=$start_notification&finish_notification=$finish_notification");
//echo "http://172.20.128.3:8080/messenger/billing_notification_detail?user=$user&start_notification=$start_notification&finish_notification=$finish_notification";
//$data = file("http://172.20.128.3:8080/messenger/billing?user=$user&start_notification=$start_notification&finish_notification=$finish_notification");
//$data_detail = file("http://172.20.128.3:8080/messenger/billing_detail?user=$user&start_notification=$start_notification&finish_notification=$finish_notification");
$source = explode("=", $data[0]);
//$source_detail=explode("=",$data_detail[0]);


$data = file('sharing/' . trim($source[1]));
$delivered_sum = 0;
$rejected_sum = 0;
$postponed_sum = 0;
$sent_sum = 0;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$i = -1;
foreach ($data as $line_num => $line) {
    if ($line_num == 0){
        $tmp = explode(chr(9), $line);
        $table .= "<tr class='active'><th style='width:10%'>" . $tmp[0] . "</th><th style='width:58%'>" . $tmp[1] . "</th><th style='width:8%'>" . $tmp[2] .
            "</th><th style='width:8%'>" . $tmp[3] . "</th><th style='width:8%'>" . $tmp[4] . "</th><th style='width:8%'>" . $tmp[5] . "</th></tr>";    }
    $i++;
    if ((($current_page * 20 - 19) > $i) or (($current_page * 20) < $i)) continue;
    $tmp = explode(chr(9), $line);
    if ($line_num > 0) {
        $table .= "<tr><td>" . $tmp[0] . "</td><td>" . $tmp[1] . "</td><td>" . $tmp[2] . "</td><td>" . $tmp[3] . "</td><td>" . $tmp[4] . "</td><td>" . $tmp[5] . "</td></tr>";
        $delivered_sum = $delivered_sum + $tmp[2];
        $rejected_sum = $rejected_sum + $tmp[3];
        $postponed_sum = $postponed_sum + $tmp[4];
        $sent_sum = $sent_sum + $tmp[5];
    }
}
$table .= "<tr class='active' style='font-weight: bold'><td colspan='2'>Всего</td><td>$delivered_sum</td><td>$rejected_sum</td><td>$sent_sum</td><td>$postponed_sum</td></tr>";

?><?include 'head.php'?>

<body>

<div class="container">
    <?php
    include 'nav.php';
    ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <? include $_SERVER['DOCUMENT_ROOT'].'/view/reportNav.php'; ?>


            <form action="/" method="get">
                <div class="row">
                    <div class="col-md-3">
                        Дата с
                        <input id="datetimepicker" name="start_notification"
                               value="<?= isset($_GET['start_notification']) ? $_GET['start_notification'] : date('d.m.Y', time() - 60 * 60 * 24 * 7) ?>"
                               class="form-control" required placeholder="Клик для выбора">
                    </div>
                    <div class="col-md-3">
                        Дата по
                        <input id="datetimepicker2" name="finish_notification"
                               value="<?= isset($_GET['finish_notification']) ? $_GET['finish_notification'] : date('d.m.Y', time() - 60 * 60) ?>"
                               class="form-control" required placeholder="Клик для выбора">
                    </div>
                    <div class="col-md-3"><br>
                        <input type="submit" value="Открыть" class="btn btn-primary">

                    </div>
                </div>
                <input type="hidden" name="target" value="report_detail">
                <input type="hidden" name="user" value="<?= trim($_COOKIE['id']) ?>">
            </form>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Таблица отсылки</h3>
            <table class="table table-condensed table-hover">
                <?php echo $table; ?>
            </table>
            <?
            $numbersList = '';
            $query = $_SERVER['QUERY_STRING'];
            if (strpos($query, '&page=') > 0)
                $query = substr($query, 0, strpos($query, '&page='));

            $count = ceil(count($data) / 20);

            for ($i = 1; $i <= $count; $i++) {
                if ((isset($_GET['page'])) and ($_GET['page'] == $i))
                    $active = ' active';
                else
                    if ((!isset($_GET['page'])) and ($i == 1))
                        $active = ' active';
                    else
                        $active = '';
                $href = "?" . $query . "&page=" . $i;
                $numbersList .= "<li class='$active'><a href=\"$href\">$i</a></li>";
            }

            if ($current_page > 1)
                $href_prev = "?" . $query . "&page=" . ($current_page - 1);
            else
                $href_prev = "?" . $query . "&page=" . ($current_page);

            if ($current_page < $count)
                $href_next = "?" . $query . "&page=" . ($current_page + 1);
            else
                $href_next = "?" . $query . "&page=" . ($current_page);

            ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li>
                        <a href="<?= $href_prev ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?= $numbersList ?>

                    <li>
                        <a href="<?= $href_next ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>


<script src="../js/jquery.datetimepicker.full.js"></script>
<script>

    $.datetimepicker.setLocale('ru');


    $('#datetimepicker').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'ru',
        disabledDates: ['1986/01/08', '1986/01/09', '1986/01/10'],
        startDate: '2016/08/19',
        formatTime: 'H:i',
        formatDate: 'd.m.Y',
        format: 'd.m.Y',
        timepicker: false,
        yearStart: 2016
    });
    $('#datetimepicker2').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'ru',
        disabledDates: ['1986/01/08', '1986/01/09', '1986/01/10'],
        startDate: '2016/08/19',
        formatTime: 'H:i',
        formatDate: 'd.m.Y',
        format: 'd.m.Y',
        timepicker: false,
        yearStart: 2016
    });
    //$('#datetimepicker').datetimepicker({value:'2016/04/15 05:03',step:10});

</script>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/view/footer.php';
?>