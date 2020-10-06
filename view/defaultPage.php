<?php
require_once(__DIR__."\\utils.php");

function getDefailtViewContents(string $recentDate, int $maxComments)
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

  //　必要な個数分取る
  foreach($listOfFolder as $path){
    if($path == "none"){
      continue;
    }
    
    if(count($viewContentOfList) >= $maxComments){
      break;
    }
    
    $pathToFolder = $pathToCommentPosted."/".$path;
    
    $contentOfTxt = file_get_contents($pathToFolder."/view.txt");
    $contentOfTxt = explode(",", $contentOfTxt);

    $OneViewContents = array();
    $OneViewContents["book"] = $contentOfTxt[0];
    $OneViewContents["date"] = $contentOfTxt[1];
    $OneViewContents["comment"] = $contentOfTxt[2];
    //print_r($viewContentOfList);
    $viewContentOfList[] = $OneViewContents;
    
  }

  // 日程が古い物は捨てる
  $tmp = array();
  
  foreach($viewContentOfList as $content){

    $contentYear = explode("/", $content["date"])[0];
    $contentMonth = explode("/", $content["date"])[1];
    $contentDay = preg_replace('/[^0-9]/', '', explode("/", $content["date"])[2]);
    $contentDate = $contentYear."/".$contentMonth."/".$contentDay;
      
    if(strtotime($recentDate) < strtotime($contentDate)){
      $tmp[] = $content;
    }
      
  }
    
  $viewContentOfList = $tmp;

  return $viewContentOfList;
}

function viewDefaultComment(string $recentDate, int $maxComments)
{
  
  // 2週間分、上限10コメント読み込む。
  $viewContents = getDefailtViewContents($recentDate, $maxComments);

  printHTMLOfComment($viewContents);

}

?>