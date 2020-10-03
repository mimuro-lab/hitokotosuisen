<?php

require_once(".//inputIDPage.php");

// 変数の取得
if(!isset($_POST["scene"])){
	$_POST["scene"] = "default";
}

print_r($_GET); echo "<br>";
print_r($_POST);echo "<br>";
print_r($_COOKIE);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>編集ページ</title>
    <meta charset="utf-8">
  </head>
  <body>  
		<table border="0" width="100%">
		<tr>
			<td colspan="4" align="center">
            <h1>ひとことすいせん</h1>
            <h2>編集ページ</h2>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td width="20%" valign="top">
                <?php if($_POST["scene"] == "default" || $_POST["scene"] == "input_ID"){echo file_get_contents(__DIR__."\\leftPage.php");}?>
			</td>
			<td align="left" width="50%">
				<?php

				switch($_POST["scene"]){
				case "default":
					echo file_get_contents(__DIR__."\\defaultPage.php");
					break;
				case "input_ID":
					main_inputID($_POST["ID"]);
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