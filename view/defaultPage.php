<?php



// 存在するファイルの中身を読み込む関数。
// 読み込むのは、日付・タイトル・タグ・コメント内容
// 返すのは、[[日付],[タイトル],[タグ],[コメント内容]]の配列
function read_from_file_all(String $filename){
  // ファイルがなかったらリターンする。
  if(!file_exists($filename)){
      echo "not exit file (function:read_from_file_all)<br>".$filename;
      return ;
  }

  // ファイルを開けなかったらリターンする。
  if(!fopen($filename, "r")){
      echo "cannot open file (function:read_from_file_all)";
      return ;
  }
  $fp = fopen($filename, "r");
  // ファイルの中身を格納する配列
  $contentOfText = array();
  while(!feof($fp)){
    // fgetにより一行読み込み
    $line = fgets($fp);
    if(count(explode(",", $line)) != 9){
      continue;
    }
    $date = explode(",", $line)[2];
    $bookAndTag = explode(",", $line)[6];
    $book = explode(":", $bookAndTag)[0];
    $tag = str_replace($book.":", "", $bookAndTag);
    $comment = explode(",", $line)[7];

    // 文字のエスケープ処理
    $comment = str_replace("?newl?", "<br>", $comment);

    $pushLine = array("date"=>$date, "book"=>$book, "tag"=>$tag, "comment"=>$comment);

    array_push($contentOfText, $pushLine);

  }
      
  return $contentOfText;
}

function getViewContents(int $readDates, int $maxComments)
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

function printHTMLOfComment($listOfContents)
{
  
  foreach($listOfContents as $comment){
    echo '
    <table border="0" width="100%">
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    <tr>
      <td style="word-break: break-all;">'.$comment["book"].'</td>
      <td style="word-break: break-all;"  align="right">'.$comment["date"].'</td>
    </tr>
    <tr>
      <td style="word-break: break-all;"  colspan="2">'.$comment["comment"].'</td>
    </tr>
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    </table>  

    ';
  }

}

function viewDefaultComment()
{
  
  // 2週間分、上限10コメント読み込む。
  $viewContents = getViewContents(14, 10);

  printHTMLOfComment(($viewContents));

  
}

?>