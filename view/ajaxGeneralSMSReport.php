<?php
$user = $_COOKIE['id'];
$dateFrom = str_replace('.', '', $_GET['dateFrom']) . '000000';
$dateTo = str_replace('.', '', $_GET['dateTo']) . '235959';

$data = file("http://172.20.128.3:8080/messenger/billing?user=$user&start_notification=$dateFrom&finish_notification=$dateTo");
$source = explode("=", $data[0]);

$data = file('sharing/' . trim($source[1]));
$delivered_sum = 0;
$rejected_sum = 0;
$postponed_sum = 0;
$sent_sum = 0;
foreach ($data as $line_num => $line) {
    $tmp = explode(chr(9), $line);
    if ($line_num > 0) {
        $date .= "'" . trim($tmp[0]) . "', ";
        $delivered .= trim($tmp[1]) . ", ";
        $delivered_sum = $delivered_sum + (int)trim($tmp[1]);
        $rejected .= trim($tmp[2]) . ", ";
        $rejected_sum = $rejected_sum + (int)trim($tmp[2]);
        $sent .= trim($tmp[3]) . ", ";
        $sent_sum = $sent_sum + (int)trim($tmp[3]);
        $postponed .= trim($tmp[4]) . ", ";
        $postponed_sum = $postponed_sum + (int)trim($tmp[4]);
        $table .= "<tr><td>" . $tmp[0] . "</td><td>" . $tmp[1] . "</td><td>" . $tmp[2] . "</td><td>" . $tmp[3] . "</td><td>" . $tmp[4] . "</td></tr>";
    } else
        $table .= "<tr class='active'><th>" . $tmp[0] . "</th><th>" . $tmp[1] . "</th><th>" . $tmp[2] . "</th><th>" . $tmp[3] . "</th><th>" . $tmp[4] . "</th></tr>";
}
$table .= "<tr class='active' style='font-weight: bold'><td>Всего</td><td>$delivered_sum</td><td>$rejected_sum</td><td>$sent_sum</td><td>$postponed_sum</td></tr>";

?>

<script>
    $(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [<?php echo $date;?>]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Количество сообщений'
                },
                stackLabels: {
                    enabled: false,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },

            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: false,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black'
                        }
                    }
                }
            },
            series: [{
                name: 'Доставлено',
                data: [<?php echo $delivered; ?>]
            }, {
                name: 'Не доставлено',
                data: [<?php echo $rejected; ?>]
            }, {
                name: 'Отправлено',
                data: [<?php echo $sent; ?>]
            }, {
                name: 'Просрочено',
                data: [<?php echo $postponed; ?>]
            }]
        });
    });</script>

<div class="panel panel-default">
    <div class="panel-body">
        <h3>График отсылки</h3>
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <h3>Таблица отсылки</h3>
        <table class="table table-condensed table-hover">
            <?php echo $table; ?>
        </table>
    </div>
</div>

