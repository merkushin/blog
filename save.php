<?php
	require_once(__DIR__ . '/includes/header.inc.php');
	if(LOGGED==1){

		if(!empty($_POST['action'])) {
			if($_POST['action']=='comment'){
				$subject = mysql_escape_string(htmlspecialchars($_POST['subject']));
				$text    = mysql_escape_string(htmlspecialchars($_POST['text']));
				$postid  = intval($_POST['postid']);
				$author  = $uId;
				if(mysql_query('INSERT INTO `'.$prefix.'comments`(`post`,`author`,`subject`,`text`,`date`) VALUES("'.$postid.'","'.$author.'","'.$subject.'","'.$text.'",NOW())')){
					$page->assign('message',$lang['commentadded']);
				} else {
					$page->assign('message',$lang['commenterror']);
				}
			}
		}

		if((isset($_POST['draft']))||(isset($_POST['post']))){
			if($ReadAccess>=3){
				if(isset($_POST['postid'])&&!empty($_POST['title'])&&!empty($_POST['text'])&&isset($_POST['access'])&&!empty($_POST['mode'])){
					$title  = mysql_escape_string(htmlspecialchars($_POST['title']));
					$text   = mysql_escape_string(htmlspecialchars($_POST['text']));
					$postid = intval($_POST['postid']);
					$author = $uId;
					$access = (intval($_POST['access'])&&($_POST['access']>=0&&$_POST['access']<=4))?intval($_POST['access']):0;
     				$mode = mysql_escape_string($_POST['mode']);
     				if($mode!='full'&&$mode!='quick'){ $mode = 'full'; }
					$status = (isset($_POST['post']))?'published':'draft';
					if($postid<>0){
						if(isset($_POST['cat'])){
							mysql_query('DELETE FROM `'.$prefix.'pc` WHERE `post`="'.$postid.'"');
							$cat = $_POST['cat'];
							foreach($cat as $key){
								mysql_query('INSERT INTO `'.$prefix.'pc`(`cat`,`post`) VALUES("'.$key.'","'.$postid.'")');
							}
						}

						mysql_query('UPDATE `'.$prefix.'posts` SET `title`="'.$title.'",`text`="'.$text.'",`access`="'.$access.'",`mode`="'.$mode.'",`status`="'.$status.'",`date`=NOW() WHERE `id`="'.$postid.'"');
					} else if($postid==0){
						mysql_query('INSERT INTO `'.$prefix.'posts`(`title`,`text`,`access`,`mode`,`author`,`date`,`status`) VALUES("'.$title.'","'.$text.'","'.$access.'","'.$mode.'","'.$uId.'",NOW(),"'.$status.'")') or die(mysql_error());
						$postid = mysql_insert_id();

						mysql_query('DELETE FROM `'.$prefix.'pc` WHERE `post`="'.$postid.'"');
						if(isset($_POST['cat'])){
							$cat = $_POST['cat'];
						} else {
							$cat[1] = 'on';
						}
						foreach($cat as $key=>$vlaue){
							mysql_query('INSERT INTO `'.$prefix.'pc`(`cat`,`post`) VALUES("'.$key.'","'.$postid.'")') or die(mysql_error());
						}

					}
					if(isset($_POST['post'])){
						header('location: index.php?id='.$postid);
					} else {
						header('location: post.php?mode=editpost&id='.$postid);
					}
				} else {
					$page->assign('message',$lang['noaccess'].'2');
					if(empty($_POST['access'])) echo '11111111111';
				}
			} else {
				$page->assign('message',$lang['noaccess'].'1');
			}
		} else {
			$page->assign('message',$lang['noaction']);
		}
	} else {
		$page->assign('message',$lang['noaccess'].'1');
	}
    $LeftColumn = $page->get_parsed('systemmessage.tpl');
    require_once(__DIR__ . '/includes/userpanel.inc.php');
    $page->assign('leftcolumn',$LeftColumn);
    $page->assign('rightcolumn',$UserPanel);
    $content = $page->get_parsed('content.tpl');
    $pagetitle = $lang['save'];

	require_once(__DIR__ . '/includes/footer.inc.php');
?>
