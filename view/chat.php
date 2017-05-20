<? include 'head.php' ?>
<body>
<div onclick="toggleEmojiPannel()" class="emoji-background collapse" style="position: fixed; top:0: left:0; width: 100%; height: 100%; z-index: 998; "></div>
<div class="container">
    <?php
    include 'nav.php';
    $link = mysqli_connect('172.20.128.22:3306', 'viberchat', 'v1b3r4@t', 'viber');
    ?>
    <div class="row">
        <div class="col-lg-3 col-lg-offset-1">
            <div class="list-group scrolled">
                
            </div>
        </div>

        <div class="col-lg-7 ">
            <div class="chat">
                <div class="panel panel-default">
                    <div class="panel-body">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default emoji collapse"
                         style="position: absolute; top: -160px; left: 10px; width: 95%; z-index: 999;">
                        <div class="panel-body">
                            <?
                            $directory_handle = opendir("emoji/");
                            while (false !== ($file = readdir($directory_handle))):
                                if ($file != "." && $file != "..") {
                                    ?>
                                    <img src="/emoji/<?= $file ?>" onclick="sendMessage('<?= substr($file,0,-4); ?>');toggleEmojiPannel()">
                                    <?
                                };
                            endwhile;
                            closedir($directory_handle);

                            ?>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <!--button class="btn btn-default" style="width: 50px;" type="button" onclick="sendMessage()"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                            <button class="btn btn-default" style="width: 50px;" type="button" onclick="sendMessageWithImg()"><i class="fa fa-picture-o" aria-hidden="true"></i></button-->
                            <button class="btn btn-default" style="width: 50px;" type="button" onclick="toggleEmojiPannel()">
                                <i class="fa fa-smile-o" aria-hidden="true"></i>
                            </button>
                        </span> <input type="text" id="inputChatSend" class="form-control" style="background: #e7e7e7"
                                       onkeypress="if (!event.shiftKey && event.keyCode==13) sendMessage($('input').val())">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="btnChatSend" onclick="sendMessage($('input').val())">Send</button>
                            <!--button class="btn btn-default" type="button" onclick="sendMessageWithImg()">Img</button-->
                        </span>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->
        </div>
    </div>
</div>
<script>
    $('.container .scrolled').css('height',document.documentElement.clientHeight-100);
    $('.chat .panel').css('height',document.documentElement.clientHeight-170);
</script>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/view/footer.php';
?>