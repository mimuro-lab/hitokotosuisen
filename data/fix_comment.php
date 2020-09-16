<?php

require "utils.php";

function divide_content(String $content){
    $divided_content = explode(",", $content);
    return $divided_content;
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
    $book = $fixed_content[6];
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
                <td>コメント内容</td><td><?php echo $comment;?></td>
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
                <td>学籍番号</th><td><input type="text" name="number"></p></td>
            </tr>
            <tr>
                <td>お名前</td><td><input type="text" name="name"></p></td>
            </tr>
            <tr>
                <td>本のタイトル</td><td><input type="text" name="book"></p></td>
            </tr>
            <tr>
                <td>コメント内容</td><td><textarea id="comment" name="comment"></textarea></td>
            </tr>
            
        </table>
        </td>
    </tr>
    </table>
    <p><input type="submit" value="変更内容を反映する"></p>
    </form>
    <?php 
    echo $_POST["number"];
    ?>
    </body>
</html>