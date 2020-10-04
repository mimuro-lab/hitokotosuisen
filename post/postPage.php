<?php

require_once(".//utils.php");

date_default_timezone_set('Asia/Tokyo');

// ここでは、added_comment.phpで使用されるPHPの関数を定義する。

function getSaveTag(String $originalTag){
    $originalTag = str_replace(":", "?cln?", $originalTag);
    $originalTag = str_replace(",", ":", $originalTag);
    return $originalTag;
}

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


// 最も大きいID番号を返す関数。
function getID_recent($filename){
    // ファイルがなかったらリターンする。
    if(!file_exists($filename)){
        echo "ファイル".$filename."が存在しませんでした。よって、処理を行いませんでした";
        return false;
    }

    $fp = fopen($filename, "r");
    // ファイルの中身を格納する変数
    $maxID = -1;

    $contentOfText = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $contentOfText = fgets($fp);
        if($contentOfText == ""){
            break;
        }
        $nowID = (int)explode(",", $contentOfText)[0];
        if($maxID < $nowID){
            $maxID = $nowID;
        }
    }
    
    return $maxID;
}


// tokenを削除する。与えられたtokenの行を削除する。
function delete_token(String $token){
    $pathToToken = __DIR__."/../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。(delete_token)".$pathToToken;
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "r")){
        echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    $pathToTmp = __DIR__."/../data/token_tmp.csv";
    $fp_tmp = fopen($pathToTmp, "w");
    
    // 一行ずつ読み込み、tmpファイルに書き込む
    $tokenLine = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $tokenLine = fgets($fp);
        // 最後の行になったらbreak
        if($tokenLine == ""){
            break;
        }   
        if(str_getcsv($tokenLine)[1] != $token){
            fwrite($fp_tmp, $tokenLine);
        }
    }

    // tmpファイルの内容をtoken.csvに上書きする。
    if(copy($pathToTmp, $pathToToken)){
    }

}

// 存在するファイルに書き込み(追記)を行う関数。
// 成功したらID＋token_commentを返す。
function write_to_file(String $filename, String $number, String $name, String $email, String $tag, String $comment, String $token){

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
    
    // コメント単位のtokenを発行する。
    $token_comment = random(10);

    $fp = fopen($filename, "a");
    $id = getID_recent($filename) + 1;
    $date = date("Y/m/d H:i:s");
    $writeOfContent = (String)$id. "," . (String)$token_comment. ",".$date .",". $number . "," . $name . "," . $email . ",";
    $writeOfContent .= $tag . ",";
    // コメント内の" , "は　"?cma?"　に置き換える（保存形式がCSVなので）
    $comment = str_replace(",", "?cma?", $comment);
    // コメント内の" <br> "は　"?newl?"　に置き換える（htmlspecialcharsを回避）
    $comment = str_replace("<br>", "?newl?", $comment);
    $comment = htmlspecialchars($comment);
    echo $comment;
    $writeOfContent .= $comment;
    $writeOfContent .= ",\n";

    // ファイルに書き込めなかったらリターンする。
    if(!fwrite($fp, $writeOfContent)){
        return false;
    }
    return array($id, $token_comment);
}


// 管理用IDから、コメントの内容を取得する関数。
// 対応する行をそのまま返す
function get_content($ID){

    // 3つの要素（page,book,lineID）で構成されていなかったらfalseを返す
    if(count(explode(":",$ID)) != 3){
        return false;
    }

    $page = explode(":",$ID)[0];
    $book = explode(":",$ID)[1];
    $lineID = (int)explode(":",$ID)[2];

    $pathToCSV = __DIR__."\\comment\\".$page."\\".$book.".csv";
    if(!file_exists($pathToCSV)){
        return false;
    }

    $fp = fopen($pathToCSV, "r");

    // ファイルの中身を格納する変数
    $contentOfText = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $contentOfText = fgets($fp);
        if($contentOfText == ""){
            break;
        }
        $nowID = (int)explode(",", $contentOfText)[0];
        if($lineID == $nowID){
            return $contentOfText;
        }
    }

    return false;
}

//とりあえず、このPHPファイルが呼び出されたら送信する(失敗したらfalseを返す)
// 発行したtokenをURLにくっつける
function sendmailToOwner($idOfComment){

    $mail_owner = 'hitokotosuisen@gmail.com';
    
    $subject = 'ひとことすいせん　管理用メール';
    
    // ヘッダー情報
    $headers = "From: ". $mail_owner . "\r\n";
    // htmlメールに対応させる
    $headers .= "Content-type: text/html;charset=UTF-8";
    
    // メッセージ部分
    $contentOfComment = get_content($idOfComment);
    $message = "コメントの管理IDは、\"".$idOfComment."\"です。<br>内容は以下、<br>".$contentOfComment;

    if(mail($mail_owner, $subject, $message, $headers)){
        return true;
    }
    
    return false;
}

function delete_cookie()
{
    setcookie("email", "", time() - 1800);
    setcookie("number", "", time() - 1800);
    setcookie("name", "", time() - 1800);
    setcookie("book", "", time() - 1800);
    setcookie("tag", "", time() - 1800);
    setcookie("comment", "", time() - 1800);
    setcookie("token", "", time() - 1800);
}

function main_postPage($post)
{
    print_r($post);
    $sccess = false;

    $number = "";
    $name = "";
    $email = "";
    $tag = "";
    $comment = "";
    $token = "";
    $pathToSavedCSV = "";
    $id_writed = "";
    $token_writed = "";
    $today = "";
    // もし、変数がすべて送信されていたら
    if(isset($post['number']) && 
       isset($post['name']) &&
       isset($post['email']) && 
       isset($post['book']) && 
       isset($post['tag']) &&
       isset($post['token'])){
        $number = $post['number'];
        $name = $post['name'];
        $email = $post['email'];
        $tag = $post['book'];
        $tag = $tag . ":" . getSaveTag($post['tag']);
        $comment = $post['comment'];
        // 改行文字は<br>に置き換える。
        $comment = str_replace("\r\n", "<br>", $comment);
        $token = $post['token'];
    }else{
        echo "変数がどれか受信できませんでした。";
        $sccess = false;
    }
    // ただしいtokenを持っていたときのみに、ファイル処理をする。
    if(get_email($token) != false){
        // 保尊先のファイル名は、作成時の日付。
        $today = date("Y-m-d");
        $pathToSaveFolder = __DIR__."\\..\\data\\comment\\";
        $pathToSaveFile = $pathToSaveFolder."\\".$today.".csv";
        if(!make_file($pathToSaveFile, $token)){
            echo "ファイルの作成を行いませんでした。<br>";
            $sccess = false;
        }
        $writed_result = write_to_file($pathToSaveFile, $number, $name, $email, $tag, $comment, $token);
        $id_writed = $writed_result[0];
        $token_writed = $writed_result[1];
        if($id_writed != false){
            echo "ファイルに書き込みを行ました。(id:".$id_writed.", token:".$token_writed.")<br>";
            $sccess = true;
        }
        $pathToSavedCSV = $pathToSaveFile;
    }

    // hitokotosuisen@gmailに対して管理用メールを送信する。
    // コメント特定用のIDは、「コメント作成時日時」＋「id(index)」＋「token」とする。
    $idOfComment = $today . ":" . $id_writed . ":" . $token_writed;
    sendmailToOwner($idOfComment);
    delete_token($token);
    delete_cookie();

    if($sccess){
        echo '
        <table border="1" width="100%">
        <tr><td align="center">
        投稿は完了しました。<br>
        <a href="http://localhost:8080">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
    }else if(!$sccess){
        echo '
        <table border="1" width="100%">
        <tr><td align="center">
        投稿できませんでした。最初からやり直してください。<br>
        <a href="http://localhost:8080">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
    }

}

?>