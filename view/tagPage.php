<?php

require_once(__DIR__."\\utils.php");

function getTagViewContents(string $serachTag)
{
  $pathToCommentPosted = __DIR__."/./../data/posted/";
  $listOfFolder = scandir($pathToCommentPosted);
  $max = 0;
  foreach($listOfFolder as $path){
    if((int)$path > $max){
      $max = (int)$path;
    }
  }
  $listTmp = array_fill(0, $max, "none");
  foreach($listOfFolder as $path){
    if($path != "." && $path != ".."){
      $listTmp[(int)$path] = $path;
    } 
  }
  $listOfFolder = $listTmp;
  $listOfFolder = array_reverse($listOfFolder);
  
  $viewContentOfList = array();

  //　全部たどる。
  foreach($listOfFolder as $path){
    if($path == "none"){
      continue;
    }
    
    $pathToFolder = $pathToCommentPosted."/".$path;
    
    $contentOfTxt = file_get_contents($pathToFolder."/view.txt");
    $contentOfTxt = explode(",", $contentOfTxt);
    $contentOfTagFix = file_get_contents($pathToFolder."/search_kwd_fixed.txt");
    $contentOfTagFix = explode(",", $contentOfTagFix);
    $contentOfTag = file_get_contents($pathToFolder."/search_kwd.txt");
    $contentOfTag = explode(",", $contentOfTag);
    $contentOfCount = file_get_contents($pathToFolder."/count.txt");
    
    $isHit = false;
    
    if($serachTag !== "___time_"){
      
      //tagと一致するか
      $contentOfTag = file_get_contents($pathToFolder."/search_kwd.txt");
      $contentOfTag = explode(",", $contentOfTag);
      foreach($contentOfTag as $tag){
        if($tag === $serachTag){
          $isHit = true;
        }
      }
      if(!$isHit){
        continue;
      }
    }

    $OneViewContents = array();
    $OneViewContents["book"] = $contentOfTxt[0];
    $OneViewContents["date"] = $contentOfTxt[1];
    $OneViewContents["comment"] = $contentOfTxt[2];
    $OneViewContents["index"] = $contentOfTagFix[0];
    $OneViewContents["tag"] = $contentOfTag;
    $OneViewContents["counter"] = $contentOfCount;
    //print_r($viewContentOfList);
    $viewContentOfList[] = $OneViewContents;
    
  }
  
  return $viewContentOfList;
}


function viewTagComment(string $viewTag, int $page)
{
  
  // 2週間分、上限10コメント読み込む。
  $viewContents = getTagViewContents($viewTag);

  $numOfContents = count($viewContents);

  // 一ページに15個のコメントを表示する。
  // 最大ページ数を求める
  
  $printViews = 7;

  $maxPage = ceil($numOfContents / $printViews);

  // 変数$pageの処理
  if($page > $maxPage){
    $page = $maxPage;
  }
  if($page < 1){
    $page = 1;
  }

  // ヒット件数を表示する
  echo count($viewContents).'件のヒット（'.$page.'/'.$maxPage.'）';


  $startIND = ($page - 1) * $printViews;
  
  $printContent = array_slice($viewContents, $startIND, $printViews);
  
  printHTMLOfComment($printContent);

  if($page == $maxPage){
    echo "投稿は以上です";
  }

  return $maxPage;

}

?>