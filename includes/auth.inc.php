<?php
	if(!empty($_SESSION['auth'])&&!empty($_SESSION['uid'])){
		define('LOGGED',1);
		$uId = $_SESSION['uid'];
		mysql_query('INSERT INTO `'.$prefix.'sessions`(`userid`,`time`) VALUES("'.$uId.'","'.time().'") ON DUPLICATE KEY UPDATE `time`="'.time().'"');
	} else if(empty($_SESSION['auth'])&&!empty($_COOKIE['username'])&&!empty($_COOKIE['userhash'])){
		if(validate::username($_COOKIE['username'])){
			$sql = mysql_query('SELECT `id`,`login` FROM `'.$prefix.'users` WHERE `login`="'. mysql_escape_string($_COOKIE['username']) .'"') or die(mysql_error());
			if( 1 == mysql_num_rows($sql) ) {
				$row = mysql_fetch_row($sql);
	            $cookiehash = sha1($row[0] . 'blog' . $row[1]);
	            if ($cookiehash === $_COOKIE['userhash']) {
	                $_SESSION['auth'] = 1;
	                $_SESSION['uid'] = $uId = $row[0];
	                mysql_query('INSERT INTO `'.$prefix.'sessions`(`userid`,`time`) VALUES("'.$uId.'","'.time().'") ON DUPLICATE KEY UPDATE `time` = "' . time() . '"');
	                define('LOGGED',1);
	            }
			}
		}
	}

	if(!defined('LOGGED')){
		define('LOGGED',0);
	}

?>