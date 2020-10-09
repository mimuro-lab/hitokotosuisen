<?php

require_once(".//utils.php");

date_default_timezone_set('Asia/Tokyo');

function fix_comment($post)
{
    $folderIND = explode(":", explode(",", $post["ID"])[0])[0];
    $pathToFolder = __DIR__."\\..\\data\\posted\\".$folderIND;
    // タグの修正。
    $tag_filePath = $pathToFolder."\\search_kwd.txt";
    $tag_content = $post["book"].",".$post["tag"];
    file_put_contents($tag_filePath, $tag_content);
    
    // コメントの表示に使われるファイル
    $view_filePath = $pathToFolder."\\view.txt";
    $dateOfMake = explode(",", file_get_contents($view_filePath))[1];
    $comment = $post["comment"];
    $comment = str_replace("\r\n", "?newl?", $comment);   //改行をhtml形式に合わせる
    $comment = str_replace(",", "?cma?", $comment);     //,のエスケープ処理（保存形式がCSVになるから）
    $comment = htmlspecialchars($comment);              //htmlのエスケープ処理
    $comment = str_replace("?newl?", "<br>", $comment);
    $view_content = $post["book"].",".$dateOfMake.",".$comment;
    file_put_contents($view_filePath, $view_content);
    
    return true;
}

function main_postPage($post)
{
    
    $success = fix_comment($post);
    if($success){
        echo '
        <br>
        <table width="100%"><tr><td align="center">
        <a style="text-decoration: none;" href="http://localhost:8080/view?index='.explode(":", $post["ID"])[0].'">
        <font size="+2" color="#696969">編集した投稿を見る</font></a></td></tr></table>
        <br><br>
        <table border="1" width="100%">
        <tr><td align="center">
        この記事の編集は完了しました。<br>
        <a href="http://localhost:8080">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
    }else{
        echo "書き込みに失敗しました。";
    }
}

?>