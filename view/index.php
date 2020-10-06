<?php

require_once(__DIR__."\\defaultPage.php");
require_once(__DIR__."\\tagPage.php");
date_default_timezone_set('Asia/Tokyo');

function printTitleLine(string $inputTag)
{
  if($inputTag == ""){
    return "<h4>最近の投稿</h4>";
  }

  if($inputTag == "___time_"){
    return "<h4>投稿時刻が早い順</h4>";
  }
  
  return "<h4>キーワード".$inputTag."を含む投稿</h4>";

}

function printPageButton(string $viewTag, int $nowPage)
{
  if($nowPage < 1){
    $nowPage = 1;
  }
  return '
  <form action="" method="get">
    <input type="hidden" name="tag" value="'.$viewTag.'">
    <button type="submit" name="page" value="'.($nowPage-1).'">前へ</button>
    <button type="submit" name="page" value="1">検索トップ</button>
    <button type="submit" name="page" value="'.($nowPage+1).'">次へ</button>
  </form>
  ';
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
  }else if(isset($_GET["stag"])){
    $viewTag = $_GET["stag"];
  }else{
    $isDefaultPage = true;
  }
  if(isset($_GET["page"])){
    $nowPage = intval($_GET["page"]);
  }else{
    $nowPage = 0;
  }

  echo '
  <table border="0" width="100%">
  <tr>
    <td colspan="4" align="center">
    <h1>ひとことすいせん</h1>
    <h2>閲覧ページ</h2>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">'.printTitleLine($viewTag).'</td>
  </tr>
  <tr>
    <td width="5%"></td>
    <td align="left" valign="top"  width="20%">'.file_get_contents(__DIR__."\\leftPage.php").'</td>
    <td align="left" width="50%">
  ';
  
  // デフォルト（タグが入力されていない）ページなら、
  // コメントを上から10個表示する。
  if($isDefaultPage){
    $recent = date('Y/m/d', strtotime('-2 week', time()));
    viewDefaultComment($recent, 5);
  }else{
    viewTagComment($viewTag, $nowPage);
  }

  echo '
  </td>
  <td align="center" valign="top" width="25%">'.file_get_contents(__DIR__."\\rightPage.php").'</td>
  </tr>
  <tr>
    <td align="center" colspan="4">
  ';
  if(!$isDefaultPage){ 
    echo printPageButton($viewTag, $nowPage);
  }
  echo '
    </td>
  </tr>
  ';

  ?>
  
  </body>
</html>