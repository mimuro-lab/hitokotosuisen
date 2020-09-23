
<?php

// ランダムな英数字を作成する。同じ文字が出現する可能性あり。
// 第一引数には文字列の長さを入力する。
function random($length)
{
    return base_convert(mt_rand(pow(36, $length - 1), pow(36, $length) - 1), 10, 36);
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

    // 3つの要素（page,book,lineID）で構成されていなかったらfalseを返す
    if(count(explode(":",$ID)) != 3){
        return false;
    }

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
// コメント内容に関するidとtokenのチェックを行う。
// idとtokenが入力され、両方マッチするものがある場合は、その内容を返す。（idとtokenがつながったものを入力）
// マッチするものがなかった場合、falseを返す。
function check_comment_token(String $ID_and_token){
    
    // 4つの要素（page,book,lineID, token）で構成されていなかったらfalseを返す
    if(count(explode(":",$ID_and_token)) != 4){
        return false;
    }

    $page = explode(":",$ID_and_token)[0];
    $book = explode(":",$ID_and_token)[1];
    $lineID = (int)explode(":",$ID_and_token)[2];
    $token = explode(":",$ID_and_token)[3];

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
        $nowToken = explode(",", $contentOfText)[1];
        if($lineID == $nowID){
            return $contentOfText;
        }
    }

    return false;
}

// 既存のコメント内容を上書きする関数。第一引数には、token付きIDが入力される。
// 成功したら上書き後のコメント内容を返す。
function fix_comment(String $ID_and_token, String $input_number, String $input_name, String $input_book, String $input_comment){

    $input_number = str_replace(",", "?cma?", $input_number);
    $input_name = str_replace(",", "?cma?", $input_name);
    $input_book = str_replace(",", "?cma?", $input_book);
    //echo mb_detect_encoding($input_comment);
    $input_comment = str_replace(",", "?cma?", $input_comment);
    // コメント内の改行文字("\r\n")は　"?newl?"　に置き換える（htmlspecialcharsを回避）
    $input_comment = str_replace("\r\n", "?newl?", $input_comment);
    // コメント内の" <br> "は　"?newl?"　に置き換える（htmlspecialcharsを回避）
    $input_comment = str_replace("<br>", "?newl?", $input_comment);
    // htmlの特殊文字は置き換える。
    $input_comment = htmlspecialchars($input_comment);

    $page = explode(":", $ID_and_token)[0];
    $book = explode(":", $ID_and_token)[1];
    $lineID = explode(":", $ID_and_token)[2];
    $token_comment = explode(":", $ID_and_token)[3];

    // 正しいtokenを持っていなかったらリターンする
    $pre_content = check_comment_token($ID_and_token);
    if($pre_content == false){
        echo "有効なtokenが受信できませんでした。<br>";
        return false;
    }

    $filename_tmp = __DIR__."\\comment"."\\fix_tmp.csv";
    $filename = __DIR__."\\comment\\".$page."\\".$book.".csv";

    // ファイルがなかったらリターンする。
    if(!file_exists($filename)){
        echo "ファイル".$filename."が存在しませんでした。よって、書き込み処理を行いませんでした";
        return false;
    }

    // ファイルを開けなかったらリターンする。
    if(!fopen($filename, "r") && !fopen($filename_tmp, "w")){
        return false;
    }
    

    echo $pre_content;

    // 修正前の要素を一時保存
    $id_pre = explode(",",$pre_content)[0];
    $token_pre = explode(",",$pre_content)[1];
    $date_pre = explode(",",$pre_content)[2];
    $number_pre = explode(",",$pre_content)[3];
    $name_pre = explode(",",$pre_content)[4];
    $email_pre = explode(",",$pre_content)[5];
    $book_pre = explode(",",$pre_content)[6];
    $comment_pre = explode(",",$pre_content)[7];

    // 編集後の内容
    $fixed_content = $id_pre.",".$token_pre.",".$date_pre.",";

    // 変更内容が入力されていれば、コメントの修正を行う。
    // 学籍番号
    if($input_number != ""){
        $fixed_content .= $input_number.",";
    }else{
        $fixed_content .= $number_pre.",";
    }
    // 名前
    if($input_name != ""){
        $fixed_content .= $input_name.",";
    }else{
        $fixed_content .= $name_pre.",";
    }
    // メールは変更不可
    $fixed_content .= $email_pre.",";

    // book
    if($input_book != ""){
        $fixed_content .= $input_book.",";
    }else{
        $fixed_content .= $book_pre.",";
    }
    // comment
    if($input_comment != ""){
        $fixed_content .= $input_comment.",";
    }else{
        $fixed_content .= $comment_pre.",";
    }
    
    // 編集後の内容をtmpファイルに書き込む。
    $fp = fopen($filename, "r");
    $fp_tmp = fopen($filename_tmp, "w");

    // 編集対象の内容を一時的にfix_tmp.csvに避難させる。
    while(!feof($fp)){
        $OneLine = fgets($fp);
        $nowID = explode(",", $OneLine)[0];
        if($nowID == $lineID){
            fwrite($fp_tmp, $fixed_content);
        }else{
            fwrite($fp_tmp, $OneLine);
        }
    }

    // tmpファイルの内容を、対象のファイルに上書きする。
    copy($filename_tmp, $filename);

}

?>