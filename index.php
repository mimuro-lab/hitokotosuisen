<?php
require_once(".//view//defaultPage.php")
?>

<!DOCTYPE html>
<html>
  <head>
    <title>トップページ</title>
    <meta charset="utf-8">
  </head>
  <body>  
		<table border="0" width="100%">
		<tr>
			<td colspan="4" align="center">
			<img src="./title_1.gif"><br>
            <font size="+2" color="#000000">トップページ</font>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td width="20%" valign="top">
				<br><br>
				<a href="./view">閲覧ページへ</a>
				<br><br>
				<a href="./post">投稿ページへ</a>
				<br><br>
				<a href="./edit">編集ページへ</a>
			</td>
			<td align="center" width="50%">
				<table border="0"  bordercolor="#adff2f" width="100%">
				<tr><td>
					<?php 
					
					$status = explode(",", file_get_contents("./data/siteStatus.txt"))[0];
					if($status === "public"){
						viewDefaultComment(7, 3);
					}
					
					?>	
				</td></tr>
				</table>
			</td>
			<td width="25%"></td>
		</tr>
		</table>
	</body>
</html>