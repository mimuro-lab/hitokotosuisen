<?php

// ここでは、added_comment.phpで使用されるPHPの関数を定義する。

//　ファイルを作成する関数。
function make_file(String $filename){
    
    // ファイルが既に存在していたらリターンする。
    if(file_exists($filename)){
        return false;
    }

    // touch関数でファイル作成
    // touchが成功したらtrue、失敗したらfalse
    return touch($filename);
}

// 存在するファイルに書き込み(追記)を行う関数。
function write_to_file(String $filename, String $number, String $name, String $email, String $book, String $comment){
    // ファイルが既に存在していたらリターンする。
    if(!file_exists($filename)){
        return false;
    }

    // ファイルを開けなかったらリターンする。
    if(!fopen($filename, "a")){
        return false;
    }
    $fp = fopen($filename, "a");
    $writeOfContent = $number . "," . $name . "," . $email . ",\n";
    $writeOfContent .= $book . ",\n";
    $writeOfContent .= $comment;

    // ファイルに書き込めなかったらリターンする。
    if(!fwrite($fp, $writeOfContent)){
        return false;
    }
    return true;
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
        if(!make_file($saveFileName)){
            echo "ファイルの作成に失敗しました。";
        }
        if(write_to_file($saveFileName, $number, $name, $email, $book, $comment)){

        }

        ?>
    </body>
</html>