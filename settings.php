<?php
	require_once(__DIR__ . '/includes/header.inc.php');
	if($ReadAccess==4){
		if(isset($_POST['change'])){
			if(isset($_POST['title'],$_POST['description'],$_POST['keywords'],$_POST['searchdesc'],$_POST['language'],$_POST['template'],$_POST['listmode'])){
				$cTitle = htmlspecialchars($_POST['title']);
				$cDescription = mysql_escape_string(htmlspecialchars($_POST['description']));
				$cKeywords = mysql_escape_string(htmlspecialchars($_POST['keywords']));
				$cSearchDesc = mysql_escape_string(htmlspecialchars($_POST['searchdesc']));
				$cLanguage = mysql_escape_string($_POST['language']);
				$cTemplate = mysql_escape_string($_POST['template']);
				$cListmode = mysql_escape_string($_POST['listmode']);

				mysql_query('UPDATE `'.$prefix.'config` SET `title`="'.$cTitle.'",`description`="'.$cDescription.'",`keywords`="'.$cKeywords.'",`searchdescription`="'.$cSearchDesc.'",`language`="'.$cLanguage.'",`template`="'.$cTemplate.'",`listmode`="'.$cListmode.'"') or die(mysql_error());
			}
		}
		$confres = mysql_query('SELECT `title`,`description`,`keywords`,`searchdescription`,`language`,`template`,`listmode` FROM `'.$prefix.'config` LIMIT 1');
		$row = mysql_fetch_assoc($confres);
		$sTitle = $row['title'];
		$page->assign('stitle',$sTitle);
		$sDescription = $row['description'];
		$page->assign('sdescription',$sDescription);
		$sKeywords = $row['keywords'];
		$page->assign('skeywords',$sKeywords);
		$sSearchDesc = $row['searchdescription'];
		$page->assign('ssearchdesc',$sSearchDesc);
		$sLanguage = $row['language'];
		$sTemplate = $row['template'];
		$sListmode = $row['listmode'];

		$LangList = '';
		if ($handle = opendir('./languages/')) {
		    while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {
		            $filename = explode(".",$file);
		            if($filename[0]==$sLanguage){
		            	$LangList = $LangList.'<option value="'.$filename[0].'" selected>'.$filename[0].'</option>';
		            } else {
		            	$LangList = $LangList.'<option value="'.$filename[0].'">'.$filename[0].'</option>';
		            }

		        }
		    }
		    closedir($handle);
		}
		$page->assign('slanglist',$LangList);

		$TemplList = '';
		if ($handle = opendir('./templates/')) {
		    while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {
		            if($file==$sTemplate){
		            	$TemplList = $TemplList.'<option value="'.$file.'" selected>'.$file.'</option>';
		            } else {
		            	$TemplList = $TemplList.'<option value="'.$file.'">'.$file.'</option>';
		            }

		        }
		    }
		    closedir($handle);
		}
		$page->assign('stemplist',$TemplList);

		$modes = array('default','full','quick');
		$ListModes = '';
		for ($i=0;$i<count($modes);$i++){
			if($modes[$i]==$sListmode){
				$ListModes = $ListModes.'<option value="'.$modes[$i].'" selected>'.$lang[$modes[$i]].'</option>';
			} else {
				$ListModes = $ListModes.'<option value="'.$modes[$i].'">'.$lang[$modes[$i]].'</option>';
			}
		}
		$page->assign('slistmodes',$ListModes);

		$LeftColumn = $page->get_parsed('settings.tpl');


	} else {
		$page->assign('message',$lang['noaccess']);
		$LeftColumn = $page->get_parsed('systemmessage.tpl');
	}

	$RightColumn = '';
	require_once(__DIR__ . '/includes/userpanel.inc.php');
	$RightColumn .= $UserPanel;

	$page->assign('leftcolumn',$LeftColumn);
	$page->assign('rightcolumn',$RightColumn);
	$content = $page->get_parsed('content.tpl');
	$pagetitle = $lang['blogsettings'];

	require_once(__DIR__ . '/includes/footer.inc.php');

?>
