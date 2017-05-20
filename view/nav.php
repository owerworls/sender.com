<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand"><img id="logo" src="../img/softline_mobile_logo.jpg" style="width:190px; margin-top:-16px; margin-left: -16px;">
                <!--?=$_COOKIE['login']?--></span>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                if ($_COOKIE['user_type'] == 0) {
                    ?>
                    <li><a href="?target=message">Рассылки</a></li>
                    <li><a href="?target=report">Статистика</a></li>
                    <?
                }

                if ($site->chatKey != null) {
                    ?>
                    <li><a href="?target=chat">Чат <span id="newMessage" class="badge top"></span></a></li>
                    <?
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if ($site->paymentType != 0) {
                    ?>
                    <li>
                        <a href="#" onclick="openGetInvoice();">
                            <span class="fa-stack" style="margin: -6px 0;">
                                <i class="fa fa-circle-thin fa-stack-2x"></i>
                                <i class="fa fa-plus fa-stack-1x "></i>
                            </span>
                        </a>
                    </li>
                    <?
                }

                if ($_COOKIE['user_type'] == 0) {
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Настройки
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Баланс</a></li>
                            <li><a href="/?target=alphanames">Альфаимена</a></li>
                        </ul>
                    </li>
                    <?
                }
                ?>

                <li><a href="/?target=logout">Выйти</a></li>

            </ul>
            <p class="navbar-text navbar-right"><span class="badge top"><?= $site->getBalance() ?></span>
            </p>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
