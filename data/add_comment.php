<?php

function showForm(String $token){
    echo "<form action=\"added_comment.php?token=".$token."\" method=\"post\">";
    echo '
    <div>
        <label for="number">学籍番号<br></label>
        <input type="text" id="number" name="number">
    </div>
    <div>
        <label for="name">名前<br></label>
        <input type="text" id="name" name="name">
    </div>
    <div>
        <label for="email">メールアドレス<br></label>
        <input type="mail" id="email" name="email">
    </div>
    <div>
        <label for="book">推薦する本の名前<br></label>
        <input type="mail" id="book" name="book">
    </div>  
    <div>
        <label for="tag">タグ<br></label>
        <input type="text" id="tag" name="tag">
    </div>
    <div>
        <label for="comment">推薦内容<br></label>
        <textarea id="comment" name="comment"></textarea>
    </div>
        <input type="submit" value="確定する"><br>
        <select name="page">
    ';

    
    $listdir = scandir(__DIR__."\comment");
    foreach ($listdir as $dir){
        if($dir != "." && $dir != "..") 
            echo "<option value=\"".$dir."\">".$dir."</option>";
          
    }
    
    echo <<< END
        </select>
        </form>
    END;
}

?>

<html>
    <head>
        <meta http-equiv="content-type" charset="utf-8">
    </head>
    <body>

        <?php
        require_once "utils.php";
        
        $token = "";
        if(isset($_GET["token"])){
            $token = $_GET["token"];
        }
        if(get_email($token) != false){
            echo "ようこそ、".get_email($token)."さん。<br>";
            showForm($token);
        }
        
        ?>
    </body>
</html>