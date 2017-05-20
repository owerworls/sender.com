<html>
<head>
    <link rel="stylesheet" href="/css/style.css">

</head>
<body style="background-color: white">

<?php
// Required: anonymous function reference number as explained above.
$funcNum = $_GET['CKEditorFuncNum'];
// Optional: instance name (might be used to load a specific configuration file or anything else).
$CKEditor = $_GET['CKEditor'];
// Optional: might be used to provide localized messages.
$langCode = $_GET['langCode'];

// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
$url = 'ckeditor/8.png';
// Usually you will only assign something here if the file could not be uploaded.
$message = '';

//echo "<script type='text/javascript'>window.opener.CKEDITOR.tools.callFunction($funcNum, '$url', '$message'); self.close();</script>";
?>

<?php
$path = $_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/pictures/";
$pathURL = "http://" . $_SERVER['HTTP_HOST'] . "/accounts/" . $_COOKIE['login'] . "/email/pictures/";
$directory_handle = opendir($path);
while (false !== ($file = readdir($directory_handle))):
    if ($file != "." && $file != "..") {
        ?>
        <div class="imgDialogItem enabled" style="height: 164px" onclick="window.opener.CKEDITOR.tools.callFunction(<?php echo $funcNum; ?>, '<?php echo $pathURL . rawurlencode($file); ?>', '<?php echo $message; ?>'); self.close();">
            <img  src="<?php echo $pathURL . rawurlencode($file); ?>" style="max-width: 100%; max-height: 75%;">
<br>
            <?php echo $file; ?>
        </div>
        <?php
    };
endwhile;
closedir($directory_handle);
?>


</body>
</html>
