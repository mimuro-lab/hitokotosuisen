<?php

require_once(__DIR__."\\utils.php");

function getTagViewContents(int $readDates, int $maxComments)
{
  $pathToCommentFolder = __DIR__."/./../data/comment/";
  $listOfCSV = scandir($pathToCommentFolder);
  $listTmp = array();
  foreach($listOfCSV as $path){
    if($path != "." && $path != ".."){
      array_push($listTmp, $path);
    } 
  }
    $listOfCSV = $listTmp;

  // CSVファイルのリストを取得、最新順に並べ替える。
  $viewListOfCSV = $listOfCSV;
  arsort($viewListOfCSV);

  // 表示対象の内容を保管する配列、要素0伴ね
  $viewContentOfList = array();

  // 作成日が新しいCSVファイルから読み込む。
  $i = 1;
  $diff_len = 0; // 読み込みコメント数を上回った時の、余分な数。
  $all_len = 0;
  foreach($viewListOfCSV as $CSV){
    // 読み込む日数を上回ったらbreak
    if($i > $readDates){
      break;
    }
    $pathToCSV = __DIR__."\\..\\data\\comment\\".$CSV;
    $viewContentOfList =  array_merge($viewContentOfList, read_from_file_all($pathToCSV));
    // 読み込むコメント数を上回ったらbreak
    if(count($viewContentOfList) > $maxComments){
      $diff_len = count($viewContentOfList) - $maxComments;
      break;
    }
    $i ++;
  }
  // コメント読み込み上限を上回っていたら、余分な分popする。
  for($i = 0; $i < $diff_len; $i++){
    array_pop($viewContentOfList);
  }

  return $viewContentOfList;
}


function viewTagComment()
{
  
  // 2週間分、上限10コメント読み込む。
  $viewContents = getTagViewContents(14, 10);

  printHTMLOfComment($viewContents);

}

?>