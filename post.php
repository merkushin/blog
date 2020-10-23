<?php
	require_once(__DIR__ . '/includes/header.inc.php');
	if(!empty($_GET['mode'])&&($_GET['mode']!='newpost'&&$_GET['mode']!='editpost')) {
	    $LeftColumn = '';
	    if(!empty($_GET['mode'])&&($_GET['mode']=='drafts')){
			$dRes = mysql_query('SELECT `id`,`title`,UNIX_TIMESTAMP(`date`) AS `pdate` FROM `'.$prefix.'posts` WHERE `author`="'.$uId.'" AND `status`="draft"');
			if(mysql_num_rows($dRes)>0){
				$DraftList = '';
				while ($row = mysql_fetch_assoc($dRes)) {
					$date = date("d.m.Y H:i",$row['pdate']);
					$page->assign('id',$row['id']);
					$page->assign('date',$date);
					$page->assign('title',$row['title']);
                    $DraftList .= $page->get_parsed('draftitem.tpl');
				}
				$page->assign('draftlist',$DraftList);
				$LeftColumn = $page->get_parsed('draftlist.tpl');
			} else {
				$page->assign('message',$lang['nodrafts']);
				$LeftColumn = $page->get_parsed('systemmessage.tpl');
			}
			mysql_free_result($dRes);
		} else if(!empty($_GET['mode'])&&($_GET['mode']=='deletepost')){
			if(!empty($_GET['id'])&&(intval($_GET['id']))){
				$pId = intval($_GET['id']);
				$iRes = mysql_query('SELECT `author` FROM `'.$prefix.'posts` WHERE `id`="'.$pId.'"');
				if ( 1 == mysql_num_rows($iRes) ) {
					$row = mysql_fetch_row($iRes);
					if (($row[0] == $uId) || ($ReadAccess == 4)) {
						mysql_query('DELETE FROM `'.$prefix.'posts` WHERE `id`="'.$pId.'"');
						$page->assign('message',$lang['postdeleted']);
					} else {
						$page->assign('message',$lang['noaccess']);
					}
				} else {
					$page->assign('message',$lang['postnotfound']);
				}
				mysql_free_result($iRes);
			} else {
				$page->assign('message',$lang['postnotfound']);
			}
			$LeftColumn = $page->get_parsed('systemmessage.tpl');

		} else if(!empty($_GET['mode'])&&($_GET['mode']=='deletecomment')){
	    	if(!empty($_GET['id'])&&(intval($_GET['id']))){
				$cId = intval($_GET['id']);
				$iRes = mysql_query('SELECT `author` FROM `'.$prefix.'comments` WHERE `id`="'.$cId.'"');
				if ( 1 == mysql_num_rows($iRes) ) {
					$row = mysql_fetch_row($iRes);
					if (($row[0] == $uId) || ($ReadAccess == 4)) {
						mysql_query('DELETE FROM `'.$prefix.'comments` WHERE `id`="'.$cId.'"');
						$page->assign('message',$lang['postdeleted']);
					} else {
						$page->assign('message',$lang['noaccess']);
					}
				} else {
					$page->assign('message',$lang['postnotfound']);
				}
				mysql_free_result($iRes);
			} else {
				 $page->assign('message',$lang['postnotfound']);
			}
    		$LeftColumn = $page->get_parsed('systemmessage.tpl');
		}

		require_once(__DIR__ . '/includes/userpanel.inc.php');
        $page->assign('leftcolumn',$LeftColumn);
        $page->assign('rightcolumn',$UserPanel);



		$content = $page->get_parsed('content.tpl');
        $pagetitle = $lang['draftpage'];

	} else if((empty($GET['mode'])||(!empty($_GET['mode']))&&($_GET['mode']=='newpost'||$_GET['mode']=='editpost'))){
		$postId = 0; $access = 0; $mode = 'full';
		if($_GET['mode']=='editpost'){
			if(!empty($_GET['id'])&&intval($_GET['id'])){
				$pId = intval($_GET['id']);
				$pRes = mysql_query('SELECT `author`,`access`,`mode` FROM `'.$prefix.'posts` WHERE `id`="'.$pId.'"');
				if ( 1 == mysql_num_rows($pRes) ) {
					$row = mysql_fetch_assoc($pRes);
					if ($row['author'] == $uId) {
						$postId = $pId;
						$access = $row['access'];
						$mode = $row['mode'];
					}
				}
				mysql_free_result($pRes);
			}
		}

		$modes = array('full','quick');
		$ListModes = '';
		for ($i=0;$i<count($modes);$i++){
			if($modes[$i]==$mode){
				$ListModes = $ListModes.'<option value="'.$modes[$i].'" selected>'.$lang[$modes[$i]].'</option>';
			} else {
				$ListModes = $ListModes.'<option value="'.$modes[$i].'">'.$lang[$modes[$i]].'</option>';
			}
		}

		$accesses = array('public'=>'0','registred'=>'1','private'=>'2','publisher'=>'3','admin'=>'4');
		$AccessList = '';
		foreach ($accesses as $key => $value) {
			if($access == $value){
				$AccessList = $AccessList.'<option value="'.$value.'" selected>'.$lang[$key].'</option>';
			} else {
				$AccessList = $AccessList.'<option value="'.$value.'">'.$lang[$key].'</option>';
		    }
		}

		$title = ''; $text = ''; $cat = array();
		if ($postId<>0) {
			$pRes = mysql_query('SELECT `title`,`text` FROM `'.$prefix.'posts` WHERE `id`="'.$postId.'"');
			if ( 1 == mysql_num_rows($pRes) ) {
				$row = mysql_fetch_assoc($pRes);
				$title = $row['title'];
				$text = $row['text'];
				$catRes = mysql_query('SELECT `'.$prefix.'pc`.`cat` AS `catid`,`'.$prefix.'categories`.`name` AS `catname` FROM `'.$prefix.'pc`,`'.$prefix.'categories` WHERE `'.$prefix.'pc`.`post`="'.$postId.'" AND `'.$prefix.'categories`.`id`=`'.$prefix.'pc`.`cat`');
				if(mysql_num_rows($catRes)>0){
					while ($crow = mysql_fetch_assoc($catRes)){
						$cat[$crow['catid']] = $crow['catname'];
					}
				}
				mysql_free_result($catRes);
			} else {
				$postId = 0;
			}
		}
   		$selectCats = '';
		$catRes = mysql_query('SELECT `id`,`name` FROM `'.$prefix.'categories`');
		if(mysql_num_rows($catRes)>0){
			while ($row = mysql_fetch_assoc($catRes)) {
				if(array_key_exists($row['id'],$cat)){
					$selectCats = $selectCats.'<li><input type="checkbox" class="iCheck" name="cat['.$row['id'].']" checked /><label>'.$row['name'].'</label></li>';
				} else {
					$selectCats = $selectCats.'<li><input type="checkbox" class="iCheck" name="cat['.$row['id'].']" /><label>'.$row['name'].'</label></li>';
				}
			}
		}
		require_once(__DIR__ . '/includes/userpanel.inc.php');
		$page->assign('postid',$postId);
		$page->assign('accesslist',$AccessList);
		$page->assign('listmodes',$ListModes);
		$page->assign('userpanel',$UserPanel);
		$page->assign('selectcats',$selectCats);
		$page->assign('title',$title);
		$page->assign('text',$text);
		$page->assign('categories',$selectCats);


		$content = $page->get_parsed('newpost.tpl');
        $pagetitle = $lang['postpage'];
	}

	require_once(__DIR__ . '/includes/footer.inc.php');

?>
