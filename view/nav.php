<nav class="navTop">
    <div class="row">
        <div class="hidden-xs col-sm-4 ">
            <img id="logo" class="hidden-xs " src="/img/softline_mobile_logo.jpg" style="width:190px; ">
        </div>
        <div class="col-sm-8 col-xs-12  text-right">
            <ul class="TopMenu">

                <li id="mainMenuIco" class="mainMenu">
                    <a href="/">
                        <i class="fa fa-home fa-2x"></i>
                    </a>
                </li>

                <li id="mainMenuIco" class="mainMenu" style="position: relative">
                    <a href="#" onclick="$('#mainMenu').slideToggle('fast'); return false;">
                        <!--                        <img src="/img/menu.png" alt="menu">-->
                        <span class="glyphicon glyphicon-th" style="font-size: 24px;"></span>
                    </a>

                </li>
                <?php
                $userLogo = $site->getUserLogo();
                ?>
                <li id="userMenuIco" class="userMenu " style="position: relative">
                    <a href="#" onclick="$('#userMenu').slideToggle('fast');  return false;">
                        <div class="imglogo" style="background-image: url(<?= $userLogo ?>);"></div>
                        <?= $_COOKIE['alfanames'] ?>
                    </a>

                    <div class="userMenu" id="userMenu">
                        <table style="margin: 10px">
                            <tr>
                                <td>
                                    <div class="imglogoBig" style="background-image: url(<?= $userLogo ?>);"></div>
                                </td>
                                <td style="vertical-align: top">
                                    <h4 style="margin: 0"><strong><?= $_COOKIE['alfanames'] ?></strong></h4>
                                    <div id="mnuForm_bal" style="display: none; ">
                                        <form action="/" onsubmit=" getInvoiceFromMenu();openGetInvoiceMenu()" id="getInvoiceFormMenu">

                                            <div class="input-group">
                                                <input class="form-control " id="invoiceSumMenu" pattern="^[\d,.]+$"
                                                       title="Пример: 1000.00" required autocomplete="off">
                                                <span class="input-group-btn">
                                                    <input type="submit" class="btn btn-primary" value="OK">
                                                  </span>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="mnuForm_tbl">
                                        <?php
                                        if ($site->paymentType != 0) {
                                            ?><?= $site->getBalance('Баланс: ', ' грн.') ?>
                                            <a href="#" onclick="openGetInvoiceMenu();">Пополнить</a>
                                            <?
                                        }
                                        ?>
                                    </div>

                                </td>
                            </tr>
                        </table>
                        <div class="just-links">
                            <a href="#">Настройки</a>
                            <a href="/?target=alphanames">Альфаимена</a>

                        </div>
                        <a href="/?target=logout" class="btn btn-danger btn-block"><i class="fa fa-sign-out"></i> Выйти</a>
                    </div>
                </li>
                <div class="mainMenu" id="mainMenu">
                    <a href="/?target=message" style="background-color: #dd4b39">
                        <i class="fa fa-mobile fa-2x"></i>
                        <span>Viber, SMS</span>
                    </a>
                    <a href="/?target=email" style="background-color: #3f51b5">
                        <i class="fa fa-at fa-2x"></i>
                        <span>E-mail</span>
                    </a>
                    <a href="/?target=chat" style="background-color: #765ccf; ">
                        <img src="/img/viber.svg" alt="viber" style="margin: 10px 0 3px;">
                        <!--                            <i class="fa fa-commenting-o fa-2x"></i>-->
                        <span>Viber чат</span>
                    </a>
                    <a href="/?target=report">
                        <i class="fa fa-bar-chart fa-2x"></i>
                        <span>Статистика</span>
                    </a>
                    <a href="/?target=report_detail">
                        <i class="fa fa-file-text-o fa-2x"></i>
                        <span>Отчет</span>
                    </a>
                    <a href="/?target=alphanames" style="background-color: #ffa227;">
                        <i class="fa fa-user fa-2x"></i>
                        <span>Альфаимена</span>
                    </a>
                    <a href="/?target=reportemail" style="background-color: #2196f3;  ">
                        <i class="fa fa-at fa-2x"></i>
                        <span>Статистика</span>
                    </a>
                    <a href="/?target=reportemail_detail" style="background-color: #2196f3; ">
                        <i class="fa fa-at fa-2x"></i>
                        <span>Отчет</span>
                    </a>
                </div>
            </ul>
        </div>
    </div>
</nav>

<script>
    $(function () {
        $(document).click(function (event) {
            if ($(event.target).closest("#mainMenuIco").length) return;
            $("#mainMenu").slideUp('fast');
            event.stopPropagation();
        });
    });
</script>
<script>
    $(function () {
        $(document).click(function (event) {
            if ($(event.target).closest("#userMenuIco").length) return;
            $("#userMenu").slideUp('fast');
            event.stopPropagation();
        });
    });
</script>