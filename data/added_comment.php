<?php

require "utils.php";

// ここでは、added_comment.phpで使用されるPHPの関数を定義する。

//　ファイルを作成する関数。
function make_file(String $filename, String $token){
    
    // 正しいtokenを持っていなかったらリターンする
    if(get_email($token) == false){
        echo "有効なtokenが受信できませんでした。<br>";
        return false;
    }

    // ファイルが既に存在していたらリターンする。
    if(file_exists($filename)){
        echo "すでにファイル".$filename."は存在しています。（新たに作成はしませんでした）<br>";
        return false;
    }

    // touch関数でファイル作成
    // touchが成功したらtrue、失敗したらfalse
    if(touch($filename)){
        // 初期IDを作成する
        fwrite(fopen($filename,"w"), "-1,\n");
    }

}

// 存在するファイルに書き込み(追記)を行う関数。
function write_to_file(String $filename, String $number, String $name, String $email, String $book, String $comment, String $token){

    // 正しいtokenを持っていなかったらリターンする
    if(get_email($token) == false){
        echo "有効なtokenが受信できませんでした。<br>";
        return false;
    }

    // ファイルがなかったらリターンする。
    if(!file_exists($filename)){
        echo "ファイル".$filename."が存在しませんでした。よって、書き込み処理を行いませんでした";
        return false;
    }

    // ファイルを開けなかったらリターンする。
    if(!fopen($filename, "a")){
        return false;
    }
    
    $fp = fopen($filename, "a");
    $id = getID_recent($filename) + 1;
    $writeOfContent = (String)$id. ",".$number . "," . $name . "," . $email . ",";
    $writeOfContent .= $book . ",";
    // コメント内の" , "は　"?cma?"　に置き換える（保存形式がCSVなので）
    //$writeOfContent .= str_replace(",", "?cma?", $comment);
    $writeOfContent .= str_replace(",","?cma?",htmlspecialchars($comment));
    $writeOfContent .= ",\n";

    // ファイルに書き込めなかったらリターンする。
    if(!fwrite($fp, $writeOfContent)){
        return false;
    }
    return true;
}

//とりあえず、このPHPファイルが呼び出されたら送信する(失敗したらfalseを返す)
// 発行したtokenをURLにくっつける
function sendmailToOwner(){

    $mail_owner = 'hitokotosuisen@gmail.com';
    
    $subject = 'ひとことすいせん　管理用メール';
    
    // ヘッダー情報
    $headers = "From: ". $mail_owner . "\r\n";
    // htmlメールに対応させる
    $headers .= "Content-type: text/html;charset=UTF-8";
    
    // メッセージ部分
    $message = "
    ";

    if(mail($mail_owner, $subject, $message, $headers)){
        return true;
    }
    
    return false;
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
        $token = "";
        $pathToSavedCSV = "";
        // もし、変数がすべて送信されていたら
        if($_POST['number'] and $_POST['name'] and $_POST['email'] and $_POST['book']){
            // add_comment.htmlから変数を受け取る
            $number = $_POST['number'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $book = $_POST['book'];
            $comment = $_POST['comment'];
            $page = $_POST['page'];
            // 改行文字は<br>に置き換える。
            $comment = str_replace("\r\n", "<br>", $comment);
            $token = $_GET['token'];
        }else{
            echo "変数がどれか受信できませんでした。";
        }
        // ただしいtokenを持っていたときのみに、ファイル処理をする。
        if(get_email($token) != false){
            // 「本のタイトル＋学籍番号＋名前」
            $pathToSaveFolder = __DIR__."\\comment\\".$page;
            $pathToSaveFile = $pathToSaveFolder."\\".$book.".csv";
            if(!make_file($pathToSaveFile, $token)){
                echo "ファイルの作成を行いませんでした。<br>";
            }
            if(write_to_file($pathToSaveFile, $number, $name, $email, $book, $comment, $token)){
                echo "ファイルに書き込みを行ました。<br>";
            }
            $pathToSavedCSV = $pathToSaveFile;
        }

        // hitokotosuisen@gmailに対して管理用メールを送信する。
        sendmailToOwner();

        getID_recent($pathToSavedCSV);
        
        ?>
    </body>
</html>