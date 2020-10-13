<?php
require_once(__DIR__."\\utils.php");
print_r($_POST);

if(!isOk($_POST["username"], $_POST["password"])){
    echo '一致しないユーザー名とパスワードが入力されました。';
    exit();
}

$scene = "default";

function main($_scene){
    switch($_scene){
        case "default":
            
        break;
    }
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
      <td align="center" colspan="2">管理者用画面</td>
	</tr>
	<tr><td><br><br></td></tr>
	
	<table width="100%">

	<?php main($scene)?>
	
	</table> 
  </body>
</html>