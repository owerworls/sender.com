<? include 'head.php' ?>

<body>

<div class="container">
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

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/?target=logout">Войти</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="panel panel-default">

        <div class="panel-body">
            <h3>Регистрация</h3>
            <p style="margin:20px 100px; ">
                Уважаемые клиенты, для регистрации в системе рассылок достаточно оставить свое имя, логин и пароль.
            <br>
                После этого, мы вышлем на Ваш email и телефон пароль для доступа к системе.
            <br>
                Если Вам для работы нужен договор и документация для автоматизации рассылок просим перейти по этой <a href="#">ссылке</a>
            </p>
            <div class="row">
                <div class="col-sm-4">

                </div>
                <div class="col-sm-4"><br>
                    <form id="regreg" method="post" action="/">
                        <div class="form-group form-group-sm">

                            <input type="hidden" name="target" value="registration" >

                            <label for="email">Email:</label>
                            <input title="Email" type="email" id="email" class="form-control btn-block" name="email" placeholder="example@mail.com" required>

                            <label for="tel">Телефон:</label>
                            <input title="Телефон в формате +380XXXXXXXXX" type="tel" id="tel" pattern="^\+380\d{9}$"  class="form-control btn-block" name="phone" placeholder="+380XXXXXXXXX" required>

                            <label for="name">Имя:</label>
                            <input title="Имя" class="form-control btn-block" name="name" placeholder="Василий" required>

                            <input type="submit" class="btn btn-primary btn-block" value="Подать заявку">
                        </div>
                        <p style="text-align: right">
                            <a href="/">Войти</a>
                        </p>
                    </form>
                </div>
                <div class="col-sm-4">

                </div>
            </div>
            <br>
            <br>
        </div>

    </div>


</div>
</body>
</html>