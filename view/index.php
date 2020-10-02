<?php

require_once(__DIR__."\\defaultPage.php");

?>

<!DOCTYPE html>
<html>
  <head>
    <title>閲覧ページ</title>
  </head>
  <body>
  <?php
  // メイン処理
  // 閲覧ページの状態を表す変数
  $viewTag = "";
  $isDefaultPage = false;
  if(isset($_GET["tag"])){
    $viewTag = $_GET["tag"];
  }else{
    $isDefaultPage = true;
  }
  
  // デフォルト（タグが入力されていない）ページなら、
  // コメントを上から10個表示する。
  if($isDefaultPage){
    // ２週間分、30コメント読み込む。
    viewDefaultComment(14, 30);
  }else{

  }

  ?>
  </body>
</html>