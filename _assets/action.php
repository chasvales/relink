<?php
	header("Access-Control-Allow-Origin: *");
	require 'functions.php';
	$myData = json_decode(file_get_contents("php://input"),true);
	
	$var1 = $myData['param1'];
	$var2 = $myData['param2'];
	$var3 = $myData['param3'];
	$var4 = $myData['param4'];

	switch($var1){
		case "compilelinks":
		$lnk->complieLinks();
		break;
		case "addlinks":
		$lnk->addLink($var2,$var3);
		break;
		case "getlinks":
		$lnk->getLinks();
		break;
		case "removelinks":
		$lnk->removeLinks($var2);
		break;
		case "getsettings":
		$lnk->getsettings();
		break;
		case "updatesettings":
		$lnk->updatesettings($var2,$var3,$var4);
		break;
		case "movehtacess":
		$lnk->movehtacess();
		break;
		case "logout":
		$lnk->logout();
		break;
	}
?>