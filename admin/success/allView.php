<?php

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

require_once(".//utils.php");
require_once(".//utils.php");

function printContent_allView($nowPage)
{
    $contentAll = getPostedAll();
    $allCount = count($contentAll);
    $viewsPerPage = 10;
    $maxPage = ceil($allCount / $viewsPerPage);
    echo '<table width="100%"><tr><td align="center">('.$nowPage.'/'.$maxPage.')<br><br></td></tr></table>';
    printContentPre($contentAll, $viewsPerPage, ($nowPage-1) * $viewsPerPage);
    echo '<table width="100%"><tr><td align="center">';
    for($i = 1; $i <= $maxPage; $i++){
        echo '<a href="./?scene=allView&page='.$i.'">'.$i.'</a>&nbsp;';
    }
    echo '</td></tr></table>';
    return $maxPage;
}

function printInputIndex(){
    echo '
    <form action="." method="get">
    <p>
        INDEXから探す<br>
        <input type="number" min="0" name="index" size="20">
        <input type="hidden" name="scene" value="view">
        <input type="submit" value="検索">
    </p>
    </form>
    ';
}

function main_allView()
{
    echo '
    <table width="100%"><tr><td align="center">一覧</td></tr></table>';
    $page = 1;
    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }
    $maxPage = printContent_allView($page);
    // ボタンを表示する
    $prePage = $page <= 1 ? 1 : $page - 1;
    $nextPage = $page >= $maxPage ? $maxPage : $page + 1;
    echo '
    <br>
    <table width="100%"><tr><td align="center">
    <form action="" method="get">
    <input type="hidden" name="scene" value="allView">
    <button type="submit" name="page" value="'.$prePage.'">前へ</button>
    <button type="submit" name="page" value="'.$nextPage.'">次へ</button>
    </fotm>
    </td></tr></table>';
}

?>