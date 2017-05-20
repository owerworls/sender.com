<?include 'head.php'?>

<body>
<div class="container">
    <form id="login" method="get" action="/handler.php" >
        <img id="logo" src="../img/softline_mobile_logo.png">
        <div class="form-group form-group-sm">
            <input type="hidden" name="target" value="login">
            <input title="Login name"  type="text" class="form-control" name="user" placeholder="Логин">
            <input type="password" class="form-control" name="pwd" placeholder="Пароль">
            <input type="submit" class="btn btn-primary btn-block" value="Войти" >
        </div>
        <p style="text-align: right">
            <a href="/registration/">Регистрация</a>
        </p>
    </form>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/view/footer.php';
?>