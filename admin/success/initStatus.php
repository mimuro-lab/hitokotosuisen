﻿<?php

function printNowStatus()
{
    echo '現在の初期状態は';
    $nowStatus = file_get_contents('./../../data/initStatus.txt');
    $nowStatus = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $nowStatus);
    $nowStatus = preg_replace('/[^a-zA-Z]/', '', $nowStatus);
    $echoStatus = "";
    $color = "";
    if($nowStatus === "wait"){
        $color = "#C0C0C0";
        $echoStatus = "認証待ち";
    }else if($nowStatus === "public"){
        $color = "#78FF94";
        $echoStatus = "公開状態";
    }else if($nowStatus == "private"){
        $color = "#FF367F";
        $echoStatus = "非公開状態";
    }
    echo '<br><br><font color="'.$color.'">'.$echoStatus."</font><br><br>";
}

function printNextStatus()
{
    echo '
    <form action=".?scene=initStatus" method="post">
    <select name="status">
    <option value="wait">承認待ち</option>
    <option value="public">公開状態</option>
    <option value="private">非公開状態</option>
    </select><br><br>
    <button type="submit">更新する</button>
    </form>
    ';
}

function saveNextStatus(string $next)
{
    file_put_contents("./../../data/initStatus.txt", $next);
}

function main_initStatus()
{
    if(isset($_POST["status"])){
        saveNextStatus($_POST["status"]);
    }
    echo '
    <table width="100%">
    <tr><td align="center">ここでは、投稿時の最初の状態を管理します。</td></tr>
    ';
    echo '<tr><td align="center">';
    printNowStatus();
    echo '</td></tr>
    <tr><td align="center">';
    printNextStatus();
    echo '</td></tr>';
    echo '</table>';
}

?>