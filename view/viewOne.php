<?php

function main_viewOne(int $index)
{
    $pathToCommentPosted = __DIR__."/./../data/posted/";
		$listOfFolder = scandir($pathToCommentPosted);
		$isFind = false;
    
    foreach($listOfFolder as $path){
			if($path == "." || $path == ".."){
				continue;
			}
			$path = preg_replace('/[^0-9]/', '', $path);
      if($index == intval($path)){
				$isFind = true;
      }
		}
		if(!$isFind){
			echo 'INDEX:<i>'.$index.'</i>に一致する投稿は見つけられませんでした。';
			return ;
		}	
    
    $pathToFolder = $pathToCommentPosted."/".$index;

    // 閲覧数を記録する
    $pathToCount = $pathToFolder."/count.txt";
    if(!isset($_COOKIE["visit"]) || intval($_COOKIE["visit"]) != $index){  
      $counter = intval(file_get_contents($pathToCount));
      file_put_contents($pathToCount, $counter+1);
    }
    $counter = file_get_contents($pathToCount);

    setcookie("visit", $index, time() + 60 * 10);

    $contentOfTxt = file_get_contents($pathToFolder."/view.txt");
    $contentOfTxt = explode(",", $contentOfTxt);
    $contentOfTagFix = file_get_contents($pathToFolder."/search_kwd_fixed.txt");
    $contentOfTagFix = explode(",", $contentOfTagFix);
    $contentOfTag = file_get_contents($pathToFolder."/search_kwd.txt");
		$contentOfTag = explode(",", $contentOfTag);
		
		$OneViewContents = array();
    $OneViewContents["book"] = $contentOfTxt[0];
    $OneViewContents["date"] = $contentOfTxt[1];
    $OneViewContents["comment"] = $contentOfTxt[2];
    $OneViewContents["index"] = $contentOfTagFix[0];
		$OneViewContents["tag"] = $contentOfTag;
		
		echo '
    <table border="0" width="100%">
    <tr>
      <td colspan="2"><hr style="height:3px;"></td>
    </tr>
    <tr>
      <td style="word-break: break-all;">
      <font size="+2" face="arial black">'.$OneViewContents["book"].'</font>
      </td>
      <td style="word-break: break-all;"  align="right">'.$OneViewContents["date"].'
      INDEX:'.$OneViewContents["index"].'
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right"><font style="opacity:0.7" face="arial unicode ms">'.$counter.'&nbsp;回閲覧</font></td>
    </tr>
    <tr><td colspan="2" align="center"><font style="opacity:0.5" size="-1" face="arial unicode ms">推薦内容</font></td></tr>
    <tr>
      <td style="word-break: break-all;"  colspan="2">'.$OneViewContents["comment"].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr><td colspan="2" align="right"><font style="opacity:0.5" size="-1" face="arial unicode ms">以上</font></td></tr>
    <tr>
    <td colspan="2"><hr style="height:3px;"></td>
  </tr>
    <tr><td colspan="2" align="right">関連するタグ<br>
    ';

    foreach($OneViewContents["tag"] as $tag){
      if($tag != ""){
        echo '&nbsp;&nbsp;<a href="http://localhost:8080/view/?tag='.$tag.'">'.$tag.'</a>';
      }
    }

    echo '
  </td></tr>
  <tr><td align="center" colspan="2">
    <br><br><a href="javascript:history.back()">[戻る]</a><br><br>
  </td></tr>
  </table> ';

}

?>