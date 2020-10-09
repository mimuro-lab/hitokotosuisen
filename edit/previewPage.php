<?php

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

function printButton_EditedPreview($next, $post)
{
    //入力画面へ戻るボタンと、確定ボタン
    echo '
    <table width="100%">
    <tr>
    <td align="left">
    <form action="" method="post">
        <input type="hidden" name="scene" value="edit_comment">
        <input type="hidden" name="back" value="backed">
        <input type="hidden" name="ID" value="'.$post["ID"].'">
        <input type="hidden" name="number" value="'.$post["number"].'">
        <input type="hidden" name="email" value="'.$post["email"].'">
        <input type="hidden" name="name" value="'.$post["name"].'">
        <input type="hidden" name="book" value="'.$post["book"].'">
        <input type="hidden" name="tag" value="'.$post["tag"].'">
        <input type="hidden" name="comment" value="'.$post["comment"].'">
        <button type="submit">　戻る　</button>
    </form>
    </td>

    <td align="right">
    ';
    if($next){
    echo '
    <form action="" method="post">
        <input type="hidden" name="scene" value="post_comment">　
        <input type="hidden" name="ID" value="'.$post["ID"].'">
        <input type="hidden" name="email" value="'.$post["email"].'">
        <input type="hidden" name="number" value="'.$post["number"].'">
        <input type="hidden" name="name" value="'.$post["name"].'">
        <input type="hidden" name="book" value="'.$post["book"].'">
        <input type="hidden" name="tag" value="'.$post["tag"].'">
        <input type="hidden" name="comment" value="'.$post["comment"].'">
        <button type="submit">確定する</button>
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
    $comment = false;
    $tag = "";
    if(isset($post["number"]) && $post["number"] !==""){
        $number = $post["number"];
    }
    if(isset($post["name"]) && $post["name"] !==""){
        $name = $post["name"];
    }    
    if(isset($post["book"]) && $post["book"] !==""){
        $book = $post["book"];
    }    
    if(isset($post["comment"]) && $post["comment"] !==""){
        $comment = $post["comment"];
    }
    if(isset($post["tag"]) && $post["tag"] !==""){
        $tag = explode(",",$post["tag"]);
    }

    echo '
    <br><br>
    <table width="100%" bgcolor="#fafafa">
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
    <td width="50%" align="center">〇タ　グ　</td><td width="50%" align="center">';
    foreach($tag as $t){
        echo $t;
        if($t !== ""){
            echo "<br>";
        }
    }
    echo '
    </td>
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

    echo '<table width="100%"><tr><td align="center">';
    if(!isSetAll($post)){
        echo '<font size="+2" color="#696969">以下の<font color="red">未入力</font>を入力してください。<br>戻るボタンから入力しなおせます。</font>';
    }else{
        echo '<font size="+2" color="#696969">
        入力した内容が正しければ、確定ボタンを押してください。<br>
        再度入力したければ、戻るボタンを押してください。<br></font>
        ';
    }
    echo '</td></tr></table>';

    $next = printPreview($post);
    printButton_EditedPreview($next, $post);

}

?>