<?php

include $_SERVER['DOCUMENT_ROOT'].'/classes/Engine.php';

include 'config.php';
//include_once 'config.php';
$site = new engine($link,$link_postgre);

if (isset($_GET['target']))
    $target = $_GET['target'];
elseif (isset($_POST['target']))
    $target = $_POST['target'];
else
    $target = '';

switch ($target):
    case 'login':
        $site->login($_GET['user'], $_GET['pwd']);
        header('location: ' . $_SERVER['HTTP_REFERER']);
        break;
    case 'getInvoice':
        echo $site->getInvoice();
        break;
    case 'addAlphaName':
        $site->addAlphaName();
        header('location: /?target=alphanames');
        break;
    case 'uploadRecipientsList':
        $site->uploadRecipientsList($_GET['for']);
        header('location: ' . $_SERVER['HTTP_REFERER']);
        break;
    case 'deleteRecipientsList':
        $site->deleteRecipientsList($_GET['for']);
        header('location: ' . $_SERVER['HTTP_REFERER']);
        break;
    case 'getDetailSMSReportByDate':
        echo $site->getDetailSMSReportByDate($_POST['dateFrom'],$_POST['dateTo']);
        break;
    case 'getDetailSMSReportByDispatch':
        echo $site->getDetailSMSReportByDispatch($_GET['id']);
        break;
    case 'getDetailVoiceReportByDate':
        $site->getDetailVoiceReportByDate();
        break;
    case 'ajaxDetailSMSReport':
        include $_SERVER['DOCUMENT_ROOT'].'/view/ajaxDetailSMSReport.php';
        break;
    case 'ajaxGeneralSMSReport':
        include $_SERVER['DOCUMENT_ROOT'].'/view/ajaxGeneralSMSReport.php';
        break;
    case 'ajaxGeneralSMSReportSmall':
        include $_SERVER['DOCUMENT_ROOT'].'/view/ajaxGeneralSMSReportSmall.php';
        break;
    case 'ajaxGeneralEmailReportSmall':
        include $_SERVER['DOCUMENT_ROOT'].'/view/ajaxGeneralSMSReportSmall.php';
        break;
    case 'ajaxDetailVoiceReport':
        include $_SERVER['DOCUMENT_ROOT'].'/view/ajaxDetailVoiceReport.php';
        break;
    case 'ajaxLastDispatchSMS':
        include $_SERVER['DOCUMENT_ROOT'].'/view/ajaxLastDispatchSMS.php';
        break;

endswitch;

