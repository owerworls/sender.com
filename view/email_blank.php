<? include 'head.php' ?>

<body>
<!--div id="page-preloader"><span class="spinner"></span></div>
<script>$(window).on('load', function () {
        var $preloader = $('#page-preloader'),
            $spinner = $preloader.find('.spinner');
        $spinner.fadeOut();
        $preloader.fadeOut('fast');
        //$preloader.delay(5000).fadeOut('fast');
    });</script-->
<div class="container">

    <?php
    include 'view/nav.php';
    ?>
    <!-- ///////////////////////////////////// nav ///////////////////////////-->

    <div class="panel panel-default" >
        <div class="panel-body" >
            <ul class="nav nav-pills">
                <li role="presentation"><a href="?target=message"><i class="fa fa-commenting-o" aria-hidden="true"></i> SMS&Viber</a></li>
                <li role="presentation" class="active"><a href="?target=email"><i class="fa fa-envelope-o" aria-hidden="true"></i>  Email</a></li>
            </ul>
        </div>
    </div>

    <div class="panel panel-default" id="group1">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <h3>Файл:</h3>
                    <form id="upload" method="post" action="/handler.php?target=uploadRecipientsList&for=email" enctype="multipart/form-data">
                        <?php
                        if (isset($_COOKIE['emailsfile'])) {
                            echo "<span class='h4'>" . $_COOKIE['emailsfile_display'] . "</span><br><br>";
                            echo "<!--span class='h4'> количество записей: " . (count($fileList) - 1) . "</span-->";
                            echo "<a style='width: 140px;' class=\"btn btn-primary\" href=\"/handler.php?target=deleteRecipientsList&for=email\"><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i> Удалить</a>";
                        } else {
                            ?>
                            <span class="btn btn-primary" style="text-align: center; overflow: hidden; height: 34px; width: 140px;">
                                <span> <i class="fa fa-folder-open" aria-hidden="true"></i> Выбрать файл</span>
                                <input type="file" name="file" onchange="$('#upload').submit();" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                       style="margin-top: -29px; margin-left:-20px; -moz-opacity: 0; filter: alpha(opacity=0); opacity:0;  font-size: 15px; height: 220%;">
                            </span>
                            <?php
                        }
                        ?>
                    </form>
                </div>
                <div class="col-md-4">
                    <h3>Поля файла</h3>
                    <script>
                        focusfield = '#email';
                    </script>
                    <select class="form-control" name="structure" id="structure" size="5"
                            ondblclick="
                            if(focusfield=='#email'){
                                $(focusfield).val($('#structure').val());
                            }
                            else
                            {
                                $(focusfield).val($(focusfield).val()+' {'+$('#structure').val()+'} ');
                                $('#lenght'+focusfield.substr(1)).html($(focusfield).val().length);
                                $(focusfield).focus();
                            }
                            ">
                        <?
                        //получаем список полей
                        $fields = $site->getRecipientsList('email');
                        foreach ($fields as $field):
                            echo "<option>" . $field . "</option>";
                        endforeach;
                        ?>
                    </select>
                    <script>
                        $('#structure').get(0).selectedIndex = 0;
                    </script>
                </div>
                <!--============================== SEND FORM =================================-->
                <form id="senderMail" method="post" action="/" onsubmit="sendMail(); return false;">
                    <input type="hidden" name="file" value="<?= $_COOKIE['emailsfile'] ?>">
                    <input type="hidden" name="user" value="<?= $_COOKIE['id'] ?>">
                    <input type="hidden" name="target" value="sendMail">
                    <div class="col-md-3">
                        <a href="#" onclick="$('#email').val($('#structure').val()); return false;">Поле email &gt;&gt;</a>

                        <input class="form-control input-sm" type="text" name="email" id="email" value="<?= count($fields) == 1 ? $fields[0] : ''; ?>" onfocus="focusfield='#phone'" required>

                        Дата и время отправки<input id="datetimepicker" type="text" name="start_date" class="form-control input-sm"
                                                    required value="<?= date('d.m.Y H:i', time()) ?>">
                        Тема письма
                        <input name="subj" id="subj" class="form-control input-sm" title="Theme" required>

                    </div>
                    <div class="col-md-3">
                        Email отправителя
                        <input type="email" name="from" class="form-control input-sm" title="Email" required>
                        Тестовый email
                        <div class="input-group">
                            <input style="width: 100%;" type="text" class="form-control input-sm">
                            <span class="input-group-btn">
                                <button class="btn btn-primary btn-sm" type="button" style="width: 70px;">Тест</button>
                            </span>
                        </div><!-- /input-group -->
                        <br>
                        <input type="submit" class="btn btn-success btn-block btn-sm" value="Отправить">
                    </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h3>Текст E-mail</h3>

                    <button class="btn btn-primary " style="width: 140px;"
                            onclick="CKEDITOR.instances['textEmail'].setData(CKEDITOR.instances['textEmail'].getData()+' {'+$('#structure').val()+'} ');
                        return false;"><i class="fa fa-indent" aria-hidden="true"></i> Поле
                    </button>
                </div>
                <div class="col-md-6">

                </div>
            </div>

            <textarea name="textEmail" class="form-control textarea" id="textEmail"></textarea>

            <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
            <script type="text/javascript">
                CKEDITOR.replace('textEmail', {
                    uiColor: '#f8f8f8',
                    filebrowserBrowseUrl: '/ckeditor/browse.php',
                    filebrowserUploadUrl: '/ckeditor/uploadimg.php',
                    filebrowserWindowWidth: '640',
                    filebrowserWindowHeight: '480'
                    // removePlugins : 'save,flash,forms,smiley,newpage,specialchar,language,bidirtl,about,iframe,pagebreak,horizontalrule,blockquote,div'
                });
            </script>
        </div>
    </div>

    </form>
</div>
<!-- Modal -->
<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Ответ сервера</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <a href="?target=report" class="btn btn-default">Статистика</a>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>


<script src="../js/jquery.datetimepicker.full.js"></script>

<script>
    $.datetimepicker.setLocale('ru');
    $('#datetimepicker').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'ru',
        disabledDates: ['1986/01/08', '1986/01/09', '1986/01/10'],
        startDate: '2016/08/19',
        formatTime: 'H:i',
        formatDate: 'd.m.Y',
        format: 'd.m.Y H:i',
        yearStart: 2016
    });
    //$('#datetimepicker').datetimepicker({value:'2016/04/15 05:03',step:10});
</script>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/view/footer.php';
?>