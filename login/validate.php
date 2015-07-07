<?php
session_start();
require "../_assets/config.php";
if($_POST){
	$email = trim($_POST['usr_email']);
	$pw = trim($_POST['usr_pass']);
	$pw = md5($pw);

	$q = $pdo->query("SELECT usr_email,usr_name FROM rlvi_ur WHERE usr_email='$email' AND usr_pass='$pw' LIMIT 1");
	$counts = $q->rowCount();
	$data = $q->fetchAll();

	if($counts == 1){
		$_SESSION['relink_usr'] = $data[0]['usr_email'];
		$_SESSION['relink_usr-name'] = $data[0]['usr_name'];
		print "good";
	}else{
		print "bad";
	}
}
?>