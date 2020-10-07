<?php

date_default_timezone_set('Asia/Tokyo');


// tokenを削除する。与えられたtokenの行を削除する。
function delete_token(String $token){
    $pathToToken = __DIR__."/../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "r")){
        echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    $pathToTmp = __DIR__."/token_tmp.csv";
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

function sendToken($token, $email)
{
    
    $subject = '小山高専図書情報　ひとことすいせん係より';
    
    // ヘッダー情報
    $headers = "From: ". $email . "\r\n";
    // htmlメールに対応させる
    $headers .= "Content-type: text/html;charset=UTF-8";
    
    // メッセージ部分
    $message = '
    コメントIDをお送りします。

    <h3>'.$token.'</h3>';

    if(mail($email, $subject, $message, $headers)){
        return true;
    }
}

// 次のコメントのフォルダ番号を取得する
function getNextFolder()
{
    $rootDirOfPosted = __DIR__."\\..\\data\\posted";
    $max = 0;
    foreach(scandir($rootDirOfPosted) as $file){
        if(!is_dir($rootDirOfPosted."\\".$file)){
            continue;
        }
        $num = preg_replace("/[^0-9]/", "", $file);
        if($num == ""){
            continue;
        }
        $num = (int)$num;
        if($max < $num){
            $max = $num;
        }
    }
    $nextPath = $rootDirOfPosted."\\".(string)($max + 1);
    return $nextPath;
}

function make_info($post, $pathToFolder)
{
    $w = date("w");
    $week_name = array("日", "月", "火", "水", "木", "金", "土");
    $dateOfMake = date("Y/m/d") . "($week_name[$w]) ".date("H:i");
    $dateOfTag =  date("Y/m/d") . "($week_name[$w]) ";

    // 検索に使われるファイル（タグと、ほんのタイトル）
    $tag = $post["tag"];
    $book = $post["book"];
    $tag_content = $book.",".$tag.",".$dateOfMake;
    $tag_filePath = $pathToFolder."\\search_kwd.txt";
    file_put_contents($tag_filePath, $tag_content);

    // コメントの表示に使われるファイル
    $comment = $post["comment"];
    $comment = str_replace("\r\n", "?newl?", $comment);   //改行をhtml形式に合わせる
    $comment = str_replace(",", "?cma?", $comment);     //,のエスケープ処理（保存形式がCSVになるから）
    $comment = htmlspecialchars($comment);              //htmlのエスケープ処理
    $comment = str_replace("?newl?", "<br>", $comment);
    $view_content = $book.",".$dateOfMake.",".$comment;
    $view_filePath = $pathToFolder."\\view.txt";
    file_put_contents($view_filePath, $view_content);

    // 投稿主の情報を格納するファイル
    $length = 10;
    $token_comment = base_convert(mt_rand(pow(36, $length - 1), pow(36, $length) - 1), 10, 36);
    $name = $post["name"];
    $number = $post["number"];
    $email = $post["email"];
    $info_content = $token_comment.','.$name.','.$number.','.$email.','.$dateOfMake;
    $info_filePath = $pathToFolder."\\info.txt";
    file_put_contents($info_filePath, $info_content);

    return $token_comment;
}

function main_postPage($post)
{
    
    session_start();session_destroy();

    $success = false;
    $token_comment = "";

    //print_r($_SESSION);
    if(!isset($_SESSION["isFirst"]))
    {
        $_SESSION["isFirst"] = "yes";
    }
    if(isset($_SESSION["isFirst"]) && $_SESSION["isFirst"] == "yes"){
        if(isset($post["number"]) && isset($post["name"]) && isset($post["email"]) && 
        isset($post["book"]) && isset($post["tag"]) && isset($post["comment"])){
            $success = true;
            // 一回目の処理
            $pathToFolder = getNextFolder();
            mkdir($pathToFolder);
            $token_comment = basename($pathToFolder).":".make_info($post, $pathToFolder);
            $_SESSION["isFirst"] = "no";
        }

    }

    if($success){
        delete_token($post["token"]);
        echo '
        コメントIDを発行しました。<br><br>
        <h3>'.$token_comment.'</h3><br><br>
        ※コメントを編集・削除するのに必要なIDです。メモしておいてください。<br><br>

        '.$post["email"].' 宛てにこのコメントIDを送信しますか？<br><br>
        <form action="" method="post">
        <input type="hidden" name="scene" value="post_comment">
        <input type="hidden" name="sendToken" value="true">
        <input type="hidden" name="token_comment" value="'.$token_comment.'">
        <input type="hidden" name="email" value="'.$post["email"].'">
        <input type="submit" value="送信する">
        </form>
        ';
        // 「トップページへ戻る」を表示する。
        echo '
        <table border="1" width="100%">
        <tr><td align="center">
        投稿は完了しました。<br>
        <a href="http://localhost:8080">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
        return;
    }

    if($post["sendToken"] == "true"){
        sendToken($post["token_comment"], $post["email"]);
        echo '
        コメントIDをてに送信しました。。<br><br>
        送信したコメントIDは以下の通りです。<br><br>
        <h3>'.$post["token_comment"].'</h3><br><br>
        <form action="" method="post">
        <input type="hidden" name="scene" value="post_comment">
        <input type="hidden" name="sendToken" value="true">
        <input type="hidden" name="token_comment" value='.$post["token_comment"].'>
        <input type="hidden" name="email" value="'.$post["email"].'">
        <input type="submit" value="再送信する">
        </form>
        <table border="1" width="100%">
        <tr><td align="center">
        投稿は完了しました。<br>
        <a href="http://localhost:8080">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
        return ;
    }

    echo '
    <table border="1" width="100%">
    <tr><td align="center">
    投稿できませんでした。最初からやり直してください。<br>
    <a href="http://localhost:8080">トップページへ戻る</a>
    </td></tr>
    </table>
    ';
    return;

    /*
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
    */

}

?>