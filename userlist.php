<?php
	require_once(__DIR__ . '/includes/header.inc.php');
	if($ReadAccess==4){
		$LeftColumn = '';
		if(!empty($_GET['delete'])){
			$userId = intval($_GET['delete']);
			mysql_query('DELETE FROM `'.$prefix.'users` WHERE `id`="'.$userId.'"');
		}

		$usersRes = mysql_query('SELECT `id`,`login`,`email` FROM `'.$prefix.'users` ORDER BY `id` DESC');
		if(mysql_num_rows($usersRes) > 0){
			$UsersList = '';
			while ($row = mysql_fetch_assoc($usersRes)) {
				$page->assign('id',$row['id']);
				$page->assign('login',$row['login']);
				$page->assign('email',$row['email']);
				$UsersList = $UsersList.$page->get_parsed('userrow.tpl');
			}
			$page->assign('userslist',$UsersList);
			$LeftColumn = $page->get_parsed('users.tpl');
		} else {
			$page->assign('message',$lang['nousers']);
			$LeftColumn = $page->get_parsed('systemmessage.tpl');
		}
	} else {
		$page->assign('message',$lang['noaccess']);
		$LeftColumn = $page->get_parsed('systemmessage.tpl');
	}

	require_once(__DIR__ . '/includes/userpanel.inc.php');
	$page->assign('rightcolumn',$UserPanel);
	$page->assign('leftcolumn',$LeftColumn);
	$content = $page->get_parsed('content.tpl');
	$pagetitle = $lang['bloguserlist'];

	require_once(__DIR__ . '/includes/footer.inc.php');
