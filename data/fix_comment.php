<?php

require "utils.php";

?>

<html>
    <head>
        <meta http-equiv="content-type" charset="utf-8">
    </head>
    <body>

    <?php
    $id = "";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }
    
    echo check_comment_token($id);
    ?>

    </body>
</html>