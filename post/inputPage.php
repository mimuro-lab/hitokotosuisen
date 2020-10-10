<?php

// emailを取得する。tokenにマッチするemailを返す関数。
// 失敗したら必ずfalseを返す。
function get_email(String $token){
    $pathToToken = __DIR__."/../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        //echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "a")){
        //echo "token.csvを開けませんでした。";
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

    //echo "tokenに対するメールアカウントを見つけられませんでした。";
    return false;
}

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
    <table border="0" width="100%" bgcolor="#fafafa">

    <tr>
    <td width="50%" align="center">〇学籍番号</td><td width="50%" align="center"><input type="text" id="number" name="number" value="'.$pre_number.'"></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇名　前　</td><td width="50%" align="center"><input type="text" id="name" name="name" value="'.$pre_name.'"></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇タ　グ　</td><td width="50%" align="center"><input type="text" size="45" name="tag" value="'.$pre_tag.'"></input>
    </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇推薦する本の名前</td>
    <td width="50%" align="center"><input type="text" name="book" value="'.$pre_book.'"></input></td>
    </td>
    <tr><td><br></td></tr>
    <tr><td><br></td></tr>
    </tr>
    <tr><td colspan="2" align="center">〇推薦内容</td></tr>
    <tr><td colspan="2" align="center">
    <textarea name="comment" rows="20" cols="80">'.$pre_comment.'</textarea>
    </td></tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <input type="hidden" name="email" value="'.$email.'">
                <input type="hidden" name="token" value="'.$token.'">
                <input type="hidden" name="scene" value="preview_comment">
                <input type="submit" value="プレビュー画面へ行く">
            </td>
        </tr>
    </table>
    </form>
    ';

}

function main_inputPage($token){
    setcookie("token", $token);
    if(get_email($token) != false){
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#000000">
        ようこそ、'.get_email($token).'さん。<br>以下の項目を全て入力してください。<br><br></font>
        </tr></td></table>';
        showForm($token, get_email($token));
    }else{
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#000000">
        無効なURLを受け取りました。<br>
        もう一度最初からやり直してください。<br>
        </font></tr></td></table>
        ';
    }
}

?>