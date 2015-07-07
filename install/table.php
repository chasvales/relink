<?php
require "../_assets/config.php";
$sql = "CREATE TABLE IF NOT EXISTS rlvi_rd (
			  org_link varchar(120) NOT NULL,
			  rdr_link varchar(150) NOT NULL,
			  usr varchar(60) NOT NULL,
			  timeUp varchar(22) NOT NULL,
			  autoInc int(11) NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY (autoInc),
			  UNIQUE KEY org_link (org_link)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;";
			$stmt = $pdo->prepare($sql);
			$stmt->execute() or die( print_r($stmt->errorInfo(), true));
		//$pdo = null;
$sqlt = "CREATE TABLE IF NOT EXISTS rlvi_ur (
	  usr_email varchar(120) NOT NULL,
	  usr_pass varchar(200) NOT NULL,
	  usr_name varchar(60) NOT NULL,
	  usr_log varchar(25) NOT NULL,
	  usr_id varchar(30) NOT NULL,
	   PRIMARY KEY (usr_id),
  UNIQUE KEY usr_email (usr_email)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
	$stmtt = $pdo->prepare($sqlt);
	$stmtt->execute() or die( print_r($stmtt->errorInfo(), true));
?>