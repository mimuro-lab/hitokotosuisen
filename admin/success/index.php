﻿<?php
require_once(".//..//utils.php");
require_once(".//utils.php");
require_once(".//allView.php");
require_once(".//view.php");
print_r($_GET); echo "<br>";
print_r($_POST); echo "<br>";
print_r($_COOKIE); echo "<br>";

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

$scene = "default";
if(isset($_GET["scene"])){
    $scene = $_GET["scene"];
}

function printDefault()
{
    echo '
    <table width="100%">
    <tr><td align="center">
    <a href="./?scene=allView">投稿内容の管理</a>
    </td></tr>
    </table>
    ';
}

function main(string $_scene)
{
    echo '
    <table width="100%">
    <tr>
    <td width="25%"></td>
    <td width="50%">
    ';
    switch($_scene){
        case "default":
            printDefault();
        break;
        case "allView":
            main_allView();
        break;
        case "view":
            main_view();
        break;
    }
    echo '
    </td>
    <td witdth="25%"></td>
    </tr>
    </table>
    ';
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>管理者用画面</title>
    <meta charset="utf-8">
  </head>
  <body> 
  <table width="100%" border="0">
	<tr><td><br><br></td></tr>
  <tr>
      <td align="center" colspan="2">管理者用画面(認証成功)</td>
	</tr>
	<tr><td><br><br></td></tr>
	
	<table width="100%">

	<?php main($scene)?>
	
	</table> 
  </body>
</html>