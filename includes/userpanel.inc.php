<?php

	$UserPanel = '';
    if(LOGGED == 1){
    	// выводим панель управления
    	$UserName = id2name($uId,$prefix);
    	$page->assign('username',$UserName);
    	$page->assign('userid',$uId);
    	$pItems = '';
    	$pItems = $pItems.'<li><a href="profile.php" class="menulink">'.$lang['editprofile'].'</a></li>';
    	if($ReadAccess>=3) {    		$pItems = $pItems.'<li><a href="post.php?mode=drafts" class="menulink">'.$lang['mydrafts'].'</a></li>';
    		$pItems = $pItems.'<li><a href="post.php?mode=newpost" class="menulink">'.$lang['newpost'].'</a></li>';
    	}
    	if($ReadAccess==4) {
    		$pItems = $pItems.'<li><a href="userlist.php" class="menulink">'.$lang['userlist'].'</a></li>';
    		$pItems = $pItems.'<li><a href="categories.php" class="menulink">'.$lang['categories'].'</a></li>';
    		$pItems = $pItems.'<li><a href="settings.php" class="menulink">'.$lang['blogsettings'].'</a></li>';
    	}
    	$pItems = $pItems.'<li><a href="login.php?action=logout" class="menulink">'.$lang['logout'].'</a></li>';
    	$page->assign('pitems',$pItems);
    	$UserPanel = $page->get_parsed('userpanel.tpl');

    } else {
    	// выводим форму авторизации
    	$UserPanel = $page->get_parsed('authform.tpl');

    }

?>