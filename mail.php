<?php

//とりあえず、このPHPファイルが呼び出されたら送信する(失敗したらfalseを返す)
// 発行したtokenをURLにくっつける
function sendmailToUser($mail_user){

    if(read_token($mail_user) == false){
        echo "メールアドレスに対応するtokenを見つけられなかったので、メールを送信できませんでした。<br>";
        return false;
    }

    // メールアドレスにマッチするtokenを取得する。
    $token = str_getcsv(read_token($mail_user))[1];
    echo "<br>".$token."<br>";

    $mail_from      = 'hitokotosuisen@gmail.com';
    
    $subject = '小山高専図書情報　ひとことすいせん係より';
    
    // ヘッダー情報
    $headers = "From: ". $mail_from . "\r\n";
    // htmlメールに対応させる
    $headers .= "Content-type: text/html;charset=UTF-8";
    
    // メッセージ部分
    $message = "
    ひとことすいせんに参加して頂き、ありがとうございます。
    このメールに、推薦内容を記載して、返信してください。

    記載内容は以下の、記載例のように記載してください。
    ※推薦者・推薦図書・推薦内容のどれかがない場合は受け付けません。

    #### 記載例 ####
    推薦者：　　本科　1学年　EE　小山太郎

    推薦図書：　電気回路Ⅰ

    推薦内容：（以下に記載）
    とても分かり易い！用語の定義から、公式の導出過程まで丁寧に書かれている！
    また、問題の内容もとても良い！初学者向けの簡単な問題から、編入対策にもなる問題もある！
    電気回路を総合的に勉強したい人向けの教科書！
    <a href = \"http://localhost:8080/data/add_comment.php?token=".$token."\">コメントしに行く</a>
    ";

    if(mail($mail_user, $subject, $message, $headers)){
        return true;
    }
    
    return false;
}

// ランダムな英数字を作成する。同じ文字が出現する可能性あり。
// 第一引数には文字列の長さを入力する。
function random($length)
{
    return base_convert(mt_rand(pow(36, $length - 1), pow(36, $length) - 1), 10, 36);
}

// tokenのテーブルを作成する。
// 一つのメールアドレスに対して、一つのtokenしか作らない
function make_token_table(String $email){

    // 以前このメールアドレスに対してのtokenを作成したか？
    if(read_token($email) != false){
        echo "同じメールアカウントに対して、複数のtokenは作りません。";
        return false;
    }
    $tokenAndEmail = $email.",".random(10)."\n";

    $pathToToken = __DIR__."/data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。";
        return;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "a")){
        return false;
    }
    $fp = fopen($pathToToken, "a");
    // ファイルに書き込めなかったらリターンする。
    if(!fwrite($fp, $tokenAndEmail)){
        return false;
    }
    return true;
}

// tokenを取得する。emailにマッチするtokenを返す関数。
function read_token(String $email){
    $pathToToken = __DIR__."/data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "a")){
        echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    
    // 一行ずつ読み込む
    $tokenLine = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $tokenLine = fgets($fp);
        if(strpos($tokenLine, $email) !== false){
            return $tokenLine;
        }
    }

    echo "メールアカウントに対するtokenを見つけられませんでした。";
    return false;
}

// 以下、本文
$mail_to = $_POST["email"];

make_token_table($mail_to);

sendmailToUser($mail_to);

echo read_token($mail_to);

?>