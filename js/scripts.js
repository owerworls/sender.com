function strip_tags(str) {	// Strip HTML and PHP tags from a string
    //
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

    return str.replace(/<\/?[^>]+>/gi, '');
}

function viberCurrect() {
    CKEDITOR.instances.textViber.updateElement();

    var text = $('#textViber').val();
    var textWOTags = strip_tags(text);
    var textTrim = textWOTags.trim();

    return !((textTrim.length > 0) && (text.indexOf('http') > 0) && (text.indexOf('href') < 0));

}

function sendTestSMS(file) {
    CKEDITOR.instances.textViber.updateElement();

    $.post('/', {
        target: 'sendTestSMS',
        file: $('input#file').val(),
        user: $('input#user').val(),
        phone: $('input#testPhone').val(),
        sms_text: $('#textSMS').val(),
        mess_text: $('#textViber').val(),
        type: $('#type').val(),
        alfaname: $('#alfaname').val()
    }, function (data) {
        $('.modal-body').html(data);
        $('#myModal').modal({keyboard: true});
    });
}
function sendSMS() {

    $('#myModal').modal('hide');
    CKEDITOR.instances.textViber.updateElement();


    var $form = $('#sender');
    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        data: $form.serialize(),
        success: function (data) {
            $('.modal-body').html(data);
            if (~data.indexOf("Рассылка запущена. Сообщения поставлены")) {
                $('.modal-footer').html("<a href=\"/?target=report\" class=\"btn btn-default\">Статистика</a><a class=\"btn btn-primary\" href=\"/handler.php?target=deleteRecipientsList&for=sms\"  >Ok</a>");
            }
            else {
                $('.modal-footer').html("<button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\">Close</button>");
            }

            $('#myModal').modal({keyboard: true});
        },
        error: function (xhr, str) {
            $('.modal-body').html(xhr.responseCode);
            $('#myModal').modal({keyboard: true});
        }
    });
}

function showPrepSendSMS() {
    CKEDITOR.instances.textViber.updateElement();
    console.log(viberCurrect());
    $('#textVer').val($('#textViber').val());
    outhtml = '<strong>Файл</strong>: ' + $('#file_display').val();
    outhtml = outhtml + '<br><strong>Поле телефона</strong>: ' + $('#phone').val();
    outhtml = outhtml + '<br><strong>Ник отправителя</strong>: ' + $('#alfaname').val();
    outhtml = outhtml + '<br><strong>Время отправки</strong>: ' + $('#datetimepicker').val();
    if ($('#type').val() == '2') {
        outhtml = outhtml + '<br><strong>Канал отправки</strong>: SMS&Viber';
        outhtml = outhtml + '<br><strong>SMS text</strong>: ' + $('#textSMS').val();
        outhtml = outhtml + '<br><strong>Viber text</strong>: <div style="margin:auto;  width: 250px; border: 1px solid #eeeeee; padding: 10px;">' + $('#textViber').val() + '</div>';
    }
    if ($('#type').val() == '0') {
        outhtml = outhtml + '<br><strong>Канал отправки</strong>: SMS';
        outhtml = outhtml + '<br><strong>SMS text</strong>: ' + $('#textSMS').val();
    }
    if ($('#type').val() == '1') {
        outhtml = outhtml + '<br><strong>Канал отправки</strong>: Viber';
        outhtml = outhtml + '<br><strong>Viber text</strong>: <div style="margin:auto;  width: 250px; border: 1px solid #eeeeee; padding: 10px;">' + $('#textViber').val() + '</div>';
    }
    $('#myModal .modal-body').html(outhtml);
    if (viberCurrect()) {
        $('#myModal .modal-footer').html("<button type=\"button\" class=\"btn btn-primary\"  onclick='sendSMS()'>Ok</button>");
    }
    else {
        $('#myModal .modal-footer').html("<div class=\"alert alert-info text-left\"'><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>        <strong>Внимание</strong> <br>          Согласно спецификации Viber совместное использование текстовой и графической информации обязательно должно сопровождаться ссылкой (кнопкой) </div>");
    }
    $('#myModal').modal({keyboard: true});
}

function sendMail() {//viber
    CKEDITOR.instances.textEmail.updateElement();


    var $form = $('#senderMail');
    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        data: $form.serialize(),
        success: function (data) {
            $('.modal-body').html(data);
            if (~data.indexOf("ok")) {
                $('.modal-footer').html("<a href=\"/?target=report\" class=\"btn btn-default\">Статистика</a> <a class=\"btn btn-primary\" href=\"/?target=deleteRecipientsList&for=email\"  >Ok</a>");
            }
            else {
                $('.modal-footer').html("<button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\">Close</button>");
            }
            $('#myModal').modal({keyboard: true});
        },
        error: function (xhr, str) {
            //alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}


function sendMail_template() {

    var $form = $('#senderMail');
    $('#textEmail').val($('#edit1').html());
    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        data: $form.serialize(),
        success: function (data) {
            $('.modal-body').html(data);
            if (~data.indexOf("ok")) {
                $('.modal-footer').html("<a href=\"/?target=report\" class=\"btn btn-default\">Статистика</a> <a class=\"btn btn-primary\" href=\"/?target=deleteEmailList\"  >Ok</a>");
            }
            else {
                $('.modal-footer').html("<button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\">Close</button>");
            }
            $('#myModal').modal({keyboard: true});
        },
        error: function (xhr, str) {
            //alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}


function insertImgViber(img) {
    imgInsert = "<img style=\"width:100%;\" src=\"" + img + "\">";
    CKEDITOR.instances['textViber'].setData(CKEDITOR.instances['textViber'].getData() + imgInsert);
    $("#dialog").dialog("close");
}

function insertLinkViber(url, title) {
    if (url.indexOf('http') != 0)
        url = 'http://' + url;

    urlInsert = "<a href=\"" + url + "\" class=\"btn btn-primary btn-sm\">" + title + "</a>";
    CKEDITOR.instances['textViber'].setData(CKEDITOR.instances['textViber'].getData() + urlInsert);
    closeWindowLink();
}

function openWindowImg() {
    $.get('/ajax/smsImgDialog.php', function (data) {
        $('#dialog').html(data);
    });
    $("#dialog").dialog("open");
}

function closeWindowImg() {
    $("#dialog").dialog("close");
}

function openWindowLink() {
    $("#dialog_link").dialog("open");
}

function closeWindowLink() {
    $("#dialog_link").dialog("close");
}

function refreshWindowImg() {
    $.get('/ajax/smsImgDialog.php', function (data) {
        $('#dialog').html(data);
    });
}

var active_user = '';

function getChat(id) {
    active_user = $("[data-id=" + id + "]").attr('data-sender-id');
    $(".list-group a").removeClass('active');
    $("[data-id=" + id + "]").addClass('active');
    $.post('/viber/handler.php', {sender_id: active_user, target: 'getChat'}, function (data) {
        $('.chat .panel-body').html(data);
        $('.panel').scrollTop($('.panel-body').height());
    });
    return false;
}

function updateChat() {
    $.post('/viber/handler.php', {sender_id: active_user, target: 'updateChat'}, function (data) {
        if (data.length > 1) {
            $('.chat .panel-body').append(data);
            $('.panel').scrollTop($('.panel-body').height());
        }
    });
    updateCorrespondents();
}
var blink_toggle = false;
var blink_run = false;
var blink_id;
var newMessages = 0;

function updateNewChats() {
    $.post('/viber/handler.php', {target: 'updateNewChats'}, function (data) {
        newMessages = data;
        // $('#newMessage.badge.top').html(data);
        if (newMessages > 0) {
            if (!blink_run) {
                blink_id = setInterval(blink, 500);
                blink_run = true;
            }
        }
        else {
            if (blink_run) {
                clearInterval(blink_id);
                blink_run = false;
                $('title').html('Softline - mobile system');
            }
        }
    });
}

function blink() {
    if (blink_toggle)
        $('title').html('*** Новое сообщение ***');
    else
        $('title').html('Softline - mobile system');
    blink_toggle = !blink_toggle;
}

function updateCorrespondents() {
    $.post('/viber/handler.php', {target: 'updateCorrespondents'}, function (data) {
        $('.list-group.chat').html(data);
        $("[data-sender-id=\"" + active_user + "\"]").addClass('active');
        updateNewChats()
    });
}

function sendMessage(message) {
    if ((active_user != '') && (!($('#btnChatSend').attr('disabled') == 'disabled'))) {
        $('#btnChatSend').attr('disabled', 'disabled');
        $('#inputChatSend').attr('disabled', 'disabled');
        $.post('/viber/handler.php',
            {receiver_id: active_user,
                target: 'sendMessage',
                text: message},
            function () {
            updateChat();
            $('#btnChatSend').removeAttr('disabled');
            $('#inputChatSend').removeAttr('disabled');
            $('input#inputChatSend').val('');
            $('input#inputChatSend').focus();
        });
    }
}

function toggleEmojiPannel() {
    $('div.panel.emoji').toggle();
    $('div.emoji-background').toggle();
    $('input').focus();
}

function sendMessageWithImg() {
    if (active_user != '') {
        $.post('/viber/handler.php', {receiver_id: active_user, target: 'sendMessageWithImg', text: $('input').val()}, function () {
            updateChat();
            $('input').val('').setFocus();
        });
    }
}

function openGetInvoice() {
    $('#invoiceSum').val('');
    $('#dialogInvoice').dialog('open');
}

function openGetInvoiceDashboard() {
    $('#invoiceSum').val('');
    $('#btnForm_bal').toggle();
    $('#btnForm_tbl').toggle();

}
function openGetInvoiceMenu() {
    $('#invoiceSumMenu').val('');
    $('#mnuForm_bal').toggle();
    $('#mnuForm_tbl').toggle();
}

function getInvoiceFromMenu() {
    $.ajaxSetup({
        async: false
    });
    $.get('/handler.php', {target: 'getInvoice', invoiceSum: $('#invoiceSumMenu').val()}, function (data) {
        $('#getInvoiceFormMenu').attr('action', '/sharing/bills/' + data);
    });
    return false;
}

function getInvoice() {
    $.ajaxSetup({
        async: false
    });
    $.get('/handler.php', {target: 'getInvoice', invoiceSum: $('#invoiceSum').val()}, function (data) {
        $('#getInvoiceForm').attr('action', '/sharing/bills/' + data);
    });
    return false;
}

updateCorrespondents();
timer_updateChat = setInterval(updateChat, 10000);

function getDetailSMSReportByDate() {

    var eventElem = $('#getDetailSMSReportByDate');
    if (!(eventElem.attr('disabled'))) {

        var htmlElem = eventElem.attr('disabled', false).html();
        eventElem.attr('disabled', true).html('<i class="fa fa-refresh fa-spin fa-fw"></i><span class="sr-only">Loading...</span>');

        $.ajax({
            type: 'post',
            dataType: 'text',
            url: '/handler.php',
            data: {
                target: 'getDetailSMSReportByDate',
                dateFrom: $('.dateFrom').val(),
                dateTo: $('.dateTo').val()
            },
            success: function (data) {
                location.href = data;
                // window.open(data);
            },
            complete: function () {
                eventElem.attr('disabled', false).html(htmlElem);
            }
        })
    }
    return false;
}

function ajaxLastDispatchSMS(){
    $.ajax({
        type:'get',
        url:'',
        data: {
            target: 'ajaxLastDispatchSMS',
            id: id
        },
        success: function (data) {
        },
        complete: function () {

        }
    })
}

function getDetailSMSReportByDispatch(id, eventElem) {

    if (!(eventElem.attr('disabled'))) {

        var htmlElem = eventElem.attr('disabled', false).html();
        eventElem.attr('disabled', true).html('<i class="fa fa-refresh fa-spin fa-fw"></i><span class="sr-only">Loading...</span>');

        $.ajax({
            type: 'GET',
            url: '/handler.php',
            data: {
                target: 'getDetailSMSReportByDispatch',
                id: id
            },
            success: function (data) {
                location.href = data;
                // window.open(data);
            },
            complete: function () {
                eventElem.attr('disabled', false).html(htmlElem);
            }
        });
    }

    return false;
}

function getDetailReportSMSFileName(id) {
    $.ajaxSetup({
        async: false
    });
    $.get('/ajax/getDetailReportSMSFileName.php', {id: id}, function (data) {// TODO it needs delete
        DetailReportSMSFileName = data;
    });
}


function ajaxDetailSMSReport(setPage, requestSource) {
    if (setPage !== undefined) {
        page = setPage;
    }
    if (requestSource === undefined) {
        requestSource = false;
    }
    if (requestSource) $('.preloader').fadeIn();
    $.ajax({
        url: '/handler.php',
        type: 'GET',
        data: {
            target: 'ajaxDetailSMSReport',
            dateFrom: $('.dateFrom').val(),
            dateTo: $('.dateTo').val(),
            page: page,
            requestSource: requestSource
        },
        success: function (data) {
            $('.preloader').fadeOut();
            $('.ajaxHere').html(data);
        }
    });
    return false;
}

function ajaxGeneralSMSReport() {
    $('.preloader').fadeIn();
    $('.ajaxHere').html('');
    $.ajax({
        url: '/handler.php',
        type: 'GET',
        data: {
            target: 'ajaxGeneralSMSReport',
            dateFrom: $('.dateFrom').val(),
            dateTo: $('.dateTo').val()
        },
        success: function (data) {
            $('.preloader').fadeOut();
            $('.ajaxHere').html(data);
        }
    });
    return false;
}

function ajaxGeneralSMSReportSmall() {
    $('.preloader').fadeIn();
    $('.ajaxHere').html('');
    $.ajax({
        url: '/handler.php',
        type: 'GET',
        data: {
            target: 'ajaxGeneralSMSReportSmall',
            dateFrom: $('.dateFrom').val(),
            dateTo: $('.dateTo').val()
        },
        success: function (data) {
            $('.ajaxGeneralSMSReportSmall').html(data);
        }
    });
    return false;
}


function ajaxGeneralEmailReportSmall() {
    $('.preloader').fadeIn();
    $('.ajaxHere').html('');
    $.ajax({
        url: '/handler.php',
        type: 'GET',
        data: {
            target: 'ajaxGeneralEmailReportSmall',
            dateFrom: $('.dateFrom').val(),
            dateTo: $('.dateTo').val()
        },
        success: function (data) {
            $('.ajaxGeneralEmailReportSmall').html(data);
        }
    });
    return false;
}


function ajaxDetailVoiceReport(setPage, requestSource) {
    if (setPage !== undefined) {
        page = setPage;
    }
    if (requestSource === undefined) {
        requestSource = false;
    }
    if (requestSource) $('.preloader').fadeIn();
    $.ajax({
        url: '/handler.php',
        type: 'GET',
        data: {
            target: 'ajaxDetailVoiceReport',
            dateFrom: $('.dateFrom').val(),
            dateTo: $('.dateTo').val(),
            outgoing: $('.outgoing').val(),
            incoming: $('.incoming').val(),
            page: page,
            requestSource: requestSource
        },
        success: function (data) {
            $('.preloader').fadeOut();
            $('.ajaxHere').html(data);
        }
    });
    return false;
}

function getDetailVoiceReportByDate() {
    location.href = '/handler.php?target=getDetailVoiceReportByDate&dateFrom=' + $('.dateFrom').val() + '&dateTo=' + $('.dateTo').val();
    return false;
}
