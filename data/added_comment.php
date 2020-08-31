<?php

// ここでは、added_comment.phpで使用されるPHPの関数を定義する。

//　ファイルを作成する関数。
function make_file(String $filename){
    
    // ファイルが既に存在していたらリターンする。
    if(file_exists($filename)){
        echo "すでにこのファイルは存在していました。";
        return false;
    }

    // touch関数でファイル作成
    // touchが成功したらtrue、失敗したらfalse
    return touch($filename);
}

?>

<html>
    <head>
    </head>
    <body>
        <?php

        $number = "";
        $name = "";
        $email = "";
        $book = "";
        $comment = "";
        // もし、変数がすべて送信されていたら
        if($_POST['number'] and $_POST['name'] and $_POST['email'] and $_POST['book'] and $_POST['comment']){
            // add_comment.htmlから変数を受け取る
            $number = $_POST['number'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $book = $_POST['book'];
            $comment = $_POST['comment'];
        }else{
            echo "変数がどれか受信できませんでした。";
        }

        // 「本のタイトル＋学籍番号＋名前」
        $saveFileName = $book."__".$number."__".$name;
        if(make_file($saveFileName)){
            echo "ファイルの作成に成功しました。";
        }

        ?>
    </body>
</html>