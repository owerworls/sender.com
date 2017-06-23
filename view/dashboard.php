<? include 'head.php' ?>

    <body>
<div class="container">
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/view/nav.php';
    ?>

    <div class="row">
        <div class="col-sm-4">
            <a href="/?target=message" style="text-decoration: none;">
                <div class="tile-box bg-google">
                    <div class="tile-header">
                        Рассылки
                    </div>
                    <div class="tile-content-wrapper">
                        <table width="100%">
                            <tr>
                                <td class="text-left">

                                    <img src="/img/sms-logo-white.png" class="fa-4x" alt="viber"
                                         style="margin: 0 ; height: 52px;">


                                </td>
                                <td class="text-right">
                                    <div class="tile-content"> Viber, SMS</div>
                                    <p>Массовая рассылка SMS и Viber сообщений по файлам номеров (csv, xls, xlsx)</p>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <span class="tile-footer" href="#">Перейти <i class="fa fa-arrow-right"></i></span>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="/?target=email">
                <div class="tile-box bg-blue-alt">
                    <div class="tile-header">
                        Рассылки
                    </div>
                    <div class="tile-content-wrapper">
                        <table width="100%">
                            <tr>
                                <td class="text-left">
                                    <i class="fa fa-at fa-4x"></i>


                                </td>
                                <td class="text-right">
                                    <div class="tile-content">E-mail</div>
                                    <p>Массовая рассылка по электронной почте по файлам адресов (csv, xls, xlsx)</p>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <span class="tile-footer">Перейти <i class="fa fa-arrow-right"></i></span>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="/?target=chat">
                <div class="tile-box bg-viber" style="color: white;">
                    <div class="tile-header">
                        <i class="fa fa-commenting-o"></i> Public accounts
                    </div>
                    <div class="tile-content-wrapper">
                        <table width="100%">
                            <tr>
                                <td class="text-left"><img src="/img/viber.svg" class="fa-4x" alt="viber"
                                                           style="margin: 10px 5px 3px; height: 52px;"></td>
                                <td class="text-right">
                                    <div class="tile-content">Viber chat</div>
                                    <p>Всего диалогов: <? include $_SERVER['DOCUMENT_ROOT'] . '/classes/Chat.php';
                                        $chat = new Chat($link);
                                        echo $chat->countCorrespondents() ?><br>
                                        Непрочитаных: <span class="badge" style="background-color: #ff8; color: #000; float: right; margin-left: 5px; font-family: Roboto; font-weight: bold; font-size: 14px;"><?
                                        $q = mysqli_query($link, "select * from viberchat where `api_key`='{$_COOKIE['chat_key']}' AND my_seen=0  GROUP BY sender_name ");
                                        $r = mysqli_num_rows($q);
                                        echo $r;
                                        ?></span></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <span class="tile-footer">Перейти <i class="fa fa-arrow-right"></i></span>
                </div>
            </a>
        </div>

    </div>
    <!-- TODO row 2-->
    <div class="row">

        <div class="col-sm-8  col-xs-12">
            <?php
            $user = $_COOKIE['id'];

            $data = file("http://172.20.128.3:8080/messenger/BillingLastNotification?user=$user");
            $source = explode("=", $data[0]);

            $data = file($_SERVER['DOCUMENT_ROOT'] . '/sharing/' . trim($source[1]));

            $data = explode(chr(9), $data[0]);
            $total = $data[4] + $data[5] + $data[6] + $data[7];
            ?>
            <a href="/?target=report_detail&openId=<?=$data[0]?>">
                <div class="tile-box bg-green">
                    <div class="tile-header">
                        <i class="fa fa-bar-chart"></i> Отчет по последней рассылке
                    </div>
                    <div class="tile-content-wrapper">
                        <div class="row" style="height: 282px;">
                            <div class="col-xs-12 col-sm-5" style="background-color: #fff;">

                                <link rel="stylesheet" href="/vendor/morris.js-0.5.1/morris.css">
                                <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
                                <script src="/vendor/morris.js-0.5.1/morris.min.js"></script>
                                <div id="myfirstchart" style="height: 282px;"></div>
                                <script>
                                    new Morris.Donut({
                                        colors: [
                                            '#337ab7',
                                            '#ffeb3b',
                                            '#aeecff',
                                            '#f44336'],
                                        // ID of the element in which to draw the chart.
                                        element: 'myfirstchart',
                                        // Chart data records -- each entry in this array corresponds to a point on
                                        // the chart.
                                        data: [
                                            {label: 'Доставлен', value: <?=$data[4]?> },
                                            {label: 'Отклонен', value: <?=$data[5]?> },
                                            {label: 'Отправлен', value: <?=$data[6]?> },
                                            {label: 'Просрочен', value: <?=$data[7]?> }
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
                            </div>
                            <div class="col-xs-12  col-sm-7">
                                <table class="lastSend" style="width: 100%; font-size:12px; margin-top: 10px;">
                                    <tr>
                                        <td style="font-weight:bold; font-family:roboto;">Дата</td>
                                        <td><?= $data[1] ?></td>
                                        <td style="font-weight:bold; font-family:roboto; width: 85px;">Сообщений</td>
                                        <td><?= number_format($total, 0, ',', ' '); ?></td>
                                    </tr>
                                </table>
                                <table class="lastSend">

                                    <tr style="font-size: 12px;">
                                        <td>Viber</td>
                                        <td class="text-nowrap">
                                            <?= $data[2] ?>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 12px;">
                                        <td>SMS</td>
                                        <td class="text-nowrap" style="overflow-x: hidden;">
                                            <?= $data[3] ?>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 18px;">
                                        <td><?= number_format($data[4], 0, ',', ' '); ?></td>
                                        <td style="font-size: 12px;">
                                            Доставлен
                                            <div class="progress" style="width: <?= ceil($data[4] / $total * 100) ?>%; ">
                                                <div class="progress-bar" role="progressbar"
                                                     aria-valuenow="100" aria-valuemin="0"
                                                     aria-valuemax="100" style="width: 100%;">
                                                    <?= $total > 0 ? ceil($data[4] / $total * 100) : 0; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 18px;">
                                        <td><?= number_format($data[5], 0, ',', ' '); ?></td>
                                        <td style="font-size: 12px;">Отклонен
                                            <div class="progress" style="width: <?= ceil($data[5] / $total * 100) ?>%; ">
                                                <div class="progress-bar progress-bar-yellow" role="progressbar" aria-valuenow="100"
                                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                    <?= $total > 0 ? ceil($data[5] / $total * 100) : 0; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 18px;">
                                        <td><?= number_format($data[6], 0, ',', ' '); ?></td>
                                        <td style="font-size: 12px;">Отправлен
                                            <div class="progress" style="width: <?= ceil($data[6] / $total * 100) ?>%;">
                                                <div class="progress-bar progress-bar-aqua" role="progressbar" aria-valuenow="100>"
                                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                    <?= $total > 0 ? ceil($data[6] / $total * 100) : 0; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 18px;">
                                        <td><?= number_format($data[7], 0, ',', ' '); ?></td>
                                        <td style="font-size: 12px;">Просрочен
                                            <div class="progress" style="width: <?= ceil($data[7] / $total * 100) ?>%; ">
                                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100"
                                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                    <?= $total > 0 ? ceil($data[7] / $total * 100) : 0; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <span class="tile-footer">Перейти <i class="fa fa-arrow-right"></i></span>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <div class="col-xs-12">
                    <a href="/?target=alphanames">
                        <div class="tile-box bg-orange">
                            <div class="tile-header">
                                <i class="fa fa-cog"></i> Настройки
                            </div>
                            <div class="tile-content-wrapper">
                                <table width="100%">
                                    <tr>
                                        <td class="text-left"><i class="fa fa-user fa-4x"></i></td>
                                        <td class="text-right">
                                            <div class="tile-content">Альфаимена</div>
                                            <p>Никнейм для идентифифкации <br>отправителя</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <span class="tile-footer">Перейти <i class="fa fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12">
                    <? if ($site->paymentType != 0) { ?>
                            <div class="tile-box bg-orange2">
                                <div class="tile-header">
                                    <i class="fa fa-money"></i> <? /*= $site->getBalance('Баланс: ', ' грн.') */ ?> Баланс
                                </div>
                                <div class="tile-content-wrapper">
                                    <div id="btnForm_bal" style="display: none; margin: 16px 0 17px;">
                                        <form action="/" onsubmit="getInvoice();openGetInvoiceDashboard()" id="getInvoiceForm">
                                            <input class="form-control input-sm" id="invoiceSum" pattern="^[\d,.]+$"   title="Пример: 1000.00"  required autocomplete="off" style="margin: 0 0 6px ">
                                            <input type="submit" class="btn btn-primary btn-sm btn-block" id="invoiceOk" >
                                        </form>
                                    </div>
                                    <table width="100%" id="btnForm_tbl">
                                        <tr>
                                            <td class="text-left"><i class="fa fa-credit-card fa-4x"></i></td>
                                            <td>
                                                <div class="tile-content">
                                                    <?= $site->getBalance() ?>
                                                </div>
                                                <p class="text-right">Выписать счет для пополнения<br> баланса</p>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                                <a class="tile-footer" href="#" onclick="openGetInvoiceDashboard(); return false;">
                                    Пополнить <i class="fa fa-arrow-right"></i>
                                </a>


                            </div>
                        <?
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="tile-box bg-blue2">
                <div class="tile-header">
                    <i class="fa fa-bar-chart"></i> Недельный отчет SMS, Viber
                </div>
                <div class="tile-content-wrapper">
                    <div class="ajaxGeneralSMSReportSmall"></div>
                </div>
                <div class="row tile-footer">
                    <div class="col-xs-6 text-left">

                        <a style="margin-left: 20px; color: #fff;" href="/?target=report">Статистика <i
                                    class="fa fa-arrow-right"></i></a></div>
                    <div class="col-xs-6 text-right"><a style="margin-left: 20px; color: #fff;" href="/?target=report_detail">Детальный
                            отчет <i class="fa fa-arrow-right"></i></a></div>
                </div>

            </div>

        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="tile-box bg-blue2">
                <div class="tile-header">
                    <i class="fa fa-bar-chart"></i> Email
                </div>
                <div class="tile-content-wrapper">
                    <div class="ajaxGeneralEmailReportSmall"></div>
                </div>

                <div class="row tile-footer">
                    <div class="col-xs-6 text-left">
                        <a style="margin-left: 20px; color: #fff;" href="/?target=reportemail">Статистика <i
                                    class="fa fa-arrow-right"></i></a></div>
                    <div class="col-xs-6 text-right"><a style="margin-left: 20px; color: #fff;" href="/?target=reportemail_detail">Детальный
                            отчет <i class="fa fa-arrow-right"></i></a></div>
                </div>
            </div>
        </div>

    </div>

    <script>
        ajaxGeneralSMSReportSmall();
        ajaxGeneralEmailReportSmall()
    </script>

</div>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/view/footer.php';
?>