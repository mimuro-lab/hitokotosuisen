<html>
    <head>
        <meta http-equiv="content-type" charset="utf-8">
    </head>

    <form action="added_comment.php" method="post">
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
            <label for="comment">推薦内容<br></label>
            <textarea id="comment" name="comment"></textarea>
        </div>
        <input type="submit" value="確定する"><br>
        <select name="page">
        <?php
        $listdir = scandir(__DIR__."\comment");
        foreach ($listdir as $dir){
            if($dir != "." && $dir != "..") 
                echo "<option value=\"".$dir."\">".$dir."</option>";
            
        }

        ?>
        </select>
        <?php echo $_GET['token'];?>

    </form>
</html>