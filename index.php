<?php

header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'].'/classes/Engine.php';
include_once 'config.php';

$site = new engine($link,$link_postgre);

if (isset($_GET['target']))
    $target = $_GET['target'];
elseif (isset($_POST['target']))
    $target = $_POST['target'];
else
    $target = ($_COOKIE['user_type']==0)?'message':'chat';

switch ($target):
    case 'alphanames':
        if ($site->isauth())
            include 'view/alphanames.php';
        else
            include 'view/login.php';
        break;
    case 'alphanames_new':
        if ($site->isauth())
            include 'view/alphanames_new.php';
        else
            include 'view/login.php';
        break;
    case 'message':
        if ($site->isauth())
            include 'view/mail2.php';
        else
            include 'view/login.php';
        break;
    case 'email':
        if ($site->isauth())
            include 'view/email.php';
        else
            include 'view/login.php';
        break;
    case 'email_blank':
        if ($site->isauth())
            include 'view/email_blank.php';
        else
            include 'view/login.php';
        break;
    case 'email_template':
        if ($site->isauth())
            include 'view/email_template.php';
        else
            include 'view/login.php';
        break;
    case 'chat':
        if ($site->isauth())
            include 'view/chat.php';
        else
            include 'view/login.php';
        break;
    case 'reportemail':
        include 'view/reportEmail.php';
        break;
    case 'reportemail_detail':
        include 'view/reportEmail_detail.php';
        break;
    case 'report':
        include 'view/report.php';
        break;
    case 'report_detail':
        include 'view/report_detail.php';
        break;
    case 'reportVoice':
        include 'view/reportVoice.php';
        break;
    case 'registrationForm':
        include 'view/registration.php';
        break;
    case 'registration':
        $site->registration();
        break;
    case 'logout':
        $site->logout();
        header('location: /');
        break;
    case 'sendSMS':
        echo $site->sendSMS($_GET);
        break;
    case 'uploadLogo':
        echo '555';//$site->uploadLogo();
        break;
    case 'sendTestSMS':
        echo $site->sendTestSMS($_POST);
        break;
    case 'sendMail':
        echo $site->sendMail($_POST);
        break;
    case 'sendMailTemplate':
        echo $site->sendMailTemplate($_POST);
        break;
endswitch;
