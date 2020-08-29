<?php

//とりあえず、このPHPファイルが呼び出されたら送信する
function sendmailToUser($mail_user){
    $mail_from      = 'hitokotosuisen@gmail.com';
    
    $subject = '小山高専図書情報　ひとことすいせん係より';
    
    // ヘッダー情報
    $headers = "From: ". $mail_from . "\r\n";
    
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

    ";

    if(mail($mail_user, $subject, $message, $headers)){
        return true;
    }
    
    return false;
}


// 以下、本文
$bodyOfMail = $_POST["body"];
$name = $_POST["name"];
$mail_to = $_POST["email"];

sendmailToUser($mail_to);

?>