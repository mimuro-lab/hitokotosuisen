<?php

function printButton()
{
    //入力画面へ戻るボタンと、確定ボタン
    echo '
    <form action="" method="post">
    <input type="hidden" name="scine" value="input_comment">

    <table width="100%">
    <tr>
        <td align="left"><button type="submit">　戻る　</button></td>
        <td align="right"><button type="submit">確定する</button></td>
    <tr>
    </table>
    </form>
    ';

}

function printPreview($post)
{
    $post["comment"] = str_replace("\r\n", "<br>", $post["comment"]);
    echo '
    <table width="100%">
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td align="center" width="50%">〇学籍番号</td><td align="center" width="50%">'.$post["number"].'</td>
    </tr>
    <tr>
        <td align="center" width="50%">〇名　前　</td><td align="center" width="50%">'.$post["name"].'</td>
    </tr>
    <tr>
        <td align="center" width="50%">〇推薦する本の名前</td><td align="center" width="50%">'.$post["book"].'</td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td align="center" colspan="2">〇推薦内容</td>
    </tr>
    <tr>
        <td>'.$post["comment"].'</td>
    </tr>
    <tr>
        <td align="center" colspan="2"><hr></td>
    </tr>
    </table>

    ';
}

function main_previewPage($post){

    setcookie("email", $post["email"], time() + 60 * 15);
    setcookie("number", $post["number"], time() + 60 * 15);
    setcookie("name", $post["name"], time() + 60 * 15);
    setcookie("book", $post["book"], time() + 60 * 15);
    setcookie("tag", $post["tag"], time() + 60 * 15);
    setcookie("comment", $post["comment"], time() + 60 * 15);

    printPreview($post);

    printButton();

}

?>