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
    <?php
    if (isset($_COOKIE['emailsfile'])) {
        $fileList = file("sharing/" . $_COOKIE['emailsfile']);
        $fileFields = explode(';', $fileList[0]);
    }
    ?>


    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Выбирите шаблон или создайте новый</h3>
            <div class="row">
                <!--=========================BLANK=======================-->
                <div class="col-xs-6 col-md-3">
                    <a href="/?target=email_blank" class="thumbnail">
                        <span class="templateThumbnail" style="color:#DDD; text-align:center;">
                            <i class="fa fa-plus-circle fa-6x" aria-hidden="true" style="margin-top: 90px;"></i>
                        </span>
                    </a>
                </div>
                <!--=========================/BLANK=======================-->

                <!--=========================List=======================-->

                <?php
                if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login']))
                    mkdir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login']);
                
                if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/"))
                    mkdir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/");
                
                if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/pictures/"))
                    mkdir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/pictures/");
                
                if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/templates/"))
                    mkdir($_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/templates/");

                $path = $_SERVER['DOCUMENT_ROOT'] . "/accounts/" . $_COOKIE['login'] . "/email/templates/";
                $pathURL = "http://" . $_SERVER['HTTP_HOST'] . "/accounts/" . $_COOKIE['login'] . "/email/templates";
                $directory_handle = opendir($path);
                while (false !== ($file = readdir($directory_handle))):
                    if ($file != "." && $file != "..") {
                        ?>

                        <div class="col-xs-6 col-md-3">
                            <a href="/?target=email_template" class="thumbnail">
                                <span class="templateThumbnail" style="background-image:url(<?= $pathURL . "/" . $file ?>/thumbnail.png); "></span>
                            </a>

                        </div>

                        <?php
                    };
                endwhile;
                closedir($directory_handle);
                ?>
                <!--=========================/List=======================-->


            </div>
        </div>
    </div>
</div>


<?php
include $_SERVER['DOCUMENT_ROOT'].'/view/footer.php';
?>