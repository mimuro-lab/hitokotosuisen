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
  

function printHTMLOfComment($listOfContents)
{
  foreach($listOfContents as $comment){
    $rinkDate = substr($comment["date"], 0, 10);
    $date = str_replace($rinkDate, "", $comment["date"]);
    echo '
    <table border="0" width="100%">
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    <tr>
      <td style="word-break: break-all;">'.$comment["book"].'</td>
      <td style="word-break: break-all;"  align="right"><a href="http://localhost:8080/view/?tag='.$rinkDate.'">'.$rinkDate.'</a>'.$date.'</td>
    </tr>
    <tr>
      <td style="word-break: break-all;"  colspan="2">'.$comment["comment"].'</td>
    </tr>
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    </table> ';
  }  
}
?>