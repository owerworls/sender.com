<ul class="nav nav-pills">
    <li role="presentation" <?= $target=='report'?'class="active"':''; ?>>
        <a href="?target=report"><i class="fa fa-bar-chart" aria-hidden="true"></i> Общая статистика Viber&SMS</a>
    </li>
    <li role="presentation" <?= $target=='report_detail'?'class="active"':''; ?>>
        <a href="?target=report_detail"><i class="fa fa-file-text-o" aria-hidden="true"></i> Детальная статистика Viber&SMS</a>
    </li>
    <li role="presentation" <?= $target=='reportemail'?'class="active"':''; ?>>
        <a href="?target=reportemail"><i class="fa fa-bar-chart" aria-hidden="true"></i> Статистика Email</a>
    </li>
    <li role="presentation" <?= $target=='reportemail_detail'?'class="active"':''; ?>>
        <a href="?target=reportemail_detail"><i class="fa fa-file-text-o" aria-hidden="true"></i> Email подробно</a>
    </li>
    <?php
    if($_COOKIE['voice']==1){
    ?>
    <li role="presentation" <?= $target=='reportСalls'?'class="active"':''; ?>>
        <a href="?target=reportVoice"><i class="fa fa-volume-control-phone" aria-hidden="true"></i> Звонки подробно</a>
    </li>
    <?
    }
    ?>
</ul>