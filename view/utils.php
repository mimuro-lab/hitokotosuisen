<?php

function getPreviewComment(string $oriComment, int $restLines)
{

  $listOfComment = explode("<br>", $oriComment);
  $trimedComment = array();
  if(count($listOfComment) < $restLines){
    $restLines = count($listOfComment);
  }
  $i = 0;
  foreach($listOfComment as $line){
    if($i >= $restLines){
      break;
    }
    array_push($trimedComment, $line);
    $i ++;
  }
  
  // コメントをstrinに戻す
  $retComment = "";

  for($i = 0; $i < $restLines; $i++){
    if($i == $restLines - 1){ 
      $retComment .= $trimedComment[$i];
    }else{
      $retComment .= $trimedComment[$i]."<br>";
    }
  }
  
  return $retComment;
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
      <td style="word-break: break-all;"  colspan="2">'.getPreviewComment($comment["comment"], 3).'</td>
    </tr>
    <tr><td colspan="2" align="right">関連するタグ<br>
    ';

    foreach($comment["tag"] as $tag){
      echo '<a href="http://localhost:8080/view/?tag='.$tag.'">'.$tag.'</a><br>';
    }

    echo '
  </td></tr>
    <tr>
      <td align="right" colspan="2">INDEX:<a href="http://localhost:8080/view/?i='.$comment["index"].'">'.$comment["index"].'</a></td>
    </tr>
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    </table> ';
  }  
}
?>