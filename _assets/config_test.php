<?php
$config_file = file_get_contents('config.php');
if($config_file == ""){
	print "empty";
}
?>