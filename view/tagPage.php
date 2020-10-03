<?php

require_once(__DIR__."\\utils.php");

function getTagViewContents(string $serachTag)
{
  $pathToCommentFolder = __DIR__."/./../data/comment/";
  $listOfCSV = scandir($pathToCommentFolder);
  $listTmp = array();
  foreach($listOfCSV as $path){
    if($path != "." && $path != ".." && $path !== "fix_tmp.csv"){
      array_push($listTmp, $path);
    } 
  }
    $listOfCSV = $listTmp;

  // CSVファイルのリストを取得、最新順に並べ替える。
  $viewListOfCSV = $listOfCSV;
  arsort($viewListOfCSV);

  // 表示対象の内容を保管する配列、要素0伴ね
  $viewContentOfList = array();

  // 作成日が新しいCSVファイルから読み込む。すべて。
  foreach($viewListOfCSV as $CSV){
    $pathToCSV = __DIR__."\\..\\data\\comment\\".$CSV;
    $viewContentOfList =  array_merge($viewContentOfList, array_reverse(read_from_file_all($pathToCSV)));
  }

  // タグが___time_だったら、全てが対称。
  if($serachTag == "___time_"){
    return $viewContentOfList;
  }
  // $serachTagを含むもののみを抽出。
  $tmpContent = array();
  foreach($viewContentOfList as $content){
    if(strpos($content["tag"], $serachTag) !== false){
      
    }
    if(strpos($content["tag"], $serachTag) !== false ||
       strpos($content["book"], $serachTag) !== false){
      array_push($tmpContent, $content);
    }
  }

  $viewContentOfList = $tmpContent;

  return $viewContentOfList;
}


function viewTagComment(string $viewTag, int $page)
{
  
  // 2週間分、上限10コメント読み込む。
  $viewContents = getTagViewContents($viewTag);



  $numOfContents = count($viewContents);

  // 一ページに15個のコメントを表示する。
  // 最大ページ数を求める
  $maxPage = ceil($numOfContents / 15);

  // 変数$pageの処理
  if($page > $maxPage){
    $page = $maxPage;
  }
  if($page < 1){
    $page = 1;
  }

  // ヒット件数を表示する
  echo count($viewContents).'件のヒット（'.$page.'/'.$maxPage.'）';

  $startIND = ($page - 1) * 15;
  
  $printContent = array_slice($viewContents, $startIND, 15);
  
  printHTMLOfComment($printContent);

}

?>