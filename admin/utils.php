<?php
function isOkUserInfo($username, $password)
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

function isOkToken($token)
{
    $true = file_get_contents(__DIR__."\\..\\data\\token_admin.csv");
	$true = preg_replace('/[^0-9a-zA-Z]/', '', $true);
	$getToken = preg_replace('/[^0-9a-zA-Z]/', '', $token);
	if($getToken === $true){
        return true;
	}else{
        return false;
	}
}
?>