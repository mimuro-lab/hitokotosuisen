<?php

function printButton($canFind)
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
            <form action="" method="post"><input type="hidden" name="scene" value="default"><button type="submit">このコメントを編集する</button></form>
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
        <td align="center">〇学籍番号</td><td align="center">'.$comment["number"].'</td>
    </tr>
    <tr>
        <td align="center">〇名　前　</td><td align="center">'.$comment["name"].'</td>
    </tr>
    <tr>
        <td align="center">〇推薦する本の名前</td><td align="center">'.$comment["tag"][0].'</td>
    </tr>
    <tr>
        <td align="center">〇タグ</td><td align="center">';
    
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
        <td>'.$comment["comment"].'</td>
    </tr>
    <tr><td colspan="2"><hr></td></tr>
    </table>
    ';
}

// コメント内容に関するidとtokenのチェックを行う。
// idとtokenが入力され、両方マッチするものがある場合は、その内容を返す。（idとtokenがつながったものを入力）
// マッチするものがなかった場合、falseを返す。
function get_comment_matched(String $ID_and_token){
    
    // 3つの要素（date,lineID, token）で構成されていなかったらfalseを返す
    if(count(explode(":",$ID_and_token)) != 3){
        return false;
    }

    $date = explode(":",$ID_and_token)[0];
    $lineID = (int)explode(":",$ID_and_token)[1];
    $token = explode(":",$ID_and_token)[2];

    $pathToCSV = __DIR__."\\..\\data\\comment\\".$date.".csv";
    if(!file_exists($pathToCSV)){
        return false;
    }

    $fp = fopen($pathToCSV, "r");

    // ファイルの中身を格納する変数
    $contentOfText = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $contentOfText = fgets($fp);
        if($contentOfText == ""){
            break;
        }
        $nowID = (int)explode(",", $contentOfText)[0];
        $nowToken = explode(",", $contentOfText)[1];
        if($lineID == $nowID && $nowToken == $token){
            $retList = array();
            $retList["index"] = explode(",", $contentOfText)[0];
            $retList["date"] = explode(",", $contentOfText)[2];
            $retList["number"] = explode(",", $contentOfText)[3];
            $retList["name"] = explode(",", $contentOfText)[4];
            $retList["email"] = explode(",", $contentOfText)[5];
            $retList["tag"] = explode(":",explode(",", $contentOfText)[6]);
            $retList["comment"] = explode(",", $contentOfText)[7];
            return $retList;
        }
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

    printButton($canFind);

}

?>