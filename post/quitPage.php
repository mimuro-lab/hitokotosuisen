<?php

function main_quitPage($post)
{
    
    $token = $post["token"]."\r\n";

    //　なんかできない。2020/10/04
    if(delete_token($token)===false){
        echo "failed";
    }else{
        echo '
        <table border="1" width="100%">
        <tr><td align="center">
        投稿を中断しました。<br>
        <a href="http://localhost:8080">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
    }



}

?>