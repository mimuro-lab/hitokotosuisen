<?php

function savePostToCookie($post)
{
    setcookie("ID", $post["ID"], time() + 60 * 20);
    setcookie("number", $post["number"], time() + 60 * 20);
    setcookie("name", $post["name"], time() + 60 * 20);
    setcookie("tag", $post["tag"], time() + 60 * 20);
    setcookie("comment", $post["comment"], time() + 60 * 20);
}

function printEditFormBack($post)
{
    $comment = str_replace("<br>", "", $post["comment"]);
    echo '
    <form action="." method="post">
    <table width="100%">
    <tr>
    <td>〇学籍番号　　　　　'.$post["number"].'</td>
    </tr>
    <tr>
    <td>〇名　前　　　　　　'.$post["name"].'</td>
    </tr>
    <tr>
    <td>〇タ　グ　　　　　　<input type="text" name="tag" value="'.$post["tag"].'"></input>
    </td>
    </tr>
    <tr>
    <td>
    〇推薦する本の名前　<input type="text" name="book" value="'.$post["book"].'"></input>
    </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td colspan="2">〇推薦内容</td></tr>
    <tr><td colspan="2">
    <textarea name="comment"  rows="20" cols="70">'.$post["comment"].'</textarea>
    </td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="プレビュー画面へ行く"></td></tr>
    </table>
    <input type="hidden" name="ID" value="'.$post["ID"].'">
    <input type="hidden" name="email" value="'.$post["email"].'">
    <input type="hidden" name="name" value="'.$post["name"].'">
    <input type="hidden" name="number" value="'.$comment.'">
    <input type="hidden" name="scene" value="preview_comment">
    </form>
    ';
}

function printEditForm($post)
{
    
    $book = explode(":",$post["tag"])[0];
    $tag = array();
    
    for($i = 1; $i < count(explode(":",$post["tag"]));$i++){
        array_push($tag, explode(":",$post["tag"])[$i]);
    }
    
    echo '
    <form action="." method="post">
    <table width="100%">
    <tr>
    <td>〇学籍番号　　　　　'.$post["number"].'</td>
    </tr>
    <tr>
    <td>〇名　前　　　　　　'.$post["name"].'</td>
    </tr>
    <tr>
    <td>〇タ　グ　　　　　　<input type="text" name="tag" value="';
    foreach($tag as $t){
        echo $t;
        if($t !== ""){
            echo ",";
        }
    }
    echo '"></input>
    </td>
    </tr>
    <tr>
    <td>
    〇推薦する本の名前　<input type="text" name="book" value="'.$book.'"></input>
    </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td colspan="2">〇推薦内容</td></tr>
    <tr><td colspan="2">
    <textarea name="comment"  rows="20" cols="70">'.$post["comment"].'</textarea>
    </td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="プレビュー画面へ行く"></td></tr>
    </table>
    <input type="hidden" name="ID" value="'.$post["ID"].'">
    <input type="hidden" name="email" value="'.$post["email"].'">
    <input type="hidden" name="name" value="'.$post["name"].'">
    <input type="hidden" name="number" value="'.$post["number"].'">
    <input type="hidden" name="scene" value="preview_comment">
    </form>
    ';
}

function main_editPage($post)
{
    //savePostToCookie($post);

    if(!isset($_POST["back"])){
        printEditForm($post);
    }else{
        printEditFormBack($post);
    }

}

?>