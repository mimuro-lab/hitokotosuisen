<?php

require "utils.php";

function divide_content(String $content){
    $divided_content = explode(",", $content);
    return $divided_content;
}

function echo_comment(String $comment){
    return str_replace("?newl?","<br>", $comment);
}

?>

<html>
    <head>
        <meta http-equiv="content-type" charset="utf-8">
    </head>
    <body>

    <?php
    $id = "";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }
    
    $fixed_content = divide_content(check_comment_token($id));
    $number = $fixed_content[3];
    $name = $fixed_content[4];
    $bookAndTag = $fixed_content[6];

    $book = explode(":", $bookAndTag)[0];
    $tag = str_replace($book.":","", $bookAndTag);

    $comment = $fixed_content[7];
    ?>
    <h1>コメントを修正します。<br></h1>
    <h4>現在の入力情報<br></h4>
    <form action="" method="post">
    <table border="0">
    <tr>
        <td valign="top">
        <table border="1">
            <tr>
                <th colspan="2">入力情報</th>
            </tr>
            <tr>
                <td>学籍番号</th><td><?php echo $number;?></td>
            </tr>
            <tr>
                <td>お名前</td><td><?php echo $name;?></td>
            </tr>
            <tr>
                <td>本のタイトル</td><td><?php echo $book;?></td>
            </tr>
            <tr>
                <td>タグ</td><td><?php echo $tag;?></td>
            </tr>
            <tr>
                <td>コメント内容</td><td><?php echo echo_comment($comment);?></td>
            </tr>
        </table>
        </td>
        <td valign="top">→</td>
        <td>
        <table border="1">
            
            <tr>
                <th colspan="2">変更内容</th>
            </tr>
            <tr>
                <td>学籍番号</th><td><input type="text" name="number_fixed"></p></td>
            </tr>
            <tr>
                <td>お名前</td><td><input type="text" name="name_fixed"></p></td>
            </tr>
            <tr>
                <td>本のタイトル</td><td><input type="text" name="book_fixed"></p></td>
            </tr>
            </tr>
            <tr>
                <td>タグ</td><td><input type="text" name="tag_fixed"></p></td>
            </tr>
            <tr>
                <td>コメント内容</td><td><textarea id="comment" name="comment_fixed"></textarea></td>
            </tr>
            
        </table>
        </td>
    </tr>
    </table>
    <p><input type="submit" value="変更内容を反映する"></p>
    </form>
    <?php 
    // 以下、main要素

    $number_fixed = "";
    $name_fixed = "";
    $book_fixed = "";
    $tag_fixed = "";
    $comment_fixed = "";
    if(isset($_POST["number_fixed"])){
        $number_fixed = $_POST["number_fixed"];
    }
    if(isset($_POST["name_fixed"])){
        $name_fixed = $_POST["name_fixed"];
    }
    if(isset($_POST["book_fixed"])){
        $book_fixed = $_POST["book_fixed"];
    }
    if(isset($_POST["comment_fixed"])){
        $comment_fixed = $_POST["comment_fixed"];
    }
    if(isset($_POST["tag_fixed"])){
        $tag_fixed = $_POST["tag_fixed"];
        // タグのエスケープ処理
        $tag_fixed = str_replace(",", "?cma?", $tag_fixed);
        $tag_fixed = str_replace(":", "?cln?", $tag_fixed);
        echo $tag_fixed;
    }
    fix_comment($id, $number_fixed, $name_fixed, $book_fixed, $tag_fixed, $comment_fixed);

    ?>
    </body>
</html>