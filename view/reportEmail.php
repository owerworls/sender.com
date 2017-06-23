<?php
if((isset($_GET['finish_notification']))and($_GET['user']==$_COOKIE['id'])){
    $start_notification=date('dmYHis',mktime(0,0,0,substr($_GET['start_notification'],3,2),substr($_GET['start_notification'],0,2),substr($_GET['start_notification'],6,4)));
    $finish_notification=date('dmYHis',mktime(23,59,59,substr($_GET['finish_notification'],3,2),substr($_GET['finish_notification'],0,2),substr($_GET['finish_notification'],6,4)));
}
else{
    $start_notification=date('dmYHis',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
    $finish_notification=date('dmYHis',mktime(23,59,59,date('m'),date('d'),date('Y')));
}
$user = $_COOKIE['id'];

$data = file("http://172.20.128.3:8080/smtp/billing?user=$user&start_notification=$start_notification&finish_notification=$finish_notification");
$data_detail = file("http://172.20.128.3:8080/smtp/billing_detail?user=$user&start_notification=$start_notification&finish_notification=$start_notification");
$source=explode("=",$data[0]);
$source_detail=explode("=",$data_detail[0]);

$data = file('sharing/'.trim($source[1]));
$delivered_sum = 0;
$rejected_sum = 0;
$postponed_sum = 0;
$sent_sum = 0;
foreach ($data as $line_num => $line) {
    $tmp = explode(chr(9), $line);
    if ($line_num > 0) {
        $date .= "'" . trim($tmp[0]) . "', ";
        $delivered .= trim($tmp[1]) . ", ";
        $delivered_sum = $delivered_sum + trim($tmp[1]);
        $rejected .= trim($tmp[2]) . ", ";
        $rejected_sum = $rejected_sum + trim($tmp[2]);
        $sent .= trim($tmp[3]) . ", ";
        $sent_sum = $sent_sum + trim($tmp[3]);
        $postponed .= trim($tmp[4]) . ", ";
        $postponed_sum = $postponed_sum + trim($tmp[4]);
        $table .= "<tr><td>" . $tmp[0] . "</td><td>" . $tmp[1] . "</td><td>" . $tmp[2] . "</td><td>" . $tmp[3] . "</td><td>" . $tmp[4] . "</td></tr>";
    } else
        $table .= "<tr class='active'><th>" . $tmp[0] . "</th><th>" . $tmp[1] . "</th><th>" . $tmp[2] . "</th><th>" . $tmp[3] . "</th><th>" . $tmp[4] . "</th></tr>";
}
$table .= "<tr class='active' style='font-weight: bold'><td>Всего</td><td>$delivered_sum</td><td>$rejected_sum</td><td>$sent_sum</td><td>$postponed_sum</td></tr>";

?>
<?include 'head.php'?>

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
                        <input id="datetimepicker" name="start_notification" value="<?=isset($_GET['start_notification'])?$_GET['start_notification']:date('d.m.Y',time()-60*60*24*7)?>" class="form-control" required placeholder="Клик для выбора">
                    </div>
                    <div class="col-md-3">
                        Дата по
                        <input id="datetimepicker2" name="finish_notification" value="<?=isset($_GET['finish_notification'])?$_GET['finish_notification']:date('d.m.Y',time()-60*60)?>" class="form-control" required placeholder="Клик для выбора">
                    </div>
                    <div class="col-md-3"><br>
                        <input type="submit" value="Открыть" class="btn btn-primary">
                        <!--a href="sharing/<?=trim($source_detail[1])?>" class="btn btn-primary" title="Детальный отчет"> <span class="icon-download3"></span> <span class="icon-file-excel"></span> </a-->
                    </div>
                </div>
                <input type="hidden" name="target" value="reportemail">
                <input type="hidden" name="user" value="<?=trim($_COOKIE['id'])?>">
            </form>
        </div>
    </div>

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
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                        }
                    }
                },
                legend: {
                    align: 'right',
                    x: -30,
                    verticalAlign: 'top',
                    y: 25,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                            style: {
                                textShadow: '0 0 3px black'
                            }
                        }
                    }
                },
                series: [{
                    name: 'delivered',
                    data: [<?php echo $delivered; ?>]
                }, {
                    name: 'rejected',
                    data: [<?php echo $rejected; ?>]
                }, {
                    name: 'sent',
                    data: [<?php echo $sent; ?>]
                }, {
                    name: 'postponed',
                    data: [<?php echo $postponed; ?>]
                }]
            });
        });</script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
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
        yearStart: 2016,
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
        yearStart: 2016,
    });
    //$('#datetimepicker').datetimepicker({value:'2016/04/15 05:03',step:10});

</script>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/view/footer.php';
?>
