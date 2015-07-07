<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php 
		$fp = fopen('../_assets/config.php', 'w');
		$pdo = "$pdo";
		$db_host = "$db_host";
		$db_name = "$db_name";
		$db_user = "$db_user";
		$db_pass = "$db_pass";
		if($_POST){
			fwrite($fp, "<?php \n\r");
			foreach($_POST as $key => $value) {
				switch($key){
					case "hta_st":
					if($value == ""){
						$value = "# BEGIN RlviReWrite";
					}
					break;
				}
				fwrite($fp, "$".$key." = '" . $value . "'; \n\r");	
			}
			fwrite($fp, "\$pdo = new PDO('mysql:host='.\$db_host.';dbname='.\$db_name,\$db_user,\$db_pass);");
			fwrite($fp, "\n\r ?>");
			fclose($fp);
			print "config complete";
		}
	?>
</body>
</html>