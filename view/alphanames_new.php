<? include 'head.php' ?>

<body>

<div class="container">
    <? include 'nav.php'; ?>
    <div class="panel panel-default">
        <div class="panel-body">

            <form action="/handler.php" enctype="multipart/form-data" method="post">
                <input type="hidden" name="target" value="addAlphaName">
                <input type="hidden" name="account_id" value="<?=$_COOKIE['id']?>">
                <table style="  margin: auto">
                    <tr>
                        <td>Канал отправки</td>
                        <td width="400"><select name="codec_id" class="form-control" title="Канал отправки">
                                <option value="1" selected>Viber</option>
                                <option value="0">SMS</option>
                                <option value="2">SMS&Viber</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="viber sms">
                        <td>
                            Ник отправителя
                        </td>
                        <td>
                            <input type="text" name="alfanames" id="alphaName" class="form-control" required title="Ник отправителя">
                        </td>
                    </tr>
                    <tr class="viber sms">
                        <td>
                            Юридическое название компании
                        </td>
                        <td>
                            <input type="text" name="company_name" class="form-control" required title="Юридическое название компании">
                        </td>
                    </tr class="viber sms">
                    <tr>
                        <td>
                            ИНН / ЕГРПОУ
                        </td>
                        <td>
                            <input type="text" name="inn_edrpou" class="form-control" required title="ИНН / ЕГРПОУ">
                        </td>
                    </tr>
                    <tr class="viber">
                        <td>
                            Юридический адрес
                        </td>
                        <td>
                            <input type="text" name="address" class="form-control" required title="Юридический адрес">
                        </td>
                    </tr>
                    <tr class="viber sms">
                        <td>
                            Адрес сайта
                        </td>
                        <td>
                            <input type="text" name="web" class="form-control" required title="Адрес сайта">
                        </td>
                    </tr>
                    <tr class="viber">
                        <td>
                            Темы для рассылок
                        </td>
                        <td>
                            <textarea name="messaging_topic" class="form-control" required title="Темы для рассылок"></textarea>
                        </td>
                    </tr>
                    <tr class="viber">
                        <td>
                            Примеры текстов сообщений
                        </td>
                        <td>
                            <textarea name="message_examples" class="form-control" required title="Примеры текстов сообщений"></textarea>
                        </td>
                    </tr>
                    <tr class="viber">
                        <td>
                            Лого
                        </td>
                        <td>
                            <table style="width: 100%">
                                <tr>
                                    <td valign="bottom"><img id="logo130" width="128" height="128" src="/img/130x130.png" title="130x130"></td>
                                    <td valign="bottom"><img id="logo100" width="100" height="100" src="/img/100x100.png" title="100x100"></td>
                                    <td valign="bottom"><img id="logo65" width="65" height="65" src="/img/65x65.png" title="65x65"></td>
                                    <td valign="bottom"><img id="logo50" width="50" height="50" src="/img/50x50.png" title="50x50"></td>
                                </tr>
                            </table>

                            <input class="btn btn-default " type="file" name="logoImg" id="logoImg" accept="image/png,image/jpeg" required
                                   style="width: 100%">

                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                        <td>
                            <br>
                            <input type="submit" class="btn btn-primary">
                        </td>
                    </tr>

                </table>
            </form>


        </div>
    </div>
</div>

<script>


    $('select').on('change', function () {

        var type=$('select').val();
        if (type == 1) {
            $('.viber').show();
            $('.viber input').prop('required',true);
            $('.viber textarea').prop('required',true);

            $('.sms').show();
            $('.sms input').prop('required',true);
            $('.sms textarea').prop('required',true);
        }
        if (type == 0) {
            $('.viber').hide();
            $('.viber input').prop('required',false);
            $('.viber textarea').prop('required',false);
            $('.sms').show();
            $('.sms input').prop('required',true);
            $('.sms textarea').prop('required',true);
        }
        if (type == 2) {
            $('.sms').hide();
            $('.sms input').prop('required',false);
            $('.sms textarea').prop('required',false);
            $('.viber').show();
            $('.viber input').prop('required',true);
            $('.viber textarea').prop('required',true);
        }

    })
</script>
<script>
    var listen = function(element, event, fn) {
        return element.addEventListener(event, fn, false);
    };

    listen(document, 'DOMContentLoaded', function() {

        var fileInput = document.querySelector('#logoImg');

        listen(fileInput, 'change', function() {
            var files = fileInput.files;
            if (files.lenght == 0) {
                return;
            }
            for(var i = 0; i < files.length; i++) {
                generatePreview(files[i]);
            }
        });

        var generatePreview = function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var dataUrl = e.target.result;
                var image135 = document.getElementById('logo130');
                var image100 = document.getElementById('logo100');
                var image65 = document.getElementById('logo65');
                var image50 = document.getElementById('logo50');
                image135.src = dataUrl;
                image100.src = dataUrl;
                image65.src = dataUrl;
                image50.src = dataUrl;
            };
            reader.readAsDataURL(file);
        };
    });
</script>