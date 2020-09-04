<?php

// 存在するファイルの中身を読み込む関数。すべて読み込む。
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
    // ファイルの中身を格納する変数
    $contentOfText = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $contentOfText .= fgets($fp);
    }
        
    return $contentOfText;
}

#echo read_from_file_all(__DIR__."\comment\page1\電気回路.csv")

function echoListOfComment(){
    $listdir = scandir(__DIR__."\comment");
    foreach ($listdir as $dir){
        if($dir != "." && $dir != "..") {
            $getParameter = "?comment_page=".$dir;
            echo "<a href=\"http://localhost:8080\\data\\readed_comment.php".$getParameter."\">".$dir."</a><br>";
        }
    }
}

echoListOfComment();

?>

<h1>ここでは、投稿されたコメントを読み込みます。</h1>
