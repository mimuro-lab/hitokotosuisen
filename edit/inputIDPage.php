<?php

function printButton($canFind, $comment, $ID)
{
    echo'
    <table width="100%">
    <tr>
        <td align="center">
        <form action="" method="post"><input type="hidden" name="scene" value="default"><button type="submit">戻る</button></form>
        </td>
        <td align="right">
    ';
    if($canFind){
        echo '
            <form action="" method="post">
            <input type="hidden" name="scene" value="edit_comment">
            <input type="hidden" name="ID" value="'.$ID.'">
            <input type="hidden" name="number" value="'.$comment["number"].'">
            <input type="hidden" name="name" value="'.$comment["name"].'">
            <input type="hidden" name="email" value="'.$comment["email"].'">
            <input type="hidden" name="tag" value="';
        
        foreach($comment["tag"] as $tag){
            echo $tag.":";
        }

        echo '">
            <input type="hidden" name="comment" value="'.$comment["comment"].'">
            <button type="submit">このコメントを編集する</button>
            </form>
        ';
    }
    echo '            
    </td>
    </tr>
    </table>
    ';
}

function printMessage($canFind)
{
    if($canFind){
        echo '
        コメントIDに対する内容が見つかりました。<br>
        ';
    }else{
        echo '
        無効なコメントIDが入力されました。<br>
        正しいIDを入力して下さい。<br>
        ';  
    }
}

function printPreviewFromID($comment)
{
    echo '
    <table width="100%">
    <tr><td colspan="2"><hr></td></tr>
    <tr>
        <td width="50%" align="center">〇学籍番号</td><td width="50%" align="center">'.$comment["number"].'</td>
    </tr>
    <tr>
        <td width="50%" align="center">〇名　前　</td><td width="50%" align="center">'.$comment["name"].'</td>
    </tr>
    <tr>
        <td width="50%" align="center">〇推薦する本の名前</td><td width="50%" align="center">'.$comment["tag"][0].'</td>
    </tr>
    <tr>
        <td width="50%" align="center">〇タグ</td><td width="50%" align="center">';
    
    // タグを全て表示する
    if(count($comment["tag"])===1){
        echo "なし";
    }
    for($i = 1; $i < count($comment["tag"]); $i++){
        echo $comment["tag"][$i]."<br>";
    }
    
    echo '</td>
    </tr>
    <tr><td colspan="2"><hr></td></tr>
    <tr>
        <td align="center" colspan="2">〇推薦内容</td>
    </tr>
    <tr>
        <td colspan="2">'.$comment["comment"].'</td>
    </tr>
    <tr><td colspan="2"><hr></td></tr>
    </table>
    ';
}

// コメント内容に関するidとtokenのチェックを行う。
// idとtokenが入力され、両方マッチするものがある場合は、その内容を返す。（idとtokenがつながったものを入力）
// マッチするものがなかった場合、falseを返す。
function get_comment_matched(String $ID_and_token){
    
    // 3つの要素（index, token）で構成されていなかったらfalseを返す
    if(count(explode(":",$ID_and_token)) != 2){
        return false;
    }

    $folderIND = explode(":",$ID_and_token)[0];
    $token_comment = explode(":",$ID_and_token)[1];

    $pathToInfo = __DIR__."\\..\\data\\posted\\".$folderIND."\\info.txt";
    if(!file_exists($pathToInfo)){
        return false;
    }
    
    $savedToken = file_get_contents($pathToInfo);
    $savedToken = explode(",", $savedToken)[0];
    echo "saved:".$savedToken."<br>".$token_comment;
    
    if($savedToken === $token_comment){
        $retContent = array();
        $infoContent = explode(",", file_get_contents($pathToInfo));
        $retContent["name"] = $infoContent[1];
        $retContent["number"] = $infoContent[2];
        $retContent["email"] = $infoContent[3];
        $pathToTag = __DIR__."\\..\\data\\posted\\".$folderIND."\\search_kwd.txt";
        $retContent["tag"] = explode(",", file_get_contents($pathToTag));
        $pathToView = __DIR__."\\..\\data\\posted\\".$folderIND."\\view.txt";
        $retContent["comment"] = explode(",", file_get_contents($pathToView))[2];
        return $retContent;
    }
    
    return false;
}

function main_inputID($ID)
{
    $comment = get_comment_matched($ID);
    $canFind = false;
    if($comment !== false){
        $canFind = true;
    }

    printMessage($canFind);

    if($canFind){
        printPreviewFromID($comment);
    }

    printButton($canFind, $comment, $ID);

}

?>