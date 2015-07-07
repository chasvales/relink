<?php
session_start();
date_default_timezone_set('America/New_York');
$usrname = $_SESSION['relink_usr-name'];
	require 'config.php';
	class rlvi{
		
		public function getLinks(){
			global $pdo;
			global $root_domain;
			//print "function worked";
			$arr = array();
			$q = $pdo->query("SELECT * FROM rlvi_rd ORDER BY autoInc DESC");
			$data = $q->fetchAll();
			$count = $q->rowCount();

			if($count >= 1){
				foreach($data as $item){
					$o = array(
						'redirectlnk' => array(
							'oldlink' => $item['org_link'],
							'newlink' => $item['rdr_link'],
							'user' => $item['usr'],
							'userdate' => $item['timeUp'],
							'rowID' => $item['autoInc'],
							'rootDomain' => $root_domain
							)
					);
					array_push($arr,$o);
				}//end each
			}else{
				$o = array(
					'redirectlnk' => array(
						'oldlink' => 'No links found, please add redirects above.',
						'newlink' => 'This row will be removed once a redirect is present.',
						'user' => ' ',
						'userdate' => ' ',
						'rowID' => ' ',
						'rootDomain' => $root_domain
						)
				);
				array_push($arr,$o);
			}

			print json_encode($arr);
		}
		function getsettings(){
			global $root_domain;
			global $hta_dir;
			global $hta_st;
			global $usrname;
			$arp = array(
				'settings' => array(
					'rootdomain' => $root_domain,
					'htaccessdirectory' => $hta_dir,
					'startpoint' => $hta_st,
					'usrname' => $usrname
					)
				);
			print json_encode($arp);
		}
		function updatesettings($rootdomain,$htac_destination,$start_point){
		global $db_host; 
		global $db_name; 
		global $db_user; 
		global $db_pass; 
		global $root_domain; 
		global $hta_dir; 
		global $hta_st; 

		if($rootdomain == ""){
			$rootdomain = $root_domain;
		}
		if($htac_destination == ""){
			$htac_destination = $hta_dir;
		}
		if($start_point == ""){
			$start_point = $hta_st;
		}

			$fp = fopen('config.php', 'w');
			fwrite($fp, "<?php \n\r");

			fwrite($fp, "\$db_host ='" . $db_host . "'; \n\r");	
			fwrite($fp, "\$db_name ='" . $db_name . "'; \n\r");	
			fwrite($fp, "\$db_user ='" . $db_user . "'; \n\r");	
			fwrite($fp, "\$db_pass ='" . $db_pass . "'; \n\r");	
			fwrite($fp, "\$root_domain ='" . $rootdomain . "'; \n\r");	
			fwrite($fp, "\$hta_dir ='" . $htac_destination . "'; \n\r");
			fwrite($fp, "\$hta_st ='" . $start_point . "'; \n\r");

			fwrite($fp, "\$pdo = new PDO('mysql:host='.\$db_host.';dbname='.\$db_name,\$db_user,\$db_pass);");
			fwrite($fp, "\n\r ?>");
			fclose($fp);
 
		}
		function complieLinks(){
			global $pdo;
			global $hta_st;
			global $root_domain;
			global $hta_dir;

			$redirects = [];
			$arr = array();
			$q = $pdo->query("SELECT * FROM rlvi_rd");
			$data = $q->fetchAll();

			foreach($data as $item){
				$link = "Redirect 301 ".$item['org_link']." ".$root_domain.$item['rdr_link']." \r\n";
				array_push($redirects,$link);
			}
			/* GET THE CURRENT HTACCESS FILE */
			$string = file_get_contents($_SERVER['DOCUMENT_ROOT'].$hta_dir.'.htaccess');
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].$hta_dir.'.htaccess')){
				print "file not found";
				exit();
			}
			$selector = $hta_st;
			/* GET THE WORDPRESS SPECFIC REWRITES */
			$str = substr($string, 0, strpos($string, $selector));
			if($str == $selector){
				$str = " \r\n";
			}else{
				$str = $str.$hta_st." \r\n";
			}
			/* COMBINE ALL LINKS INTO ONE STATEMENT */
			$newString = implode(",", $redirects);
			$newString = str_replace(",", "", $newString);
			/* COMBINE LINKS STATEMENT WITH WORDPRESS STATEMENT */
			$newString = $str."# BEGIN RlviReWrite \r\n".$newString."# END RlviReWrite";
			/* UPDATE .HTACCESS FILE */
			file_put_contents($_SERVER['DOCUMENT_ROOT'].$hta_dir.'.htaccess',$newString);
			print "HTACCESS file has been updated.";
		}
		function addLink($old_link,$new_link){
			global $pdo;
			$user = $_SESSION['relink_usr-name'];;
			$upTime = date('m/d/y H:i:s');
			if($old_link[0] !='/'){
				$old_link = '/'.$old_link;
			}
			if($new_link[0] !='/'){
				$new_link = '/'.$new_link;
			}
			if($old_link[0] == '/' && $old_link[1] == '/'){
			}
			if($new_link[0] == '/' && $new_link[1] == '/'){
			}
			$sql = "INSERT INTO rlvi_rd (org_link,rdr_link,usr,timeUp) VALUES (:orglnk,:newlnk,:username,:updatedTime)";
			$stmt = $pdo->prepare($sql);
			$params = array(
						':orglnk' => $old_link,
						':newlnk' => $new_link,
						':username' => $user,
						':updatedTime' => $upTime
					);
			$stmt->execute($params) or die( print_r($stmt->errorInfo(), true));

		}
		function removeLinks($id){
			global $pdo;
			foreach($id as $mrow){
				$sql = "DELETE FROM rlvi_rd WHERE autoInc =:rowID LIMIT 1";
				$stmt = $pdo->prepare($sql);
				$params = array(
							':rowID' => $mrow
						);
				$stmt->execute($params) or die( print_r($stmt->errorInfo(), true));
			}

		}
		function logout(){
		    session_unset();
		    session_destroy();
		}
		function movehtacess(){
			global $pdo;
			global $hta_dir;
			copy('./.htaccess', $_SERVER['DOCUMENT_ROOT'].$hta_dir.'.htaccess');
		}
	}//end class
	$lnk = new rlvi;
?>