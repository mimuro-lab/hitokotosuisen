<?php

//とりあえず、このPHPファイルが呼び出されたら送信する
function sendmail(string $name, string $message){
    $to = "hitokotosuisen@gmail.com";
    $subject = "未定のタイトル";
    $message = $name . "\n" . $message;
    $header = "From: hitokotosuisen@gmail.cmo" . "\r\n";
    if(mail($to, $subject, $message, $header)){
        return true;
    }
    
    return false;
}

// 以下、本文
$bodyOfMail = $_POST["body"];
$name = $_POST["name"];

if(sendmail($name, $bodyOfMail)){
    echo "メール送信に成功しました!!!";
}else{
    echo "残念ながらメール送信はできませんでした。。。";
}

?>