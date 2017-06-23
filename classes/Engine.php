<?php

class Engine
{
    public $link;
    public $link_postgre;
    public $chatKey;
    public $paymentType;

    function __construct($link,$link_postgre)
    {
        $this->link = $link;
        $this->link_postgre = $link;
        $q = mysqli_query($this->link, "select chat_key,payment_type from accounts where id='{$_COOKIE['id']}'");
        $r = mysqli_fetch_array($q);
        $this->chatKey = $r[0];
        $this->paymentType = $r[1];
    }

    function log($value, $file = 'log.txt')
    {
        $fp = fopen($file, "a"); // Открываем файл в режиме записи
        fwrite($fp, $value . chr(10)); // Запись в файл
        fclose($fp); //Закрытие файла
    }

    function isauth()
    {
        return isset($_COOKIE['id']);
    }

    function login($user, $pwd)
    {

        $data = file("http://172.20.128.3:8080/messenger/login?user=$user&pwd=$pwd");
        file_put_contents('login.txt',file_get_contents("http://172.20.128.3:8080/messenger/login?user=$user&pwd=$pwd"));
        $rows = explode('=', $data[0]);

        if (($rows[0] == 'user') and (strpos($rows[1], 'unknown') === false)) {
            setcookie('id', trim($rows[1]), time() + 60 * 60 * 8, '/');
            setcookie('login', $user, time() + 60 * 60 * 8, '/');
            $alfanamesA = explode('=', $data[1]);
            $alfanames = str_replace('[', '', $alfanamesA[1]);
            $alfanames = str_replace(']', '', $alfanames);
            $alfanames = trim($alfanames);
            setcookie('alfanames', $alfanames, time() + 60 * 60 * 8, '/');
            $user_type = explode('=', $data[2]);
            setcookie('user_type', trim($user_type[1]), time() + 60 * 60 * 8, '/');
            $api_key = explode('=', $data[3]);
            setcookie('chat_key', trim($api_key[1]), time() + 60 * 60 * 8, '/');
            $voice = explode('=', $data[5]);
            setcookie('voice', trim($voice[1]), time() + 60 * 60 * 8, '/');
        }
    }

    function getUserLogo(){
        $sql="select file_name from account_detail where account_id='{$_COOKIE['id']}'";
        $q=mysqli_query($this->link,$sql);
        $r=mysqli_fetch_array($q);
        $fileNamePath=$r[0];
        $fileNameURL=str_replace('/var/www/html','',$r[0]);
        if(file_exists($fileNamePath)){
            return $fileNameURL;
        }
        else {
            return '/img/man.png';
        }
    }

    function logout()
    {
        setcookie('id', '', time() - 60 * 60);
        setcookie('login', '', time() - 60 * 60);
        setcookie('alfanames', '', time() - 60 * 60);
        setcookie('filename', '', time() - 60 * 60);
        setcookie('chat_key', '', time() - 60 * 60);
    }

    function registration()
    {
        $email = urlencode($_POST['email']);
        $name = urlencode($_POST['name']);
        $phone = trim($_POST['phone']);
        $phone = str_replace('-', '', $phone);
        $phone = str_replace(')', '', $phone);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('_', '', $phone);
        $phone = str_replace('+', '', $phone);
        $phone = urlencode($phone);

        $url = "http://172.20.128.3:8080/messenger/reg?phone=$phone&email=$email&name=$name";
        $data = file($url);
        header('location: /');
    }

    function uploadRecipientsList($for)
    {
        $outputext = pathinfo($_FILES['file']['name']);
        $newname = date('YmdHis') . "." . $outputext['extension'];
        if ((copy($_FILES['file']['tmp_name'], "sharing/" . $newname)) && (strpos('csv xls xlsx', $outputext['extension']) !== false)) {
            switch ($for) {
                case 'sms':
                    setcookie('filename', $newname);
                    setcookie('filename_display', $_FILES['file']['name']);
                    break;
                case'email':
                    setcookie('emailsfile', $newname);
                    setcookie('emailsfile_display', $_FILES['file']['name']);
                    break;
            }

        }

    }

    function getRecipientsList($for)
    {
        $result = array();

        switch ($for) {
            case'sms':
                $fileList = isset($_COOKIE['filename']) ? "sharing/" . $_COOKIE['filename'] : false;
                break;
            case'email':
                $fileList = isset($_COOKIE['emailsfile']) ? "sharing/" . $_COOKIE['emailsfile'] : false;
                break;
            default:
                $fileList = false;
        }

        if ($fileList) {
            $filename = pathinfo($fileList);

            switch ($filename['extension']) {
                case 'csv':
                    $fileList = file($fileList);
                    $result = explode(';', mb_convert_encoding($fileList[0], 'UTF-8', 'windows-1251'));
                    break;
                case 'xls':
                case 'xlsx':

                    include $_SERVER['DOCUMENT_ROOT'] . '/PHPExcel.php';
                    $objPHPExcel = PHPExcel_IOFactory::load($fileList);
//                $objPHPExcel->save('testExportFile.csv');

                    $lists = array();
                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
                        $lists[] = $worksheet->toArray();
                    $rows = $lists[0];
                    foreach ($rows as $row)
                        file_put_contents("sharing/".$filename['filename'].'.csv', implode(';', $row).PHP_EOL,FILE_APPEND);

                    setcookie('filename', $filename['filename'].'.csv');

                    foreach ($rows[0] as $col)
                        $result[] = $col;

                    break;
                default:
                    $result[] = 'Неверный формат файла';
            }

        }
        return $result;
    }

    function xls2csv()
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/PHPExcel.php';
        $objPHPExcel = PHPExcel_IOFactory::load();
    }

    function getFullDetailReportSMSFileName()
    {
        $data = file("http://172.20.128.3:8080/messenger/billing_notification?notification={$_GET['id']}");
        $file = explode('=', $data[0]);
        $filepath = trim($file[1]);
        return $filepath;
    }

    function getDetailSMSReportByDate($dateFrom, $dateTo)
    {
        $user = $_COOKIE['id'];

        $dateFrom = str_replace('.', '', $dateFrom) . '000000';
        $dateTo = str_replace('.', '', $dateTo) . '235959';

        $url = "http://172.20.128.3:8080/messenger/billing_detail?user=$user&start_notification=$dateFrom&finish_notification=$dateTo";
        $data_detail = file($url);

        $source_detail = explode("=", $data_detail[0]);
        return "/sharing/" . trim($source_detail[1]);
    }

    function getDetailVoiceReportByDate()
    {

        $link_postgre = pg_connect("host=172.20.29.2 dbname=asterisk user=cdrview password=cdrv13w")
        or die('Could not connect: ' . pg_last_error());

        $dateFrom = DateTime::createFromFormat('d.m.Y', $_GET['dateFrom']);
        $dateTo = DateTime::createFromFormat('d.m.Y', $_GET['dateTo']);
        $query = "select * from taxi WHERE calldate BETWEEN '" . $dateFrom->format('Y.m.d') . "' and  '" . $dateTo->format('Y.m.d') . " 23:59:59'";
        $result = pg_query($link_postgre, $query) or die('Ошибка запроса: ' . pg_last_error());

        $output='Дата/Время;Исходящий;Входящий номер;Продолжительность;Тарификация;Статус';
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $output.=implode(';',$line).PHP_EOL;
        }

        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-type: application/vnd.ms-excel");
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=reportVoice-{$_GET['dateFrom']}-{$_GET['dateTo']}.csv");

        file_put_contents('php://output',$output);
//        return $output;
    }

    function getDetailSMSReportByDispatch($id)
    {

        $data = file("http://172.20.128.3:8080/messenger/billing_notification?notification=$id");
        $file = explode('=', $data[0]);
        return '/sharing/' . trim($file[1]);

    }

    function sendSMS($post)
    {
        sleep(2);
        $file = $post['file'];
        $user = $post['user'];
        $phone = urlencode($post['phone']);
        $sms_text = urlencode($post['sms_text']);
        $type = $post['type'];
        $start_date = date('dmYHis', mktime(
            substr($post['start_date'], 11, 2),
            substr($post['start_date'], 14, 2),
            0,
            substr($post['start_date'], 3, 2),
            substr($post['start_date'], 0, 2),
            substr($post['start_date'], 6, 4)));
        $alfaname = urlencode($post['alfaname']);

        file_put_contents('mess_text.txt',$post['mess_text']);
        $mess = str_replace("&nbsp;", "", $post['mess_text']);
        $mess = str_replace(chr(13), "", $mess);
        $mess = str_replace(chr(10), "", $mess);
        $mess = str_replace('<p>', '', $mess);
        $mess = str_replace('</p>', '\n', $mess);
        $mess = str_replace('&ndash;', '-', $mess);
        file_put_contents('mess_text2.txt',$mess);


        //======================= обработка изображений ======================//
        if (strpos($mess, 'src="') > 0) {
            $mess_img = $mess;
            $mess_img = substr($mess_img, strpos($mess_img, 'src="') + 5);

            $mess_img = substr($mess_img, 0, strpos($mess_img, '"'));


            $p1 = strpos($mess, '<img');
            $tmp = substr($mess, $p1);
            $p2 = $p1 + strpos($tmp, '>') + 1;
            $mess = substr($mess, 0, $p1) . " " . $mess_img . " " . substr($mess, $p2);
        }
        //=========================== обработка ссылок ========================//
        if (strpos($mess, 'href="') > 0) {
            $mess_url = $post['mess_text'];
            $mess_url = substr($mess_url, strpos($mess_url, 'href="') + 6);
            $mess_url = substr($mess_url, 0, strpos($mess_url, '"'));

            $mess_bntName = $post['mess_text'];

            $mess_bntName = substr($mess_bntName, strpos($mess_bntName, '<a') + 2);
            $mess_bntName = substr($mess_bntName, strpos($mess_bntName, '>') + 1);
            $mess_bntName = substr($mess_bntName, 0, strpos($mess_bntName, '</a>'));
            $mess_bntName = str_replace(' ', '|', $mess_bntName);

            $mess_url = (strpos($mess_url, '?') > 0) ? "$mess_url&btnName=$mess_bntName" : "$mess_url?btnName=$mess_bntName";

            $p1 = strpos($mess, '<a');
            $tmp = substr($mess, $p1);
            $p2 = $p1 + strpos($tmp, '</a>') + 4;
            $mess = substr($mess, 0, $p1) . " " . $mess_url . " " . substr($mess, $p2);

        }


        $mess = str_replace('%', '%25', $mess);
        $mess = str_replace('&quot;', '%22', $mess);
        $mess = str_replace('&amp;', '%26', $mess);
        $mess = str_replace('~', '%7E', $mess);
        $mess = str_replace('&#39;', '%27', $mess);
        $mess = str_replace('&lt;', '%3C', $mess);
        $mess = str_replace('&gt;', '%3E', $mess);
        $mess = str_replace('&laquo;', '%22', $mess);
        $mess = str_replace('&raquo;', '%22', $mess);
        $mess = str_replace('&euro;', '%E2%82%AC', $mess);
        $mess = str_replace('&rsquo;', '%27', $mess);


        $mess_text = urlencode(trim(strip_tags($mess)));
        file_put_contents('mess_text3.txt',$mess_text);

        $url = "http://172.20.128.3:8080/messenger/send?file=$file&user=$user&phone=$phone&sms_text=$sms_text&mess_text=$mess_text&type=$type&start_time=$start_date&alfaname=$alfaname";
        $data = file($url);

        if (strpos($data[0], 'e ok') > 0)
            $this->deleteRecipientsList('sms');
        return $data[0];
    }

    function deleteRecipientsList($for)
    {
        switch ($for) {
            case'sms':
                setcookie('filename', '', time() - 60 * 60);
                setcookie('filename_display', '', time() - 60 * 60);
                break;
            case'email':
                setcookie('emailsfile', '', time() - 60 * 60);
                setcookie('emailsfile_display', '', time() - 60 * 60);
                break;
        }
    }

    function getInvoice()
    {
        $data = file("http://172.20.128.3:8080/messenger/BillRequest?user_id=" . $_COOKIE['id'] . "&amount=" . $_GET['invoiceSum']);
        $dataArray = explode('=', $data[0]);
        return ($dataArray[1]);
    }

    function sendTestSMS($post)
    {
        sleep(1);
        $file = $post['file'];
        $user = $post['user'];
        $phone = urlencode($post['phone']);
        $sms_text = urlencode($post['sms_text']);
        $type = $post['type'];
        $start_date = date('dmYHis', mktime(
            substr($post['start_date'], 11, 2),
            substr($post['start_date'], 14, 2),
            0,
            substr($post['start_date'], 3, 2),
            substr($post['start_date'], 0, 2),
            substr($post['start_date'], 6, 4)));
        $alfaname = urlencode($post['alfaname']);

        $mess = str_replace("&nbsp;", "", $post['mess_text']);
        $mess = str_replace(chr(13), "", $mess);
        $mess = str_replace(chr(10), "", $mess);
        $mess = str_replace('</p>', '\n', $mess);
        $mess = str_replace('&ndash;', '-', $mess);


        //============= обработка изображений ======================//
        if (strpos($mess, 'src="') > 0) {
            $mess_img = $mess;
            $mess_img = substr($mess_img, strpos($mess_img, 'src="') + 5);

            $mess_img = substr($mess_img, 0, strpos($mess_img, '"'));


            $p1 = strpos($mess, '<img');
            $tmp = substr($mess, $p1);
            $p2 = $p1 + strpos($tmp, '>') + 1;
            $mess = substr($mess, 0, $p1) . " " . $mess_img . " " . substr($mess, $p2);
        }
        //======================== обработка ссылок =====================//
        if (strpos($mess, 'href="') > 0) {
            $mess_url = $post['mess_text'];
            $mess_url = substr($mess_url, strpos($mess_url, 'href="') + 6);
            $mess_url = substr($mess_url, 0, strpos($mess_url, '"'));

            $mess_bntName = $post['mess_text'];

            $mess_bntName = substr($mess_bntName, strpos($mess_bntName, '<a') + 2);
            $mess_bntName = substr($mess_bntName, strpos($mess_bntName, '>') + 1);
            $mess_bntName = substr($mess_bntName, 0, strpos($mess_bntName, '</a>'));
            $mess_bntName = str_replace(' ', '|', $mess_bntName);

            $mess_url = (strpos($mess_url, '?') > 0) ? "$mess_url&btnName=$mess_bntName" : "$mess_url?btnName=$mess_bntName";

            $p1 = strpos($mess, '<a');
            $tmp = substr($mess, $p1);
            $p2 = $p1 + strpos($tmp, '</a>') + 4;
            $mess = substr($mess, 0, $p1) . " " . $mess_url . " " . substr($mess, $p2);

        }

        $mess = str_replace('%', '%25', $mess);
        $mess = str_replace('&quot;', '%22', $mess);
        $mess = str_replace('&amp;', '%26', $mess);
        $mess = str_replace('~', '%7E', $mess);
        $mess = str_replace('&#39;', '%27', $mess);
        $mess = str_replace('&lt;', '%3C', $mess);
        $mess = str_replace('&gt;', '%3E', $mess);
        $mess = str_replace('&laquo;', '%22', $mess);
        $mess = str_replace('&raquo;', '%22', $mess);
        $mess = str_replace('&euro;', '%E2%82%AC', $mess);
        $mess = str_replace('&rsquo;', '%27', $mess);


        $mess_text = urlencode(trim(strip_tags($mess)));

        $url = "http://172.20.128.3:8080/messenger/send_test?file=$file&user=$user&phone=$phone&sms_text=$sms_text&mess_text=$mess_text&type=$type&alfaname=$alfaname";

        $data = file($url);

        return $data[0];
    }

    function sendMail($post)
    {
        $file = $post['file'];
        $user = $post['user'];
        $subj = rawurlencode($post['subj']);
        $from = $post['from'];
        $email = $post['email'];//recipient
        $textEmail = rawurlencode($post['textEmail']);//textEmail
        $start_date = date('dmYHis', mktime(
            substr($post['start_date'], 11, 2),
            substr($post['start_date'], 14, 2),
            0,
            substr($post['start_date'], 3, 2),
            substr($post['start_date'], 0, 2),
            substr($post['start_date'], 6, 4)));
//        $url = "http://172.20.128.3:8080/smtp/send?file=$file&user=$user&email=$email&subj=$subj&from=$from&message=$textEmail&start_time=$start_date";
//        $data = file($url);
        $url = "http://172.20.128.3:8080/smtp/send";


        $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_HEADER, 0);//выводить заголовки
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
        curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");
        curl_setopt($ch1, CURLOPT_POSTFIELDS, "file=$file&user=$user&email=$email&subj=$subj&from=$from&message=$textEmail&start_time=$start_date");
        $data = curl_exec($ch1);
        curl_close($ch1);
        file_put_contents("url.txt", "file=$file&user=$user&email=$email&subj=$subj&from=$from&message=" . ($textEmail) . "&start_time=$start_date"); // Открываем файл в режиме записи

        if (strpos($data[0], 'e ok') > 0)
            $this->deleteEmailList();

        return $data;
    }


    function sendMailTemplate($post)
    {
        $head = "<html lang='ru'><head><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" /></head><body>
                 <div style=\"background:  url('http://va.ks.ua/nav.jpg'); padding-top: 26px;\">";
        $footer = "</div></body></html>";
        $file = $post['file'];
        $user = $post['user'];
        $subj = rawurlencode($post['subj']);
        $from = $post['from'];
        $email = $post['email'];//recipient
        $textEmail = rawurlencode($head . $post['textEmail'] . $footer);//textEmail
        $start_date = date('dmYHis', mktime(
            substr($post['start_date'], 11, 2),
            substr($post['start_date'], 14, 2),
            0,
            substr($post['start_date'], 3, 2),
            substr($post['start_date'], 0, 2),
            substr($post['start_date'], 6, 4)));
//        $url = "http://172.20.128.3:8080/smtp/send?file=$file&user=$user&email=$email&subj=$subj&from=$from&message=$textEmail&start_time=$start_date";
//        $data = file($url);
        $url = "http://172.20.128.3:8080/smtp/send";


        $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_HEADER, 0);//выводить заголовки
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);// 1 - вывод в переменную
        curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36");
        curl_setopt($ch1, CURLOPT_POSTFIELDS, "file=$file&user=$user&email=$email&subj=$subj&from=$from&message=$textEmail&start_time=$start_date");
        $data = curl_exec($ch1);
        curl_close($ch1);
        file_put_contents("url.txt", "file=$file&user=$user&email=$email&subj=$subj&from=$from&message=" . ($textEmail) . "&start_time=$start_date"); // Открываем файл в режиме записи

        if (strpos($data[0], 'e ok') > 0)
            $this->deleteEmailList();

        return $data;
    }

    function getBalance($prefix='',$suffix='')
    {
        $sql = "select balance, payment_type from accounts where id={$_COOKIE['id']}";
        $q = mysqli_query($this->link, $sql);
        $r = mysqli_fetch_array($q);
        if ($r['payment_type'] != 0)
            return $prefix . number_format($r[0] / 1000, 2, ',', ' ') . $suffix;
        else
            return "";

    }

    function addAlphaName()
    {
        $post = $_POST;

        if (isset($_FILES['logoImg']['tmp_name'])) {
            $pathInfo = pathinfo($_FILES['logoImg']['name']);
            $uploadFullFile = $_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/viber/logo/" . $post['alfanames'] . '.' . $pathInfo['extension'];
            if (copy($_FILES['logoImg']['tmp_name'], $uploadFullFile)) {
                $this->resizeLogo($uploadFullFile);
            };
        }

        $fields = '';
        foreach ($post as $key => $value) {
            if ($key == 'target') continue;
            if ($key == 'alfanames') continue;
            if ($key == 'codec_id') continue;
            $fields .= "`$key`='" . addslashes($value) . "', ";
        }

        $fields = substr($fields, 0, strlen($fields) - 2);
        $sql = "insert into account_detail set $fields, `file_name`='$uploadFullFile'";
        mysqli_query($this->link, $sql);

        if ($_POST['codec_id'] == 2) {
            $sql = "insert into codec_accounts set `account_id`='{$_POST['account_id']}', `codec_id`='0', `alfanames`='{$_POST['alfanames']}' ";
            mysqli_query($this->link, $sql);
            $sql = "insert into codec_accounts set `account_id`='{$_POST['account_id']}', `codec_id`='1', `alfanames`='{$_POST['alfanames']}' ";
            mysqli_query($this->link, $sql);

        } else {
            $sql = "insert into codec_accounts set `account_id`='{$_POST['account_id']}', `codec_id`='{$_POST['codec_id']}', `alfanames`='{$_POST['alfanames']}' ";
            mysqli_query($this->link, $sql);
        }

    }

    function resizeLogo($imageIn)
    {
        $pathInfo = pathinfo($imageIn);

        require_once 'ImageLib/AcImage.php';
        AcImage::setRewrite(true);
        $image = AcImage::createImage($imageIn);
        $image->setQuality(100);
        $image->resizeByWidth(130);
        $image->save($pathInfo['dirname'] . "/" . $pathInfo['filename'] . '-130.' . $pathInfo['extension']);
        $image->resizeByWidth(100)->save($pathInfo['dirname'] . "/" . $pathInfo['filename'] . '-100.' . $pathInfo['extension']);
        $image->resizeByWidth(65)->save($pathInfo['dirname'] . "/" . $pathInfo['filename'] . '-65.' . $pathInfo['extension']);
        $image->resizeByWidth(50)->save($pathInfo['dirname'] . "/" . $pathInfo['filename'] . '-50.' . $pathInfo['extension']);

    }
}