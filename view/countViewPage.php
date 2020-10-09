<?php 

require_once(__DIR__."/./utils.php");

function getAscendContents()
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
  
      $OneViewContents = array();
      $OneViewContents["book"] = $contentOfTxt[0];
      $OneViewContents["date"] = $contentOfTxt[1];
      $OneViewContents["comment"] = $contentOfTxt[2];
      $OneViewContents["index"] = $contentOfTagFix[0];
      $OneViewContents["tag"] = $contentOfTag;
      $OneViewContents["counter"] = $contentOfCount;
      $viewContentOfList[] = $OneViewContents;
      
    }
    foreach ((array) $viewContentOfList as $key => $value) {
        $sort[$key] = $value['counter'];
    }
    
    array_multisort($sort, SORT_ASC, $viewContentOfList);

    return $viewContentOfList;
}

function main_countView(string $upOrDown, int $page)
{
  
  global $viewContents;

    // 昇順
    if($upOrDown == "ascend"){
        $viewContents = getAscendContents();
    }
    // 降順
    if($upOrDown == "descend"){
        $viewContents = getAscendContents();
        $viewContents = array_reverse($viewContents);
    }

    $numOfContents = count($viewContents);

    // 一ページに15個のコメントを表示する。
    // 最大ページ数を求める

    $printViews = 7;
    $maxPage = ceil($numOfContents / $printViews);

    // ヒット件数を表示する
    echo count($viewContents).'件のヒット（'.$page.'/'.$maxPage.'）';

    $startIND = ($page - 1) * $printViews;
    if($startIND <= 0){
      $startIND = 0;
    }
  
    $viewContents = array_slice($viewContents, $startIND, $printViews);
    printHTMLOfComment($viewContents);
    //printHTMLOfComment($viewContents);

    if($page == $maxPage){
      echo "投稿は以上です";
    }

    return $maxPage;

}

?>