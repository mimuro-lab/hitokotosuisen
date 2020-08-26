<?php

//とりあえず、このPHPファイルが呼び出されたら送信する
function sendmail(string $name, string $message){
    $mail_from      = 'hitokotosuisen@gmail.com';
    $mail_to        = 'hitokotosuisen@gmail.com';
    $mail_from_name = '送信者の名前';
    
    $subject = '件名';
    
    $body_text = '代替テキストの本文';
    $body_html = 'HTMLメールの本文';
    
    $parameter = "-f ".$mail_from;
    
    $boundary = "--".uniqid(rand(),1);
    
    // ヘッダー情報
    $headers .= 'Content-type: text/html;';
    $headers .= "From: " . $mail_from . "\r\n";
    
    // メッセージ部分
    /*
    $message = '';
    $message .= '--' . $boundary . "\r\n";
    $message .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";
    $message .= 'Content-Disposition: inline' . "\r\n";
    $message .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n";
    $message .= "\r\n";
    $message .= quoted_printable_decode ( $body_text ) . "\r\n";
    $message .= "\r\n";
    $message .= '--' . $boundary . "\r\n";
    $message .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    $message .= 'Content-Disposition: inline' . "\r\n";
    $message .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n";
    $message .= "\r\n";
    $message .= quoted_printable_decode ( $body_html ) . "\r\n";
    $message .= '--' . $boundary . "\r\n";
    */

    $message = "<h1>".$message."</h1>";

    if(mail($mail_to,$subject, $message, $headers, $parameter)){
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