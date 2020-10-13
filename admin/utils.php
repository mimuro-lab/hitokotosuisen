<?php
function isOk($username, $password)
{
	$pathToInfo = __DIR__.'\\..\\data\\admin_userInfo.txt';
	$fp = fopen($pathToInfo, "r");
	
	
	while($infoLine = fgets($fp)){
		//　BOM削除
		$infoLine = preg_replace('/^\xEF\xBB\xBF/', '', $infoLine);
		
		if(count(explode(",", $infoLine)) != 3){
			continue;
		}
		$TrueUsername = explode(",", $infoLine)[0];
		$TruePassword = explode(",", $infoLine)[1];
		
		if($username === $TrueUsername && $password === $TruePassword){
			return True;
		}
	}
	return False;
}
?>