<? include 'head.php' ?>

    <body>
<div class="container">

<?php
include $_SERVER['DOCUMENT_ROOT'].'/view/nav.php';
?>
    <!-- ///////////////////////////////////// nav ///////////////////////////-->
<?php

if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login']))
    mkdir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login']);

if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/"))
    mkdir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/");

if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/pictures/"))
    mkdir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/pictures/");

if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/logo/"))
    mkdir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/logo/");

?>
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a href="?target=message"><i class="fa fa-commenting-o" aria-hidden="true"></i>
                        SMS&Viber</a></li>
                <li role="presentation"><a href="?target=email"><i class="fa fa-envelope-o" aria-hidden="true"></i> Email</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" id="group1">
                <div class="panel-body">
                    <h3>Файл:</h3>
                    <form id="upload" method="post" action="/handler.php?target=uploadRecipientsList&for=sms" enctype="multipart/form-data">
                        <?php
                        if (isset($_COOKIE['filename'])) {
                            echo "<span class='h4'>" . $_COOKIE['filename_display'] . "</span><br><br>";
                            echo "<!--span class='h4'> количество записей: " . (count($fileList) - 1) . "</span-->";
                            echo "<a style='width: 140px;' class=\"btn btn-primary \" href=\"/handler.php?target=deleteRecipientsList&for=sms\"><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i> Удалить</a>";
                        } else {
                            ?>
                            <span class="btn btn-primary " style="text-align: center; overflow: hidden; height: 32px; width: 140px;">
                                <span> <i class="fa fa-folder-open" aria-hidden="true"></i> Выбрать файл</span>
                                <input type="file" name="file" onchange="$('#upload').submit();"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                       style="margin-top: -29px; margin-left:-20px; -moz-opacity: 0; filter: alpha(opacity=0); opacity:0;  font-size: 15px; height: 220%;">
                            </span>
                            <?php
                        }
                        ?>
                    </form>
                    <br><br>
                    <h3>Поля файла</h3>
                    <script>
                        focusfield = '#phone';
                    </script>
                    <select class="form-control" name="structure" id="structure" size="4" style="min-height:209px;"
                            ondblclick="
                            if(focusfield=='#phone'){
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
                        $fields = $site->getRecipientsList('sms');
                        foreach ($fields as $field):
                            echo "<option>" . $field . "</option>";
                        endforeach;
                        ?>
                    </select>
                    <script>
                        $('#structure').get(0).selectedIndex = 0;
                    </script>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <!--============================== SEND FORM ==================================-->
                    <form id="sender" method="get" action="/" onsubmit="showPrepSendSMS(); return false;">
                        <input type="hidden" name="file" id="file" value="<?= $_COOKIE['filename'] ?>">
                        <input type="hidden" name="file_display" id="file_display" value="<?= $_COOKIE['filename_display'] ?>">
                        <input type="hidden" name="user" id="user" value="<?= $_COOKIE['id'] ?>">
                        <input type="hidden" name="target" value="sendSMS">
                        <h3>Параметры</h3>
                        Канал отправки
                        <select class="form-control input-sm" name="type" id="type"
                                onchange="
                            $('#group2, #group3').css('display','none');
                            if($(this).val()=='0') {
                                $('#group2').css('display','block');
                                $('#textSMS').attr('required',true);
                                $('#textVer').attr('required',false);
                            }
                            if($(this).val()=='1') {
                                $('#group3').css('display','block');
                                $('#textSMS').attr('required',false);
                                $('#textVer').attr('required',true);
                            }
                            if($(this).val()=='2') {
                                $('.panel.panel-default').css('display','block');
                                $('#textSMS').attr('required',true);
                                $('#textVer').attr('required',true);
                            }
                            ">
                            <option value="1" selected>Viber</option>
                            <option value="0">SMS</option>
                            <option value="2">SMS&Viber</option>
                        </select>
                        <br>
                        Дата и время отправки<input id="datetimepicker" type="text" name="start_date" class="form-control input-sm"
                                                    required value="<?= date('d.m.Y H:i', time()) ?>"><br>

                        Альфаимя
                        <div class="input-group">
                            <select name="alfaname" id="alfaname" class="form-control input-sm" title="Alphaname" required>
                                <?php
                                $options = explode(',', $_COOKIE['alfanames']);
                                foreach ($options as $option):
                                    echo "<option>$option</option>";
                                endforeach;
                                ?>
                            </select>

                            <span class="input-group-btn">
                              <a href="/?target=alphanames" class="btn btn-primary btn-sm"
                                 type="button" style="width: 70px;">Добавить</a>
                          </span>
                        </div><!-- /input-group -->

                        <br>
                        Тестовый номер
                        <div class="input-group">
                            <input style="width: 100%;" type="text" name="testPhone" id="testPhone" class="form-control input-sm">
                            <span class="input-group-btn">
                              <button class="btn btn-primary btn-sm" id="testSendSMS" onclick="sendTestSMS($('#file').val()); return false;"
                                      type="button" style="width: 70px;">Тест
                              </button>
                          </span>
                        </div><!-- /input-group -->
                        <br>
                        <input type="submit" class="btn btn-success btn-block" value="Отправить" onmousedown="CKEDITOR.instances.textViber.updateElement();
                            $('#textVer').val($('#textViber').val());">

                </div>
            </div>

        </div>
        <!--=========================== second column ========================-->
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Поле телефона</h3>

                    <button class="btn btn-primary"
                            onclick="$('#phone').val($('#structure').val()); return false;">
                        <i class="fa fa-indent" aria-hidden="true"></i> Телефон
                    </button>


                    <input type="text" name="phone" value="<?= count($fields) == 1 ? $fields[0] : ''; ?>"
                           id="phone" class="form-control input-sm" title="phone" onfocus="focusfield='#phone'" required>

                </div>
            </div>

            <div class="panel panel-default" id="group2">
                <div class="panel-body">
                    <h3>Текст для SMS</h3>

                    <button class="btn btn-primary"
                            onclick="
                            $('#textSMS').val($('#textSMS').val()+' {'+$('#structure').val()+'} ');
                            $('#lenghttextSMS').html($('#textSMS').val().length);
                            $('#textSMS').focus();
                            return false;"><i class="fa fa-indent" aria-hidden="true"></i> Поле
                    </button>
                    <span id="lenghttextSMS"><b id="smsCount"></b> SMS / <b id="smsLength"></b> символов осталось</span>

                    <textarea name="sms_text" class="form-control textarea" id="textSMS" style="height:100px;"

                              onfocus="focusfield='#textSMS'"></textarea>
                </div>
            </div>

            <div class="panel panel-default" id="group3">
                <div class="panel-body">
                    <h3>Текст для Viber</h3>
                    <div class="btn-group" role="group" aria-label="...">
                        <button class="btn btn-primary "
                                onclick="
                            CKEDITOR.instances['textViber'].setData(CKEDITOR.instances['textViber'].getData()+' {'+$('#structure').val()+'} ');
                                        return false;"
                                onfocus="focusfield='#textViber"><i class="fa fa-indent" aria-hidden="true"></i> Поле
                        </button>

                        <span class="btn btn-primary" onclick="openWindowImg()"><i class="fa fa-picture-o" aria-hidden="true"></i> Картинка</span>
                        <span class="btn btn-primary" onclick="openWindowLink()"><i class="fa fa-link" aria-hidden="true"></i> Кнопка</span>
                    </div>

                    <textarea name="mess_text" id="textViber"></textarea>
                    <input name="me_text" id="textVer" required style="height: 1px; border: 0; opacity: 0; width:100%; margin-top: -2px;">

                    <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>

                    <script>
                        // Turn off automatic editor creation first.
                        // CKEDITOR.disableAutoInline = false;
                        CKEDITOR.replace('textViber', {
                            uiColor: '#f8f8f8',
                            removeButtons: 'Underline,anchor',
                            filebrowserBrowseUrl: '/ckeditor/browse.php',
                            filebrowserUploadUrl: '/ckeditor/uploadimg.php',
                            filebrowserWindowWidth: '640',
                            filebrowserWindowHeight: '480',
                            height: '300',
                            removePlugins: 'iframe,smiley,specialchar,table,tabletools,magicline,flash,pagebreak,horizontalrule',
                            toolbarGroups: [
                                {name: 'links'},
                                {name: 'insert'}
                            ]
                        });
                    </script>


                    <div id="dialog" title="Вставить изображение"></div>
                </div>
            </div>

        </div>


        </form>
    </div>

    <script src="../js/jquery.datetimepicker.full.js"></script>
    <script>

        $.datetimepicker.setLocale('ru');

        $('#datetimepicker').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'ru',
            formatTime: 'H:i',
            formatDate: 'd.m.Y',
            format: 'd.m.Y H:i'
        });

    </script>
    <script>
        //Plugin
        (function ($) {
            $.fn.smsArea = function (options) {

                var
                    e = this,
                    cutStrLength = 0,

                    s = $.extend({

                        cut: true,
                        maxSmsNum: 4,
                        interval: 20,

                        counters: {
                            message: $('#smsCount'),
                            character: $('#smsLength')
                        },

                        lengths: {
                            ascii: [160, 306, 459, 612],
                            unicode: [70, 134, 201, 265]
                        }
                    }, options);


                e.keyup(function () {

                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(function () {

                        var
                            smsType,
                            smsLength = 0,
                            smsCount = -1,
                            charsLeft = 0,
                            text = e.val(),
                            isUnicode = false;

                        for (var charPos = 0; charPos < text.length; charPos++) {
                            switch (text[charPos]) {
                                case "\n":
                                case "[":
                                case "]":
                                case "\\":
                                case "^":
                                case "{":
                                case "}":
                                case "|":
                                case "€":
                                    smsLength += 2;
                                    break;

                                default:
                                    smsLength += 1;
                            }

                            //!isUnicode && text.charCodeAt(charPos) > 127 && text[charPos] != "€" && (isUnicode = true)
                            if (text.charCodeAt(charPos) > 127 && text[charPos] != "€")
                                isUnicode = true;
                        }

                        if (isUnicode) smsType = s.lengths.unicode;
                        else                smsType = s.lengths.ascii;

                        for (var sCount = 0; sCount < s.maxSmsNum; sCount++) {
                            console.log('s.maxSmsNum ' + s.maxSmsNum);
                            console.log('sCount ' + sCount);
                            console.log('smsType[sCount] ' + smsType[sCount]);
                            cutStrLength = smsType[sCount];
                            if (smsLength <= smsType[sCount]) {

                                smsCount = sCount + 1;
                                charsLeft = smsType[sCount] - smsLength;
                                break
                            }
                        }

                        if (s.cut) e.val(text.substring(0, cutStrLength));
                        smsCount == -1 && (smsCount = s.maxSmsNum, charsLeft = 0);

                        s.counters.message.html(smsCount);
                        s.counters.character.html(charsLeft);

                    }, s.interval)
                }).keyup()
            }
        }(jQuery));

        //Start
        $(function () {
            $('#textSMS').smsArea({maxSmsNum: 4});
        });


    </script>
    <!-- Modal -->
    <div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade " id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <div id="dialog_link" title="Вставить ссылку">
        <form>
            URL
            <input class="form-control input-sm" name="url" id="link_url" title="url" required autocomplete="off" style="margin: 0 0 6px ">
            Название кнопки
            <input class="form-control input-sm" name="title" id="link_title" title="title" required autocomplete="off"
                   style="margin: 0 0 16px ">
            <input type="submit" class="btn btn-primary btn-sm btn-block" value="OK"
                   onclick="insertLinkViber($('#link_url').val(),$('#link_title').val()); return  false;">
        </form>
    </div>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/view/footer.php';
?>