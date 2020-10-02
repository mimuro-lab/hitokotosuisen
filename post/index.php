<?php
require_once(".//sendedEmailPage.php");
require_once(".//inputPage.php");
require_once(".//previewPage.php");
require_once(".//postPage.php");

// 変数の取得
//print_r($_GET); echo "<br>";
//print_r($_POST);echo "<br>";
//print_r($_COOKIE);
$scine = "default";
$token = "";
$userMail = "";
$sended = "";
if(isset($_POST["sended"]) && isset($_POST["email"])){
	$scine = "sended_email";
	$userMail = $_POST["email"];
	$sended = $_POST["sended"];
}	
if(isset($_COOKIE["token"])){
	$token = $_COOKIE["token"];
}			
if(isset($_GET["token"])){
	$token = $_GET["token"];
	$scine = "input_comment";
}

// postにscineがセットされていたら、postを優先する。
if(isset($_POST["scine"])){
	$scine = $_POST["scine"];
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
			<td colspan="4" align="center">
            <h1>ひとことすいせん</h1>
            <h2>投稿ページ</h2>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td width="20%" valign="top">
                <?php if($scine == "default"){echo file_get_contents(__DIR__."\\leftPage.php");}?>
			</td>
			<td align="left" width="50%">
				<?php

				switch($scine){
				case "default":
					echo file_get_contents(__DIR__."\\defaultPage.php");
					break;
				case "sended_email":
					main_sendMail($userMail, $sended);
					break;
				case "input_comment":
					main_inputPage($token);
					setcookie("token", $token, time() + 60 * 15);
					break;
				case "preview_comment":
					main_previewPage($_POST);
					break;
				case "post_comment":
					main_postPage($_COOKIE);
					break;
				}

				?>
			</td>
			<td width="25%"></td>
		</tr>
		</table>
	</body>
</html>