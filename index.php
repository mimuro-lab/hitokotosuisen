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
            <h1>ひとことすいせん</h1>
            <h2>トップページ</h2>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td width="20%" valign="top">
				<br><br>
				<a href="./view">閲覧ページへ</a>
				<br><br>
				<a href="./post">投稿ページへ</a>
			</td>
			<td align="center" width="50%">最近の投稿<?php viewDefaultComment(7, 5);?></td>
			<td width="25%"></td>
		</tr>
		</table>
	</body>
</html>