<?php
session_start();
if(!isset($_SESSION['relink_usr-name'])){
	print "invalid";
}else{
	print "valid";
}
?>