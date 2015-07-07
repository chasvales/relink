<?php
	require "../_assets/config.php";
	function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
	{
	    $str = '';
	    $count = strlen($charset);
	    while ($length--) {
	        $str .= $charset[mt_rand(0, $count-1)];
	    }
	    return $str;
	}

	$usrID = randString(15);
	$usr_email = trim($_POST['usr_email']);
	$usr_pass = md5($_POST['usr_pass']);
	$usr_name = $_POST['usr_name'];

	$sql = "INSERT INTO rlvi_ur (usr_email,usr_pass,usr_name,usr_id) VALUES (:email,:paw,:flname,:uid)";
	$stmt = $pdo->prepare($sql);
	$params = array(
		':email' => $usr_email,
		':paw' => $usr_pass,
		':flname' => $usr_name,
		':uid' => $usrID
		);
	$stmt->execute($params) or die( print_r($stmt->errorInfo(), true));
?>