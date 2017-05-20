<?php
if (isset($_FILES['upload'])) {
    copy($_FILES['upload']['tmp_name'], '../sharing/pictures/' . $_FILES['upload']['name']);
    echo 'ok';
} else {
    ?>
    <form enctype="multipart/form-data" method="post" >
        <input name="upload" type="file" onchange="$('form').submit()">
        <input type="submit">
    </form>
    <?
}
?>