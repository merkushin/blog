<?php
	require_once(__DIR__ . '/includes/header.inc.php');
	if(isset($_POST['edit'])&&!empty($_POST['userid'])){
		$userId = intval($_POST['userid']);

		if((($ReadAccess==4) && ($userId<>$uId))||($userId==$uId)){     		$userRes = mysql_query('SELECT `login`,`password`,`email`,`userpicture`,`realname`,`showrealname`,`usersex`,DAYOFMONTH(`birthdate`) AS `dob`,MONTH(`birthdate`) AS `mob`,YEAR(`birthdate`) AS `yob`,`showbirthdate`,`userabout`,`viewby`,`listmode`,`showcomments`,`readaccess` FROM `'.$prefix.'users` WHERE `id`="'.$userId.'"');
     		if( 1 == mysql_num_rows($userRes) ) {
                $row = mysql_fetch_assoc($userRes);
     			$changes = false;

				if(!empty($_POST['newpassword'])&&!empty($_POST['repassword'])&&!empty($_POST['oldpassword'])){
					if ($row['password']===sha1('blog'.$_POST['oldpassword'])){

						if($_POST['newpassword']===$_POST['repassword']){
							mysql_query('UPDATE `'.$prefix.'users` SET `password`="'.mysql_escape_string(sha1('blog'.$_POST['newpassword'])).'" WHERE `id`="'.$userId.'"');
							$changes = true;
						}
					}
				}

				if(!empty($_POST['newemail'])&&(validate::email($_POST['newemail']))&&!empty($_POST['oldemail'])){
					if($row['email']==$_POST['oldemail']) {
						mysql_query('UPDATE `'.$prefix.'users` SET `email`="'.$_POST['newemail'].'" WHERE `id`="'.$userId.'"');
						$changes = true;
					}
				}

				if(isset($_POST['deleteuserpic'])){
					mysql_query('UPDATE `'.$prefix.'users` SET `userpicture`="" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if(isset($_FILES['userpic'])){
					define('ROOT', dirname(__FILE__));
					$userpath =  ROOT . '/images/';
					$available_extensions_array = array('jpg', 'jpeg', 'png', 'gif');
					$usersize = 131072;
					$usr_err = false;
					do {
						if(!isset($_FILES['useravatar'])){
							$usr_err = true;
							break;
						} else {
							$useravatar = $_FILES['useravatar'];
						}
						if($useravatar['error']== UPLOAD_ERR_NO_FILE){
							$usr_err = true;
							break;
						}
						if($useravatar['size']>$usersize){
							$usr_err = true;
							break;
						}

						$usrImgInfo = getimagesize($useravatar['tmp_name']);

						if($usrImgInfo[0]>150 or $usrImgInfo[1]>150){
							$usr_err = true;
							break;
						}
						if(($usrImgInfo[2]!=1) and ($usrImgInfo[2]!=2) and ($usrImgInfo[2]!=3)){
							$usr_err = true;
							break;
						}
						$E = explode('.', $useravatar['name']);
    					$ext = strtolower($E[sizeof($E)-1]);
    					if (!in_array($ext, $available_extensions_array)) {
    						$usr_err = true;
							break;
    					}

					} while (0);
					if(!$usr_err){
						$usrAvFName = $UserId.'.'.$ext;
						if(@move_uploaded_file($useravatar['tmp_name'],$userpath.$usrAvFName)){
							mysql_query('UPDATE `'.$prefix.'users` SET `userpicture`="'.$usrAvFName.'" WHERE id="'.$userId.'"');
							$changes = true;
						}
					}
				}

    			if(!empty($_POST['realname'])&&(mysql_escape_string(htmlspecialchars($_POST['realname']))!=$row['realname'])){
    				mysql_query('UPDATE `'.$prefix.'users` SET `realname`="'.mysql_escape_string(htmlspecialchars($_POST['realname'])).'" WHERE id="'.$userId.'"');
                    $changes = true;
				}

				if((!isset($_POST['showrealname'])&&($row['showrealname']=='No'))||(isset($_POST['showrealname'])&&($row['showrealname']=='Yes'))){
					$val = isset($_POST['showrealname'])?'No':'Yes';
					mysql_query('UPDATE `'.$prefix.'users` SET `showrealname`="'.$val.'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if(!empty($_POST['usersex'])&&($_POST['usersex']=='M'||$_POST['usersex']=='F')&&($_POST['usersex']!=$row['usersex'])){
					mysql_query('UPDATE `'.$prefix.'users` SET `usersex`="'.$_POST['usersex'].'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if((!empty($_POST['DOB'])&&(intval($_POST['DOB'])<>$row['dob']))||(!empty($_POST['MOB'])&&(intval($_POST['MOB'])<>$row['mob']))||(!empty($_POST['YOB'])&&(intval($_POST['YOB'])<>$row['yob']))){

    				$yob = $row['yob']; $mob = $row['mob']; $dob = $row['dob'];
					if((intval($_POST['YOB'])>1900)&&(intval($_POST['YOB'])<>$row['yob'])&&(intval($_POST['YOB'])<2038)){
						$yob = intval($_POST['YOB']);
					}

					if((intval($_POST['MOB'])>0)&&(intval($_POST['MOB'])<>$row['mob'])&&(intval($_POST['MOB'])<13)){
						$mob = (intval($_POST['MOB'])<10)?'0'.intval($_POST['MOB']):intval($_POST['MOB']);
					}

					if((intval($_POST['DOB'])>0)&&(intval($_POST['DOB'])<>$row['dob'])&&(intval($_POST['DOB'])<=31)){
						$dob = (intval($_POST['DOB'])<10)?'0'.intval($_POST['DOB']):intval($_POST['DOB']);
					}

					mysql_query('UPDATE `'.$prefix.'users` SET `birthdate`="'.($yob.'-'.$mob.'-'.$dob).'" WHERE `id`="'.$userId.'"') or die(mysql_error());
					$changes = true;
				}

				if((isset($_POST['showbirthdate'])&&($row['showbirthdate']=='Yes'))||(!isset($_POST['showbirthdate'])&&($row['showbirthdate']=='No'))){
					$val = isset($_POST['showbirthdate'])?'No':'Yes';
					mysql_query('UPDATE `'.$prefix.'users` SET `showbirthdate`="'.$val.'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if(!empty($_POST['userabout'])&&($_POST['userabout']!=$row['userabout'])){
					mysql_query('UPDATE `'.$prefix.'users` SET `userabout`="'.mysql_escape_string(htmlspecialchars($_POST['userabout'])).'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if(!empty($_POST['viewslist'])&&($_POST['viewslist']!=$row['viewby'])){
					mysql_query('UPDATE `'.$prefix.'users` SET `viewby`="'.mysql_escape_string(htmlspecialchars($_POST['viewslist'])).'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if(!empty($_POST['listmodes'])&&($_POST['listmodes']!=$row['listmode'])){
					mysql_query('UPDATE `'.$prefix.'users` SET `listmode`="'.mysql_escape_string(htmlspecialchars($_POST['listmodes'])).'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if(!empty($_POST['listmodes'])&&($_POST['listmodes']!=$row['listmode'])){
					mysql_query('UPDATE `'.$prefix.'users` SET `listmode`="'.mysql_escape_string(htmlspecialchars($_POST['listmodes'])).'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if(!empty($_POST['showcomments'])&&($_POST['showcomments']!=$row['showcomments'])){
					mysql_query('UPDATE `'.$prefix.'users` SET `showcomments`="'.mysql_escape_string(htmlspecialchars($_POST['showcomments'])).'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

				if(!empty($_POST['accesslevel'])&&($_POST['accesslevel']!=$row['readaccess'])){
					mysql_query('UPDATE `'.$prefix.'users` SET `readaccess`="'.intval($_POST['accesslevel']).'" WHERE `id`="'.$userId.'"');
					$changes = true;
				}

    			if($changes){
					header('location: ./profile.php?mode=view&id='.$userId);
					exit(0);
				} else {
					header('location: ./profile.php');
					exit(0);
				}
			}
			mysql_free_result($userRes);
		}

	}

	if((!empty($_GET['mode'])&&($_GET['mode']=='view'))&&!empty($_GET['id'])){
		// показываем профиль пользователя id
		$userId = (intval($_GET['id'])&&($_GET['id']>0))?intval($_GET['id']):0;
		$pRes = mysql_query('SELECT `login`,`userpicture`,`realname`,`showrealname`,`usersex`,UNIX_TIMESTAMP(`birthdate`) AS `birthdate`,`showbirthdate`,`userabout` FROM `'.$prefix.'users` WHERE `id`="'.$userId.'"');
        if (1 == mysql_num_rows($pRes)) {
        	$row = mysql_fetch_assoc($pRes);
        	$page->assign('userlogin',$row['login']);
        	if(!empty($row['userpicture'])){
        		$page->assign('imgname',$row['userpicture']);
        		$UserPicture = $page->get_parsed('imgpost.tpl');
        	} else {
        		$UserPicture = '';
        	}
        	$page->assign('userpicture',$UserPicture);
        	if($row['showrealname']=='Yes'){
				$realname = $row['realname'];
        	} else {
        		$realname = $lang['hidden'];
        	}
        	$page->assign('realname',$realname);
        	if ($row['showbirthdate']=='Yes') {
        		$birthdate = date("d.m.Y",$row['birthdate']);
        	} else {
        		$birthdate = $lang['hidden'];
        	}
        	$page->assign('birthdate',$birthdate);
			$page->assign('userabout',bbcodes($row['userabout']));
			$usersex = ($row['usersex']=='M')?$lang['male']:$lang['female'];
			$page->assign('usersex',$usersex);

			$LeftColumn = $page->get_parsed('profileview.tpl');
        }
        mysql_free_result($pRes);
	} else if(LOGGED==1 && (empty($_GET['mode'])||(!empty($_GET['mode'])&&($_GET['mode']=='edit')))){
		// изменение настроек авторизованного пользователя, вывод формы
		if(empty($_GET['id'])){
			$userId = $uId;
		} else {
			if($ReadAccess==4){
				$userId = intval($_GET['id']);
			} else {
				$userId = $uId;
			}
		}

		$pRes = mysql_query('SELECT `email`,`userpicture`,`realname`,`showrealname`,`usersex`,DAYOFMONTH(`birthdate`) AS `DOB`,MONTH(`birthdate`) AS `MOB`, YEAR(`birthdate`) AS `YOB`,`showbirthdate`,`userabout`,`viewby`,`listmode`,`showcomments`,`readaccess` FROM `'.$prefix.'users` WHERE `id`="'.$userId.'"') or die(mysql_error());
		$row = mysql_fetch_assoc($pRes);
		$email = $row['email'];
		$userpicture = $row['userpicture'];
		$realname = $row['realname'];
		$showrealname = $row['showrealname'];
		$usersex = $row['usersex'];
		$DOB = $row['DOB'];
		$MOB = intval($row['MOB']);
		$YOB = $row['YOB'];
		$showbirthdate = $row['showbirthdate'];
		$userabout = $row['userabout'];
		$viewby = $row['viewby'];
		$listmode = $row['listmode'];
		$showcomments = $row['showcomments'];
		$useraccess = $row['readaccess'];
		mysql_free_result($pRes);
		$months = array($lang['january'],$lang['february'],$lang['march'],$lang['april'],$lang['may'],$lang['june'],$lang['july'],$lang['august'],$lang['september'],$lang['october'],$lang['november'],$lang['december']);

		$page->assign('userid',$userId);

  		$CurrentUserpic = '';
		if(!empty($userpicture)){
			$page->assign('userpicture',$userpicture);
			$CurrentUserpic = $page->get_parsed('currentuserpic.tpl');
		}

		$sexes = array('male'=>'M','female'=>'F');
		$SexList = '';
		foreach ($sexes as $key => $value) {
			if($value == $usersex) {
				$SexList = $SexList.'<option value="'.$value.'" selected>'.$lang[$key].'</option>';
			} else {
				$SexList = $SexList.'<option value="'.$value.'">'.$lang[$key].'</option>';
		    }
		}

		$Month = '';
		$n = $MOB-1;
        for($i=0;$i<12;$i++){
            if($i==$n){
   				$Month = $Month.'<option value="'.($i+1).'" selected>'.$months[$i].'</option>';
   			} else {
   				$Month = $Month.'<option value="'.($i+1).'">'.$months[$i].'</option>';
   			}
		}
		$showrealname_checked = ($showrealname=='Yes')?'':'checked';
		$showbirthdate_checked = ($showbirthdate=='Yes')?'':'checked';

  		$modes = array('default','full','quick');
		$ListModes = '';
		for ($i=0;$i<count($modes);$i++){
			if($modes[$i]==$listmode){
				$ListModes = $ListModes.'<option value="'.$modes[$i].'" selected>'.$lang[$modes[$i]].'</option>';
			} else {
				$ListModes = $ListModes.'<option value="'.$modes[$i].'">'.$lang[$modes[$i]].'</option>';
			}
		}

		$views = array('date','calendar','categories');
		$ViewsList = '';
		for ($i=0;$i<count($views);$i++){
			if($viewby==$views[$i]){
				$ViewsList = $ViewsList.'<option value="'.$views[$i].'" selected>'.$lang['by'.$views[$i]].'</option>';
			} else {
				$ViewsList = $ViewsList.'<option value="'.$views[$i].'">'.$lang['by'.$views[$i]].'</option>';
			}
		}

		$commentsarr = array('Yes'=>'withcomments','No'=>'withoutcomments');
		$CommentsList = '';
		foreach ($commentsarr as $key => $value) {
			if($key == $showcomments) {
				$CommentsList = $CommentsList.'<option value="'.$key.'" selected>'.$lang[$value].'</option>';
			} else {
				$CommentsList = $CommentsList.'<option value="'.$key.'">'.$lang[$value].'</option>';
		    }
		}

		$ChangeAccess = '';
		if($ReadAccess==4){
			$accesses = array('registred'=>'1','private'=>'2','publisher'=>'3','admin'=>'4');
			$AccessList = '';
			foreach ($accesses as $key => $value) {
				if($useraccess == $value){
					$AccessList = $AccessList.'<option value="'.$value.'" selected>'.$lang[$key].'</option>';
				} else {
					$AccessList = $AccessList.'<option value="'.$value.'">'.$lang[$key].'</option>';
			    }
			}

			$page->assign('accesslist',$AccessList);
			$ChangeAccess = $page->get_parsed('changeaccess.tpl');
		}

		$page->assign('userid',$userId);
		$page->assign('currentemail',$email);
		$page->assign('currentuserpic',$CurrentUserpic);
		$page->assign('realname',$realname);
		$page->assign('DOB',$DOB);
		$page->assign('MOB',$Month);
		$page->assign('YOB',$YOB);
		$page->assign('showrealname_checked',$showrealname_checked);
		$page->assign('showbirthdate_checked',$showbirthdate_checked);
		$page->assign('userabout',$userabout);
		$page->assign('listmodes',$ListModes);
		$page->assign('viewslist',$ViewsList);
		$page->assign('commentslist',$CommentsList);
		$page->assign('sexlist',$SexList);
		$page->assign('changeaccess',$ChangeAccess);

		$LeftColumn = $page->get_parsed('profileedit.tpl');
	} else {
		$page->assign('message',$lang['noaccess']);
		$LeftColumn = $page->get_parsed('systemmessage.tpl');
	}

	require_once(__DIR__ . '/includes/userpanel.inc.php');
    $RightColumn = $UserPanel;
    $page->assign('leftcolumn',$LeftColumn);
    $page->assign('rightcolumn',$RightColumn);
	$content = $page->get_parsed('content.tpl');
	$pagetitle = $lang['profilepage'];

	require_once(__DIR__ . '/includes/footer.inc.php');
?>
