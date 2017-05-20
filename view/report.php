<? include 'head.php' ?>

    <body>
<div class="container">

    <? include 'nav.php'; ?>

    <div class="panel panel-default">
        <div class="panel-body">
           <? include $_SERVER['DOCUMENT_ROOT'].'/view/reportNav.php'; ?>

            <div class="row">
                <div class="col-md-3">
                    Дата с
                    <input id="datetimepicker" name="start_notification"
                           value="<?= isset($_GET['start_notification']) ? $_GET['start_notification'] : date('d.m.Y', time() - 60 * 60 * 24 * 7) ?>"
                           class="form-control dateFrom" required placeholder="Клик для выбора">
                </div>
                <div class="col-md-3">
                    Дата по
                    <input id="datetimepicker2" name="finish_notification"
                           value="<?= isset($_GET['finish_notification']) ? $_GET['finish_notification'] : date('d.m.Y', time() - 60 * 60) ?>"
                           class="form-control dateTo" required placeholder="Клик для выбора">
                </div>
                <div class="col-md-3"><br>
                    <button class="btn btn-primary" onclick="ajaxGeneralSMSReport()">Открыть</button>
                </div>
            </div>
        </div>
    </div>

    <div style="min-height: 300px; position: relative">
        <div class="ajaxHere"></div>
        <div class="preloader"></div>
    </div>

</div>


<script src="/js/highcharts.js"></script>
<script src="/js/exporting.js"></script>
<script src="/js/jquery.datetimepicker.full.js"></script>
<script>

    $.datetimepicker.setLocale('ru');


    $('#datetimepicker').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'ru',
        formatTime: 'H:i',
        formatDate: 'd.m.Y',
        format: 'd.m.Y',
        timepicker:false
    });
    $('#datetimepicker2').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'ru',
        formatTime: 'H:i',
        formatDate: 'd.m.Y',
        format: 'd.m.Y',
        timepicker:false
    });

    ajaxGeneralSMSReport()
</script>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/view/footer.php';
?>