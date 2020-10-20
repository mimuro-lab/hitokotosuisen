<?php
require_once(".//..//utils.php");

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

function getPostedAll()
{
    $pathToPostdFolder = __DIR__."//..//..//data//posted";
    $listOfFolder = scandir($pathToPostdFolder);
    $max = count($listOfFolder);
    $listTmp = array_fill(0, $max, "none");
    foreach($listOfFolder as $path){
      if($path != "." && $path != ".."){
        $listTmp[(int)$path] = $path;
      } 
    }
    $listOfFolder = $listTmp;
    
    $listOfContent = array();
    foreach($listOfFolder as $path){
        if($path === "none"){
            continue;
        }    
        $pathToDir = $pathToPostdFolder."\\".$path;
        $oneContent = array();
        $oneContent["info"] = explode(",", file_get_contents($pathToDir."\\info.txt"));
        $oneContent["index"] = file_get_contents($pathToDir."\\index.txt");
        $oneContent["view"] = explode(",", file_get_contents($pathToDir."\\view.txt"));
        $oneContent["serch_kwd"] = explode(",", file_get_contents($pathToDir."\\search_kwd.txt"));
        $oneContent["serch_kwd_fixed"] = explode(",", file_get_contents($pathToDir."\\search_kwd_fixed.txt"));
        $oneContent["count"] = explode(",", file_get_contents($pathToDir."\\count.txt"));
        array_push($listOfContent, $oneContent);
    }
    return $listOfContent;
}

function getPostedFromIndex(int $index)
{
    $pathToPostdFolder = __DIR__."//..//..//data//posted";
    $listOfFolder = scandir($pathToPostdFolder);
    $max = count($listOfFolder);
    $listTmp = array_fill(0, $max, "none");
    foreach($listOfFolder as $path){
      if($path != "." && $path != ".."){
        $listTmp[(int)$path] = $path;
      } 
    }
    $listOfFolder = $listTmp;
    $pathToDir = $pathToPostdFolder;
    $find = false;
    foreach($listOfFolder as $path){
        if($path === "none"){
            continue;
        }    
        if($index == (int)$path){
            $pathToDir .= "\\".$path;
            $find = true;
        }
    }
    if(!$find){
        return False;
    }
    
    $oneContent = array();
    $oneContent["info"] = explode(",", file_get_contents($pathToDir."\\info.txt"));
    $oneContent["index"] = file_get_contents($pathToDir."\\index.txt");
    $oneContent["view"] = explode(",", file_get_contents($pathToDir."\\view.txt"));
    $oneContent["serch_kwd"] = explode(",", file_get_contents($pathToDir."\\search_kwd.txt"));
    $oneContent["serch_kwd_fixed"] = explode(",", file_get_contents($pathToDir."\\search_kwd_fixed.txt"));
    $oneContent["count"] = explode(",", file_get_contents($pathToDir."\\count.txt"));

    return $oneContent;

}

function printContentPre($content, $maxContent, $starNumber)
{

    $trimedContent = array_slice($content, $starNumber, $maxContent);
    echo '
    <table width="100%">
    <tr>
    <td width="30%">ユーザー情報</td>
    <td width="40%">投稿情報</td>
    <td width="30%">ステータス</td>
    </tr>
    </table>';
    foreach($trimedContent as $content){
        $status = "none";
        if($content["info"][6] == "public"){
            $status = '<font color="#78FF94">公開状態</font>';
        }else if($content["info"][6] == "private"){
            $status = '<font color="#FF367F">非公開状態</font>';
        }else if($content["info"][6] == "wait"){
            $status = '<font color="#C0C0C0">認証待ち状態</font>';
        }
        echo '
        <table width="100%" border="0">
        <tr><td colspan="3"><hr></td></tr>
        <tr>
        <td width="30%" align="left">
        INDEX:'.$content["index"].'<br>
        '.$content["info"][2].'<br>
        '.$content["info"][1].'<br>
        </td>
        <td width="40%">
        Title:'.$content["view"][0].'<br>
        '.$content["info"][4].'<br>
        <a style="text-decoration: none;" href="./?scene=view&index='.$content["index"].'">
        <font color="#2C7CFF">詳細を設定する</font></a>
        </td>
        <td width="30%">
        '.$status.'
        </td>
        </tr>
        </table>
        ';
    }
    echo '<table width="100%"><tr><td><hr></td></tr></table>';

}

?>
