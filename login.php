<?php
	require_once(__DIR__ . '/includes/header.inc.php');
	$LeftColumn = '';

	if(!empty($_GET['action'])&&($_GET['action']=='register')){
		$LeftColumn = $page->get_parsed('registerform.tpl');
	} else if(!empty($_GET['action'])&&($_GET['action']=='verify')) {
		if(!empty($_GET['email'])&&!empty($_GET['code'])){
			if(validate::email($_GET['email'])){
				$verRes = mysql_query('SELECT `id`,`verifycode` FROM `'.$prefix.'users` WHERE `email`="'.$_GET['email'].'" AND `readaccess`="0" LIMIT 1');
				if (1 == mysql_num_rows($verRes)) {
					$row = mysql_fetch_row($verRes);
					if ($row[1] == $_GET['code']) {
						mysql_query('UPDATE `'.$prefix.'users` SET `verifycode`="",`readaccess`="1" WHERE `id`="'.$row[0].'"');
						$page->assign('message',$lang['congratulate']);
					} else {
						$page->assign('message',$lang['codenotmatch']);
					}
				} else {
					$page->assign('message',$lang['noentry']);
				}
				mysql_free_result($verRes);
			} else {
				$page->assign('message',$lang['codenotmatch']);
			}
		} else {
			$page->assign('message',$lang['codenotmatch']);
		}
		$LeftColum = $page->get_parsed('systemmessage.tpl');
	} else if(!empty($_GET['action'])&&($_GET['action']=='logout')) {
		if(LOGGED==1) {
			logOut($uId,$prefix);
		} else {
			header('location: ./index.php');
		}
	}

	if(!empty($_POST['action'])&&($_POST['action']=='login')){
		if(!empty($_POST['username'])&&validate::username($_POST['username'])){
			if(!empty($_POST['password'])){
				if(isset($_SESSION['auth']) or isset($_SESSION['uid'])){
					$QueryResult = mysql_query('DELETE FROM `'.$prefix.'sessions` WHERE `userid`="'.$_SESSION['uid'].'"');
					session_unregister('auth');
					session_unregister('uid');
				}

				$password = mysql_escape_string($_POST['password']);

				$QueryResult = mysql_query('SELECT `id`,`login`,`password` FROM `'.$prefix.'users` WHERE login="'.$_POST['username'].'" LIMIT 1');

				if(mysql_num_rows($QueryResult)<>0){
					$Info = mysql_fetch_assoc($QueryResult);
					$login = $Info['login'];
					if($Info['password'] == sha1('blog'.$password)){
						$_SESSION['uid'] = $uId = $Info['id'];
						$QueryResult = mysql_query('DELETE FROM `sessions` WHERE `uid`="'.$uId.'"');
						mysql_query('INSERT INTO `sessions`(`uid`,`time`) VALUES("'.$uId.'","'.time().'")');
						$_SESSION['auth'] = 1;
						$cookiehash = sha1($uId . 'blog' . $login);
					    setcookie('username', $login, time()+86400,'/');
					    setcookie('userhash', $cookiehash, time()+86400,'/');
					    unset($cookiehash);

						header('location: index.php');
						exit(0);
					} else {
						$page->assign('message',$lang['errorpwd']);
					}

				} else {
					$page->assign('message',$lang['errorlogin']);
				}

			} else {
				$page->assign('message',$lang['errorpwd']);
			}
		} else {
			$page->assign('message',$lang['errorlogin']);
		}
		$LeftColumn = $page->get_parsed('systemmessage.tpl');
	} else if (!empty($_POST['action'])&&($_POST['action']=='register')) {
		if(!empty($_POST['username'])){
			if(validate::username($_POST['username'])){
				if(!empty($_POST['email'])){
					if(validate::email($_POST['email'])){
						$regRes = mysql_query('SELECT `login`,`email` FROM `'.$prefix.'users` WHERE `email`="'.$_POST['email'].'" OR `login`="'.$_POST['username'].'" LIMIT 1');
						if (1 == mysql_num_rows($regRes)) {
							$row = mysql_fetch_row($regRes);
							if($row[0]==$_POST['username']){
								$page->assign('message',$lang['loginbusy']);
							} else {
								$page->assign('message',$lang['emailbusy']);
							}
						} else {
							$verifycode = sha1($_POST['username'].time().$_POST['email']);
							$password = pwdgenerator();
							$pwd = sha1('blog'.$password);
							if(mysql_query('INSERT INTO `'.$prefix.'users`(`login`,`email`,`password`,`readaccess`,`verifycode`) VALUES("'.$_POST['username'].'","'.mysql_escape_string($_POST['email']).'","'.$pwd.'","0","'.$verifycode.'")')or die(mysql_error())){
								$subject = 'Register';
								$text = "Dear ".$_POST['username']."!\nPlease follow link for validating this Email-address.\n http://".$_SERVER['HTTP_HOST'].substr_replace($_SERVER['REQUEST_URI'],"",strrpos($_SERVER['REQUEST_URI'],"/")+1).'login.php?action=verify&code='.$verifycode."\n Your username: ".$_POST['username']."Password: ".$password."\n";
								$address = $_POST['email'];

								emailer($address,$subject,$text,$_SERVER['HTTP_HOST']);
								$page->assign('message',$lang['checkemail']);
							} else {
								echo 1;
							}
						}
					} else {
						$page->assign('message',$lang['emailerror']);
					}
				} else {
					$page->assign('message',$lang['noemail']);
				}
			} else {
				$page->assign('message',$lang['loginerror']);
			}
		} else {
			$page->assign('message',$lang['nologin']);
		}
		$LeftColumn = $page->get_parsed('systemmessage.tpl');
	}



	require_once(__DIR__ . '/includes/userpanel.inc.php');
	$RightColumn = $UserPanel;
	$page->assign('rightcolumn',$RightColumn);
	$page->assign('leftcolumn',$LeftColumn);
	$content = $page->get_parsed('content.tpl');
	$pagetitle = $lang['loginpage'];

	require_once(__DIR__ . '/includes/footer.inc.php');

?>
