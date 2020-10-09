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
    <table width="100%">
    <tr><td align="center"><font size="+2" color="#696969">編集内容を入力してください</font><br>
    ※名前と学籍番号は変更できません。</td></tr></table>
    <form action="." method="post">
    <table width="100%" bgcolor="#fafafa">
    <tr>
    <td width="50%" align="center">〇学籍番号</td><td width="50%" align="center">'.$post["number"].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇名　前</td><td width="50%" align="center">'.$post["name"].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇タ　グ　</td><td width="50%" align="center"><input type="text" size="45" name="tag" value="'.$post["tag"].'"></input>
    </td>
    <tr><td><br></td></tr>
    </tr>
    <tr>
    <td width="50%" align="center">〇推薦する本の名前</td>
    <td  width="50%" align="center"><input type="text" name="book" value="'.$post["book"].'"></input></td>
    </tr>
    <tr><td><br></td></tr>
    <tr><td><br></td></tr>
    <tr><td colspan="2" align="center">〇推薦内容</td></tr>
    <tr><td colspan="2" align="center">
    <textarea name="comment"  rows="20" cols="80">'.$post["comment"].'</textarea>
    </td></tr>
    <tr><td colspan="2" align="center"><br><input type="submit" value="プレビュー画面へ行く"></td></tr>
    </table>
    <input type="hidden" name="ID" value="'.$post["ID"].'">
    <input type="hidden" name="email" value="'.$post["email"].'">
    <input type="hidden" name="name" value="'.$post["name"].'">
    <input type="hidden" name="number" value="'.$post["number"].'">
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
    <table width="100%" bgcolor="#fafafa">
    <tr>
    <td width="50%" align="center">〇学籍番号</td><td width="50%" align="center">'.$post["number"].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇名　前　</td><td width="50%" align="center">'.$post["name"].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇タ　グ　</td><td width="50%" align="center"><input type="text" size="45" name="tag" value="';
    foreach($tag as $t){
        echo $t;
        if($t !== ""){
            echo ",";
        }
    }
    echo '"></input>
    </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇推薦する本の名前</td>
    <td width="50%" align="center"><input type="text" name="book" value="'.$book.'"></input></td>
    </td>
    <tr><td><br></td></tr>
    <tr><td><br></td></tr>
    </tr>
    
    <tr><td colspan="2" align="center">〇推薦内容</td></tr>
    <tr><td colspan="2" align="center">
    <textarea name="comment" rows="20" cols="80">'.$post["comment"].'</textarea>
    </td></tr>
    <tr><td colspan="2" align="center"><br><input type="submit" value="プレビュー画面へ行く"></td></tr>
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