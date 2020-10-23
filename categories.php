<?php
	require_once(__DIR__ . '/includes/header.inc.php');
	if($ReadAccess==4){
		if(!empty($_POST['newcategory'])){
			$cName = htmlspecialchars($_POST['newcategory']);
			mysql_query('INSERT INTO `'.$prefix.'categories`(`name`) VALUES("'.$cName.'")');
		}
		if(!empty($_GET['delcategory'])){
			$cId = (intval($_GET['delcategory'])&&(intval($_GET['delcategory'])>1))?intval($_GET['delcategory']):0;
			mysql_query('DELETE FROM `'.$prefix.'categories` WHERE `id`="'.$cId.'"');
			mysql_query('DELETE FROM `'.$prefix.'pc` WHERE `cat`="'.$cId.'"');
		}

		$Categories = '';
		$res = mysql_query('SELECT `id`,`name` FROM `'.$prefix.'categories`');
		while ( $row = mysql_fetch_assoc($res) ) {
			$page->assign('cid',$row['id']);
			$page->assign('cname',$row['name']);
			$Categories .= $page->get_parsed('catrow.tpl');
		}
		$page->assign('categories',$Categories);
		$LeftColumn = $page->get_parsed('categories.tpl');

	} else {
		$page->assign('message',$lang['noaccess']);
		$LeftColumn = $page->get_parsed('systemmessage.tpl');
	}
	require_once(__DIR__ . '/includes/userpanel.inc.php');
	$page->assign('leftcolumn',$LeftColumn);
	$page->assign('rightcolumn',$UserPanel);
	$content = $page->get_parsed('content.tpl');
	$pagetitle = $lang['categoriespage'];

	require_once(__DIR__ . '/includes/footer.inc.php');
?>
