<?php
    // по id пользователя возвращает его имя
	function id2name($uId,$prefix) {
		$uId = (intval($uId)&&($uId>0))?intval($uId):0;
		$nRes = mysql_query('SELECT `login` FROM `'.$prefix.'users` WHERE `id`="'.$uId.'"');
		if (1 == mysql_num_rows($nRes)) {
			$row = mysql_fetch_row($nRes);
			return $row[0];
		} else {
			return ' ';
		}
	}

	function emailer($address, $subject, $message, $domain) {
		$subject = iconv('CP1251', 'UTF-8', $subject);
		$subject = '=?utf-8?B?' . base64_encode($subject) . '?=';
		$message = iconv('CP1251', 'UTF-8', $message);
		$headers = "From: \"Blog \" <noreply@$domain>\n" .
		"MIME-Version: 1.0\n" .
		"Content-Type: text/plain; charset=utf-8\n" .
		"Content-Transfer-Encoding: 8bit\n" .
		"Return-Path: noreply@$domain\n" .
		"Errors-to: noreply@$domain\n" .
		"X-Priority: 3\n" .
		"X-Mailer: Blog Mail\n";
		mail($address, $subject, $message, $headers);
	}

	function pwdgenerator($length=6) {
		$key = '';
		$lower = 'abcdefghijklmnopqrstuvwxyz';
		$upper = strtoupper($lower);
		$digit = '0123456789';
		$chars = $lower . $digit . $upper;
		for ($i=0, $size=strlen($chars); $i<$length; $i++) {
			$key .= $chars{mt_rand(0, $size-1)};
		}
		return $key;
	}

	function logOut($uId,$prefix){
		session_unregister('uid');
		session_unregister('auth');
		setcookie('username', '', time()-1,'/');
		setcookie('userhash', '', time()-1,'/');
		mysql_query('DELETE FROM `'.$prefix.'sessions` WHERE `userid`="'.$uId.'"');
		header('location: ./index.php');
	}

?>