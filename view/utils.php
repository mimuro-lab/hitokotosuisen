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

function getEndComment(string $oriComment, int $restLines)
{
  $listOfComment = explode("<br>", $oriComment);
  if(count($listOfComment) < $restLines){
    return "";
  }else{
    return "以下省略";
  }
}

// date, book, index, tag, commentが必要
function printHTMLOfComment($listOfContents)
{
  foreach($listOfContents as $comment){
    $rinkDate = substr($comment["date"], 0, 10);
    $date = str_replace($rinkDate, "", $comment["date"]);
    echo '
    <table border="0" width="100%" bgcolor="#fafafa">
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    <tr>
      <td style="word-break: break-all;"><font size="+1" face="arial black">'.$comment["book"].'</font></td>
      <td style="word-break: break-all;"  align="right">
      <font size="-1">'.$comment["date"].'&nbsp;INDEX:'.$comment["index"].'<br></font>
      <font size="-1" style="opacity:0.7" face="arial unicode ms">'.$comment["counter"].'&nbsp;回閲覧</font>
      </td>
    </tr>

    <tr>
      <td style="word-break: break-all;"  colspan="2"><font size="-1" face="arial unicode ms">'.getPreviewComment($comment["comment"], 3).'</font></td>
    </tr>
    <tr>
      <td style="opacity:0.5" colspan="2" style="word-break: break-all;"  colspan="2"><font size="-1" face="arial unicode ms">'.getEndComment($comment["comment"], 3).'</font></td>
    </tr>
    <tr>
      <td style="opacity:0.8" colspan="2" align="center"><a href="http://localhost:8080/view/?index='.$comment["index"].'"><font color="#696969">この投稿を全部見る</td>
    </tr>
    
    <tr><td colspan="2" align="right">関連するタグ<br>
    ';

    foreach($comment["tag"] as $tag){
      echo '<a href="http://localhost:8080/view/?tag='.$tag.'"><font size="-1" color="#6495ed">'.$tag.'</a>&nbsp;';
    }

    echo '
    </td></tr>
    <tr>
      <td align="right" colspan="2"></td>
    </tr>
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    
    </table> ';
  }  

}
?>