<?php

require_once(".//utils.php");

date_default_timezone_set('Asia/Tokyo');

// コメント内容に関するidとtokenのチェックを行う。
// idとtokenが入力され、両方マッチするものがある場合は、その内容を返す。（idとtokenがつながったものを入力）
// マッチするものがなかった場合、falseを返す。
function check_comment_token(String $ID_and_token){
    
    // 3つの要素（date,lineID, token）で構成されていなかったらfalseを返す
    if(count(explode(":",$ID_and_token)) != 3){
        return false;
    }

    $date = explode(":",$ID_and_token)[0];
    $lineID = (int)explode(":",$ID_and_token)[1];
    $token = explode(":",$ID_and_token)[2];

    $pathToCSV = __DIR__."\\..\\data\\comment\\".$date.".csv";
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
        if($lineID == $nowID && $nowToken == $token){
            return $contentOfText;
        }
    }
    
    return false;
}

// 既存のコメント内容を上書きする関数。第一引数には、token付きIDが入力される。
// 成功したら上書き後のコメント内容を返す。
function fix_comment(String $ID_and_token, String $input_number, String $input_name, String $input_book, String $input_tag, String $input_comment){

    $input_tag = str_replace(",", ":", $input_tag);
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


    //$page = explode(":", $ID_and_token)[0];
    $date = explode(":", $ID_and_token)[0];
    $lineID = explode(":", $ID_and_token)[1];
    $token_comment = explode(":", $ID_and_token)[2];
    //$token_comment = explode(":", $ID_and_token)[3];

    // 正しいtokenを持っていなかったらリターンする
    $pre_content = check_comment_token($ID_and_token);
    if($pre_content == false){
        echo "有効なtokenが受信できませんでした。<br>";
        return false;
    }

    $filename_tmp = __DIR__."\\..\\data\\comment"."\\fix_tmp.csv";
    $filename = __DIR__."\\..\\data\\comment\\".$date.".csv";

    // ファイルがなかったらリターンする。
    if(!file_exists($filename)){
        echo "ファイル".$filename."が存在しませんでした。よって、書き込み処理を行いませんでした";
        return false;
    }

    // ファイルを開けなかったらリターンする。
    if(!fopen($filename, "r") && !fopen($filename_tmp, "w")){
        echo "ファイル".$filename."が存在しませんでした。よって、書き込み処理を行いませんでした";        
        return false;
    }


    // 修正前の要素を一時保存
    $id_pre = explode(",",$pre_content)[0];
    $token_pre = explode(",",$pre_content)[1];
    $date_pre = explode(",",$pre_content)[2];
    $number_pre = explode(",",$pre_content)[3];
    $name_pre = explode(",",$pre_content)[4];
    $email_pre = explode(",",$pre_content)[5];
    $bookAndTag_pre = explode(",",$pre_content)[6];
    $book_pre = explode(":", $bookAndTag_pre)[0];
    $tag_pre = "";
    if(count(explode(":", $bookAndTag_pre)) > 1){
        $tag_pre = explode(":", $bookAndTag_pre)[1];
    }
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
        $fixed_content .= $input_book;
    }else{
        $fixed_content .= $book_pre;
    }

    // tag
    if($input_tag != ""){
        $fixed_content .= ":".$input_tag.",";
    }else{
        $fixed_content .= ":".$tag_pre.",";
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

    return true;

}

function main_postPage($post)
{
    $success = fix_comment($post["ID"], $post["number"], $post["name"], $post["book"], $post["tag"], $post["comment"]);
    if($success){
        echo '
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