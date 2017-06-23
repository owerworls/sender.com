<?php
$user = $_COOKIE['id'];
$dateFrom = $_GET['dateFrom'] != '' ? str_replace('.', '', $_GET['dateFrom']) . '000000' : date('dmY', time()- 60 * 60 * 24 * 7) . '000000' ;
$dateTo = $_GET['dateTo'] != '' ? str_replace('.', '', $_GET['dateTo']) . '235959' : date('dmY', time() ). '235959';

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
    };
}

?>

<script>
    $(function () {
        $('#container').highcharts({
            chart: {
                type: 'column',
                width: 400,
                height: 200
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            yAxis: {
                enabled: false,
                min: 0,
                title: {
                    text: ''
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
                data: [<?= $delivered; ?>]
            }, {
                name: 'Не доставлено',
                data: [<?= $rejected; ?>]
            }, {
                name: 'Отправлено',
                data: [<?= $sent; ?>]
            }, {
                name: 'Просрочено',
                data: [<?= $postponed; ?>]
            }]
        });
    });</script>
    <div style="min-width: 310px; height: 400px; margin: 0 auto"></div>

