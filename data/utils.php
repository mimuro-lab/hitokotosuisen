
<?php

// ランダムな英数字を作成する。同じ文字が出現する可能性あり。
// 第一引数には文字列の長さを入力する。
function random($length)
{
    return base_convert(mt_rand(pow(36, $length - 1), pow(36, $length) - 1), 10, 36);
}

// tokenを取得する。emailにマッチするtokenを返す関数。
function get_token(String $email){
    $pathToToken = __DIR__."/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "a")){
        echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    
    // 一行ずつ読み込む
    $tokenLine = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $tokenLine = fgets($fp);
        // 最後の行になったらbreak
        if($tokenLine == ""){
            break;
        }   
        if(str_getcsv($tokenLine)[0] == $email){
            return str_getcsv($tokenLine)[1];
        }
    }

    echo "メールアカウントに対するtokenを見つけられませんでした。";
    return false;
}

// tokenを削除する。与えられたtokenの行を削除する。
function delete_token(String $token){
    $pathToToken = __DIR__."/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "r")){
        echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    $pathToTmp = __DIR__."/token_tmp.csv";
    $fp_tmp = fopen($pathToTmp, "w");
    
    // 一行ずつ読み込み、tmpファイルに書き込む
    $tokenLine = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $tokenLine = fgets($fp);
        // 最後の行になったらbreak
        if($tokenLine == ""){
            break;
        }   
        if(str_getcsv($tokenLine)[1] != $token){
            fwrite($fp_tmp, $tokenLine);
        }
    }

    // tmpファイルの内容をtoken.csvに上書きする。
    if(copy($pathToTmp, $pathToToken)){
    }

}

// emailを取得する。tokenにマッチするemailを返す関数。
// 失敗したら必ずfalseを返す。
function get_email(String $token){
    $pathToToken = __DIR__."/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "a")){
        echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    
    // 一行ずつ読み込む
    $tokenLine = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $tokenLine = fgets($fp);
        #if(strpos($tokenLine, $token) !== false){
        // 最後の行になったらbreak
        if($tokenLine == ""){
            break;
        }
        if(str_getcsv($tokenLine)[1] == $token){
            return str_getcsv($tokenLine)[0];
        }
    }

    echo "tokenに対するメールアカウントを見つけられませんでした。";
    return false;
}

// 最も大きいID番号を返す関数。
function getID_recent($filename){
    // ファイルがなかったらリターンする。
    if(!file_exists($filename)){
        echo "ファイル".$filename."が存在しませんでした。よって、処理を行いませんでした";
        return false;
    }

    $fp = fopen($filename, "r");
    // ファイルの中身を格納する変数
    $maxID = -1;

    $contentOfText = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $contentOfText = fgets($fp);
        if($contentOfText == ""){
            break;
        }
        $nowID = (int)explode(",", $contentOfText)[0];
        if($maxID < $nowID){
            $maxID = $nowID;
        }
    }
    
    return $maxID;
}

// 管理用IDから、コメントの内容を取得する関数。
// 対応する行をそのまま返す
function get_content($ID){
    
    $page = explode(":",$ID)[0];
    $book = explode(":",$ID)[1];
    $lineID = (int)explode(":",$ID)[2];

    $pathToCSV = __DIR__."\\comment\\".$page."\\".$book.".csv";
    if(!file_exists($pathToCSV)){
        return false;
    }

    $fp = fopen($pathToCSV, "r");

    // ファイルの中身を格納する変数
    $contentOfText = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $contentOfText = fgets($fp);
        if($contentOfText == ""){
            break;
        }
        $nowID = (int)explode(",", $contentOfText)[0];
        if($lineID == $nowID){
            return $contentOfText;
        }
    }

    return false;
}

?>