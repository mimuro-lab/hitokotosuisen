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

$comment_page = $_GET["comment_page"];

$FolderToComment = __DIR__."\\comment\\".$comment_page;
$result = glob($FolderToComment."\\*.csv");

foreach($result as $pathToCSV){
    echo read_from_file_all($pathToCSV);
}

?>