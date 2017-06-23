<?php
$user = $_COOKIE['id'];
$dateFrom = $_GET['dateFrom'] != '' ? str_replace('.', '', $_GET['dateFrom']) . '000000' : date('dmY', time()- 60 * 60 * 24 * 7) . '000000' ;
$dateTo = $_GET['dateTo'] != '' ? str_replace('.', '', $_GET['dateTo']) . '235959' : date('dmY', time() ). '235959';

$data = file("http://172.20.128.3:8080/smtp/billing?user=$user&start_notification=$dateFrom&finish_notification=$dateTo");

$source = explode("=", $data[0]);

$data = file('sharing/' . trim($source[1]));
$delivered_sum = 0;
$rejected_sum = 0;
$postponed_sum = 0;
$sent_sum = 0;
foreach ($data as $line_num => $line) {
    $tmp = explode(chr(9), $line);
    if ($line_num > 0) {
        $date = trim($tmp[0]) ;
        $delivered = trim($tmp[1]) ;
        $delivered_sum = $delivered_sum + (int)trim($tmp[1]);
        $rejected = trim($tmp[2]) ;
        $rejected_sum = $rejected_sum + (int)trim($tmp[2]);
        $sent = trim($tmp[3]) ;
        $sent_sum = $sent_sum + (int)trim($tmp[3]);
        $postponed = trim($tmp[4]) ;
        $postponed_sum = $postponed_sum + (int)trim($tmp[4]);
        $morrisData.="{ y: '$date', a: $delivered, b: $rejected, c: $sent, d: $postponed },";
    };
}
$morrisData=substr($morrisData,0,-1);
?>
<div id="emailWeek" style="height: 200px;"></div>

<script>
    new Morris.Line({
        lineColors: [
            '#337ab7',
            '#ffeb3b',
            '#aeecff',
            '#f44336'],
        // ID of the element in which to draw the chart.
        element: 'emailWeek',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [
            <?=$morrisData ?>
        ],
        xkey: 'y',
        ykeys: ['a', 'b', 'c', 'd'],
        labels: ['Доставлен', 'Отклонен', 'Отправлен', 'Просрочен']
    });
</script>
