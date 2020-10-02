<?php
require_once(".//sendedEmailPage.php");

function main_sendMail($userMail, $sended)
{
	if(sendPostMail($userMail)){
		echo $userMail.'宛てに応募用メールを送信しました。メールの内容をご確認ください。<br><br>';
	}else{
		echo sendPostMail($userMail);
	}

	// メールを再送信する。
	if($sended == "resend"){
		sendmailToUser($userMail);
		echo "再送信しました。<br>";
	}

	echo '
	<br>
	<form action="" method="post">
	<input type="hidden" name="sended" value="resend">
	<input type="hidden" name="email" value="'.$userMail.'">
	<button type="submit">メールを再送信する</button>
	</form>
	';
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
                <?php echo file_get_contents(__DIR__."\\leftPage.php");?>
			</td>
			<td align="center" width="50%">
				<?php
				print_r($_GET);
				print_r($_POST);
				echo "<br><br>";
				$scene = "default";
				$token = "";
				$userMail = "";
				$sended = "";

				if(isset($_POST["sended"]) && isset($_POST["email"])){
					$scene = "sended_email";
					$userMail = $_POST["email"];
					$sended = $_POST["sended"];
				}				
				if(isset($_GET["token"])){
					$token = $_GET["token"];
					$scene = "input_comment";
				}

				switch($scene){
				case "default":
					echo file_get_contents(__DIR__."\\defaultPage.php");
					break;
				case "sended_email":
					main_sendMail($userMail, $sended);
					break;
				case "input_comment":
					echo "入力画面です。";
					break;
				}

				?>
			</td>
			<td width="25%"></td>
		</tr>
		</table>
	</body>
</html>