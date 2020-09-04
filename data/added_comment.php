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
    // ファイルがなかったらリターンする。
    if(!file_exists($filename)){
        return false;
    }

    // ファイルを開けなかったらリターンする。
    if(!fopen($filename, "a")){
        return false;
    }
    $fp = fopen($filename, "a");
    $writeOfContent = $number . "," . $name . "," . $email . ",";
    $writeOfContent .= $book . ",";
    // コメント内の" , "は　"?cma?"　に置き換える（保存形式がCSVなので）
    $writeOfContent .= str_replace(",", "?cma?", $comment);
    $writeOfContent .= ",\n";

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
        $page = "";
        // もし、変数がすべて送信されていたら
        if($_POST['number'] and $_POST['name'] and $_POST['email'] and $_POST['book'] and $_POST['comment'] and $_POST['page']){
            // add_comment.htmlから変数を受け取る
            $number = $_POST['number'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $book = $_POST['book'];
            $comment = $_POST['comment'];
            $page = $_POST['page'];
            // 改行文字は<br>に置き換える。
            $comment = str_replace("\r\n", "<br>", $comment);
            echo $comment;
        }else{
            echo "変数がどれか受信できませんでした。";
        }

        // 「本のタイトル＋学籍番号＋名前」
        $pathToSaveFolder = __DIR__."\\comment\\".$page;
        $pathToSaveFile = $pathToSaveFolder."\\".$book.".csv";
        if(!make_file($pathToSaveFile)){
            echo "ファイルの作成に失敗しました。";
        }
        if(write_to_file($pathToSaveFile, $number, $name, $email, $book, $comment)){
        }
        
        ?>
    </body>
</html>