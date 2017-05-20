<? include 'head.php' ?>

<body>

<div class="container">
    <? include 'nav.php'; ?>
    <div class="panel panel-default">
        <div class="panel-body">

            <table class="table table-hovered table-condensed">
                <tr>
                    <th>Ник</th>
                    <th>Канал</th>
                </tr>
                <?php
                $channels=array('SMS','Viber','SMS&Viber');
                $q = mysqli_query($link, "select * from codec_accounts WHERE account_id='{$_COOKIE['id']}' and codec_id!=2");
                while ($r = mysqli_fetch_array($q)):
                    ?>
                <tr><td><?=$r['alfanames']?></td><td><?=$channels[$r['codec_id']]?></td></tr>
                <?
                endwhile;
                ?>
            </table>
            <a class="btn btn-primary" href="/?target=alphanames_new">Зарегистрировать</a>
        </div>
    </div>
</div>

