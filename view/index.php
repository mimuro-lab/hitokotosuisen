<?php

require_once(__DIR__."\\defaultPage.php");
require_once(__DIR__."\\tagPage.php");

function printTitleLine(string $inputTag)
{
  if($inputTag == ""){
    return "<h4>最近の投稿</h4>";
  }
  
  return "<h4>キーワード".$inputTag."を含む投稿</h4>";

}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>閲覧ページ</title>
    <meta charset="utf-8">
  </head>
  <body>
  <?php
  // メイン処理
  // 閲覧ページの状態を表す変数
  $viewTag = "";
  $isDefaultPage = false;
  if(isset($_GET["tag"]) && $_GET["tag"] != ""){
    $viewTag = $_GET["tag"];
  }else{
    $isDefaultPage = true;
  }

  echo '
  <table border="0" width="100%">
  <tr>
    <td colspan="3" align="center">
    <h1>ひとことすいせん　閲覧ページ</h1>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">'.printTitleLine($viewTag).'</td>
  </tr>
  <tr>
    <td align="center" width="25%">'.file_get_contents(__DIR__."\\leftPage.php").'</td>
    <td align="left" width="50%">
  ';
  
  // デフォルト（タグが入力されていない）ページなら、
  // コメントを上から10個表示する。
  if($isDefaultPage){
    viewDefaultComment();
  }else{
    viewTagComment($viewTag);
  }

  echo '
  </td>
  <td align="center" valign="top" width="25%">'.file_get_contents(__DIR__."\\rightPage.php").'</td>
  <tr>
  ';

  ?>
  </body>
</html>