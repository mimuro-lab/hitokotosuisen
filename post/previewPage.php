<?php
date_default_timezone_set('Asia/Tokyo');

function getFixedTagFromTable($key)
{
    if($key == "date"){
        $w = date("w");
        $week_name = array("日", "月", "火", "水", "木", "金", "土");
        $dateOfMake = date("Y/m/d") . "($week_name[$w]) ".date("H:i");
        $dateOfTag =  date("Y/m/d");
        return $dateOfTag;
    }
    if($key == "book"){
        return $_POST["book"];
    }
    $fixedTagsFromTable = explode(",", file_get_contents(__DIR__."\\..\\data\\tagTable.txt"));
    return $fixedTagsFromTable[(int)$key];
}

function getFixedTags()
{
    // 固定タグのプレビュー
    $fixed_tags = array();
    foreach($_POST as $p){
        if(substr($p, 0, 11) == "checked_fix"){
            $tagKey = str_replace("checked_fix_", "", $p);
            $fixedTag = getFixedTagFromTable($tagKey);
            array_push($fixed_tags, $fixedTag);
        }
    }
    return $fixed_tags;
}

function isSetAll($post)
{
    if(isset($post["number"]) && $post["number"] !== "" &&  
       isset($post["name"]) && $post["name"] !== "" &&  
       isset($post["book"]) &&  $post["book"] !== "" &&  
       isset($post["comment"]) && $post["comment"] !== "" ){
           return true;
    }
    return false;
}

function printButton($next, $post)
{
    //入力画面へ戻るボタンと、確定ボタン
    echo '
    <head>
        <title>トップページ</title>
        <meta charset="utf-8">
    </head>
    <table width="100%">
    <tr>
    
    <td align="left">
    <form action="" method="post">
        <input type="hidden" name="scene" value="input_comment">
        <input type="hidden" name="token" value="'.$post["token"].'">
        <button type="submit">　戻る　</button>
    </form>
    </td>

    <td align="right">
    ';
    if($next){
        $send_comment = htmlspecialchars($post["comment"]);
        echo '
        <form action="" method="post">
            <input type="hidden" name="scene" value="post_comment">
            <input type="hidden" name="email" value="'.$post["email"].'">
            <input type="hidden" name="number" value="'.$post["number"].'">
            <input type="hidden" name="name" value="'.$post["name"].'">
            <input type="hidden" name="book" value="'.$post["book"].'">
            <input type="hidden" name="tag" value="'.$post["tag"].'">
            <input type="hidden" name="comment" value="'.$send_comment.'">
            <input type="hidden" name="token" value="'.$post["token"].'">
        ';
        // 固定タグ
        $fixed = "";
        foreach(getFixedTags() as $f){
            $fixed .=$f.",";
        }
        echo '
            <input type="hidden" name="fixedTag" value="'.$fixed.'">
            <button type="submit">投稿する</button>
        </form>
        ';
    }

    echo '
    </td>

    </tr>
    </table>
    
    ';

}

function printPreview($post)
{
    $post["comment"] = str_replace("\r\n", "?newl?", $post["comment"]);
    $post["comment"] = htmlspecialchars($post["comment"]);
    $post["comment"] = str_replace("?newl?", "<br>", $post["comment"]);

    $number = false;
    $name = false;
    $book = false;
    $tag = false;
    $comment = false;
    if(isset($post["number"]) && $post["number"] !==""){
        $number = $post["number"];
    }
    if(isset($post["name"]) && $post["name"] !==""){
        $name = $post["name"];
    }    
    if(isset($post["book"]) && $post["book"] !==""){
        $book = $post["book"];
    }    
    if(isset($post["tag"]) && $post["tag"] !==""){
        $tag = explode(",", $post["tag"]);
    }    
    if(isset($post["comment"]) && $post["comment"] !==""){
        $comment = $post["comment"];
    }

    echo '
    <table border="0" width="100%" bgcolor="#fafafa">
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td align="center" width="50%">〇学籍番号</td><td align="center" width="50%">';
    
    if($number !== false){
        echo $number;
    }else{
        echo '<font color="red">未入力</font>';
    }
    
    echo '</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td align="center" width="50%">〇名　前　</td><td align="center" width="50%">';

    if($name !== false){
        echo $name;
    }else{
        echo '<font color="red">未入力</font>';
    }

    echo '</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td align="center" width="50%">〇固定タグ</td><td align="center" width="50%">';
    $fixed_tags = getFixedTags();
    foreach($fixed_tags as $f){
        echo $f."<br>";
    }
    echo '
    </td></tr>
    <tr><td><br></td></tr>
    <tr>
    <td align="center" width="50%">〇自由タグ</td><td align="center" width="50%">';

    if($tag !== false){
        foreach($tag as $t){
            echo $t;
            if($t !== ""){
                echo "<br>";
            }
        }
    }else{
        echo 'なし';
    }

    echo '</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td align="center" width="50%">〇推薦する本の名前</td><td align="center" width="50%">';

    if($book !== false){
        echo $book;
    }else{
        echo '<font color="red">未入力</font>';
    }

    echo '</td>
    </tr>
    <tr><td><br></td></tr>

    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td align="center" colspan="2">〇推薦内容</td>
    </tr>
    <tr>
        <td colspan="2">';
    
    if($comment !== false){
        echo $comment;
    }else{
        echo '<font color="red">未入力</font>';
    }
    
    echo '</td>
    </tr>
    <tr>
        <td align="center" colspan="2"><hr></td>
    </tr>
    </table>

    ';

    // 確定ボタンを押しても良いかどうか？
    if($number !== false && $name !== false && $book !== false && $comment !== false){
        return true;
    }else{
        return false;
    }

}

function main_previewPage($post){

    setcookie("email", $post["email"], time() + 60 * 15);
    setcookie("number", $post["number"], time() + 60 * 15);
    setcookie("name", $post["name"], time() + 60 * 15);
    setcookie("book", $post["book"], time() + 60 * 15);
    setcookie("tag", $post["tag"], time() + 60 * 15);
    setcookie("comment", $post["comment"], time() + 60 * 15);

    if(!isSetAll($post)){
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#000000">
        以下の<font color="red">未入力</font>を入力してください。<br>戻るボタンから入力しなおせます。
        </font></td></tr></table>';
    }else{
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#000000">
        入力した内容が正しければ、確定ボタンを押してください。<br>
        再度入力したければ、戻るボタンを押してください。<br>
        </font></td></tr></table>';
    }

    $next = printPreview($post);
    printButton($next, $post);

}

?>