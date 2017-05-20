<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/favicon.png" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <script src="/js/jquery-1.12.4.min.js"></script>
    <script src="/js/scripts.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>

        $(function () {
            $("#dialog").dialog({
                autoOpen: false,
                width: 800,
                height: 600
            });
        });

        $(function () {
            $("#dialog_link").dialog({
                autoOpen: false,
                width: 300,
                height: 250
            });
        });

        $(function () {
            $("#dialogInvoice").dialog({
                autoOpen: false,
                width: 300,
                height: 250
            });
        });
        var page = 1;
    </script>
    <title>Softline - mobile system</title>
    <?php
    if($target=='message'){
        ?>
        <style>
            #cke_1_top{
                display: none;
            }
            #group2{
                display: none;
            }
        </style>
    <?php
    }
    ?>
</head>
