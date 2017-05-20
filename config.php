<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE);//E_ALL & ~E_NOTICE
ini_set('display_errors', 0);//Определяет, должны ли ошибки быть выведены на экран как часть вывода или они должны быть скрыты от пользователя.
ini_set('display_startup_errors', 0);
ini_set('date.timezone', 'Europe/Kiev');

header("Content-Type: text/html; charset=utf-8");

define('countOnPage',20);

$link = mysqli_connect('172.20.128.22:3306', 'viberchat', 'v1b3r4@t', 'viber');
mysqli_query($link,"SET NAMES 'utf8'");

$link_postgre = pg_connect("host=172.20.29.2 dbname=asterisk user=cdrview password=cdrv13w")
or die('Could not connect: ' . pg_last_error());

session_start();
