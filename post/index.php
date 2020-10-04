<?php
require_once(".//sendedEmailPage.php");
require_once(".//inputPage.php");
require_once(".//previewPage.php");
require_once(".//postPage.php");
require_once(".//quitPage.php");

// 変数の取得
print_r($_GET); echo "<br>";
print_r($_POST); echo "<br>";
print_r($_COOKIE); echo "<br>";
$scene = "default";
$token = "";
$userMail = "";

if(isset($_GET["token"])){
	$token = $_GET["token"];
	$scene = "input_comment";
}else if(isset($_POST["token"])){
	$token = $_POST["token"];
}

// postにscineがセットされていたら、postを優先する。
if(isset($_POST["scene"])){
	$scene = $_POST["scene"];
}


?>

<!DOCTYPE html>
<html>
  <head>
    <title>投稿ページ</title>
    <meta charset="utf-8">
  </head>
  <body>  
		<table border="0" width="100%">
		<tr>
			<td colspan="5" align="center">
            <h1>ひとことすいせん</h1>
            <h2>投稿ページ</h2>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td width="20%" valign="top">
				<?php
				if($scene == "default"){
					echo file_get_contents(__DIR__."\\leftPage.php");
				}?>
			</td>
			<td align="left" width="50%">
				<?php

				switch($scene){
				case "default":
					echo file_get_contents(__DIR__."\\defaultPage.php");
					break;
				case "sended_email":
					main_sendMail($_POST);
					break;
				case "input_comment":
					main_inputPage($token);
					setcookie("token", $token, time() + 60 * 15);
					break;
				case "preview_comment":
					main_previewPage($_POST);
					break;
				case "post_comment":
					main_postPage($_POST);
					break;
				case "quit_post":
					main_quitPage($_POST);
					break;
				}

				?>
			</td>
			<td width="5%"></td>
			<td width="20%" valign="top">
			<br>
			<?php
			if($scene == "sended_email" || $scene == "input_comment" || $scene == "preview_comment"){
				require_once(".//rightPage.php");
			}?>
			</td>
		</tr>
		</table>
	</body>
</html>