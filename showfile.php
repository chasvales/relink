<?php
	require '_assets/config.php';
	$string = file_get_contents($_SERVER['DOCUMENT_ROOT'].$hta_dir.'.htaccess');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>RElink View document</title>
</head>
<body>
	<?php
		if(!$string){
			print "Your .htaccess file is empty.";
		}else{
			print "<pre>".$string."</pre>";
		}
	?>
</body>
</html>