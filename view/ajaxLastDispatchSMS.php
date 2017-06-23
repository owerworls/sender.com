<?php
$user = $_COOKIE['id'];

$data = file("http://172.20.128.3:8080/messenger/BillingLastNotification?user=2");
$source = explode("=", $data[0]);

$data = file($_SERVER['DOCUMENT_ROOT'].'/sharing/' . trim($source[1]));

$data=explode(chr(9),$data[0]);

?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<div id="myfirstchart" style="height: 250px;"></div>
<script>
    new Morris.Donut({
        // ID of the element in which to draw the chart.
        element: 'myfirstchart',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [
            { year: 'Доставлен', value: <?=$data[4]?> },
            { year: 'Отклонен', value: <?=$data[5]?> },
            { year: 'Отправлен', value: <?=$data[6]?> },
            { year: 'Просрочен', value: <?=$data[7]?> }
        ],
        // The name of the data record attribute that contains x-values.
        xkey: 'year',
        // A list of names of data record attributes that contain y-values.
        ykeys: ['value'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['Value']
    });
</script>