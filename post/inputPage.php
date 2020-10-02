<?php

function showForm(string $token, string $email){

    // cookieに値が保存されている場合は、その値を使う。
    $pre_name = "";
    $pre_number = "";
    $pre_book = "";
    $pre_tag = "";
    $pre_comment = "";
    if(isset($_COOKIE["name"])){
        $pre_name = $_COOKIE["name"];
    }
    if(isset($_COOKIE["number"])){
        $pre_number = $_COOKIE["number"];
    }
    if(isset($_COOKIE["book"])){
        $pre_book = $_COOKIE["book"];
    }
    if(isset($_COOKIE["tag"])){
        $pre_tag = $_COOKIE["tag"];
    }
    if(isset($_COOKIE["comment"])){
        $pre_comment = $_COOKIE["comment"];
    }
    echo '
    <form action="." method="post">
        <input type="hidden" name="email" value="'.$email.'">
        <input type="hidden" name="scine" value="preview_comment">
        <label for="number">〇学籍番号　　　　</label>
        <input type="text" id="number" name="number" value="'.$pre_number.'">
        <label for="name"><br>〇名　前　　　　　</label>
        <input type="text" id="name" name="name" value="'.$pre_name.'">
        <label for="book"><br>〇推薦する本の名前</label>
        <input type="mail" id="book" name="book" value="'.$pre_book.'">
        <label for="tag"><br>〇タ　グ　　　　　</label>
        <input type="text" id="tag" name="tag" value="'.$pre_tag.'">
        <label for="comment"><br><br>〇推薦内容<br></label>
        <textarea id="comment" name="comment"  rows="20" cols="70">'.$pre_comment.'</textarea>
        <br>
        <table width="100%">
        <tr><td align="center">
            <input type="submit" value="プレビュー画面へ行く">
        </td></tr>
        </table>
    </form>';

}

// emailを取得する。tokenにマッチするemailを返す関数。
// 失敗したら必ずfalseを返す。
function get_email(String $token){
    $pathToToken = __DIR__."//../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。(get_email)";
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

function main_inputPage($token){
    setcookie("token", $token);
    if(get_email($token) != false){
        echo "ようこそ、".get_email($token)."さん。<br>以下の項目を全て入力してください。<br><br>";
        showForm($token, get_email($token));
    }
}

?>