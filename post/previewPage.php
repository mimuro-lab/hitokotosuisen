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

function printButton($next)
{
    //入力画面へ戻るボタンと、確定ボタン
    echo '
    <table width="100%">
    <tr>
    
    <td align="left">
    <form action="" method="post">
        <input type="hidden" name="scine" value="input_comment">
        <button type="submit">　戻る　</button>
    </form>
    </td>

    <td align="right">
    ';
    if($next){
    echo '
    <form action="" method="post">
        <input type="hidden" name="scine" value="post_comment">　
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

    echo '
    <table width="100%">
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
    <tr>
        <td align="center" width="50%">〇名　前　</td><td align="center" width="50%">';

    if($name !== false){
        echo $name;
    }else{
        echo '<font color="red">未入力</font>';
    }

    echo '</td>
    </tr>
    <tr>
        <td align="center" width="50%">〇推薦する本の名前</td><td align="center" width="50%">';

    if($book !== false){
        echo $book;
    }else{
        echo '<font color="red">未入力</font>';
    }

    echo '</td>
    </tr>
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
        echo '以下の<font color="red">未入力</font>を入力してください。<br>戻るボタンから入力しなおせます。';
    }else{
        echo '
        入力した内容が正しければ、確定ボタンを押してください。<br>
        再度入力したければ、戻るボタンを押してください。<br>
        ';
    }

    $next = printPreview($post);
    printButton($next);

}

?>