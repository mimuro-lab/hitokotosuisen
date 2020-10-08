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

function printPageButton(string $viewTag, int $nowPage, int $maxPage)
{
  
  if($nowPage < 1){
    $nowPage = 1;
  }
  $nextPage = $nowPage + 1;
  if($maxPage < $nextPage){
    $nextPage = $maxPage;
  }
  $backPage = $nowPage - 1;
  if($backPage <= 0){
    $backPage = 1;
  }
  return '
  <form action="" method="get">
    <input type="hidden" name="tag" value="'.$viewTag.'">
    <button type="submit" name="page" value="'.$backPage.'">前へ</button>
    <button type="submit" name="page" value="1">検索トップ</button>
    <button type="submit" name="page" value="'.$nextPage.'">次へ</button>
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
  $maxPage = 0;
  $scene = "default";

  if(isset($_GET["tag"]) && $_GET["tag"] != ""){
    $scene = "tag";
    $viewTag = $_GET["tag"];
  }
  
  if(isset($_GET["page"])){
    $nowPage = intval($_GET["page"]);
  }else{
    $nowPage = 0;
  }

  if(isset($_GET["i"])){
    $scene = "index";
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
  
  switch($scene){
    case "default":
      $recent = date('Y/m/d', strtotime('-2 week', time()));
      viewDefaultComment($recent, 5);
      break;
    case "tag":
      $maxPage = viewTagComment($viewTag, $nowPage);
      break;
    case "index":
      echo "index";
      break;
  }

  echo '
  </td>
  <td align="center" valign="top" width="25%">'.file_get_contents(__DIR__."\\rightPage.php").'</td>
  </tr>
  <tr>
    <td align="center" colspan="4">
  ';
  if($scene != "default"){ 
    echo printPageButton($viewTag, $nowPage, $maxPage);
  }
  echo '
    </td>
  </tr>
  ';

  ?>
  
  </body>
</html>