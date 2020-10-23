<?php
	session_name();
	session_start();
	if(file_exists(__DIR__ . '/../install.php')){
		echo 'Please remove file install.php and refresh this page.';
		exit(0);
	}
	require_once(__DIR__ . '/config.inc.php');
	require_once(__DIR__ . '/validate.class.php');
	require_once(__DIR__ . '/templater.class.php');
	require_once(__DIR__ . '/auth.inc.php');
	require_once(__DIR__ . '/functions.inc.php');
	require_once(__DIR__ . '/bbcodes.inc.php');



	$bConfig = mysql_query('SELECT `title`,`description`,`keywords`,`searchdescription`,`language`,`template` FROM `'.$prefix.'config`') or die('Error! Can\'t get config infomation!');
	if(mysql_num_rows($bConfig)==0){
		echo 'Config-information missing!';
		exit(0);
	}
	$bConfRow = mysql_fetch_assoc($bConfig);
	mysql_free_result($bConfig);
	$bTitle = $bConfRow['title'];
	$bDescription = $bConfRow['description'];
	$bKeywords = $bConfRow['keywords'];
	$bSearchDesc = $bConfRow['searchdescription'];
	$bLanguage = $bConfRow['language'];
	$bTemplate = $bConfRow['template'];



	if(file_exists(__DIR__ . "/../languages/$bLanguage.php")){
		include_once(__DIR__ . "/../languages/$bLanguage.php");
	} else {
		if(file_exists(__DIR__ . '/../languages/english.php')){
			include_once(__DIR__ . '/../languages/english.php');
		} else {
			echo 'No language files found!';
			exit(0);
		}
	}


	if(file_exists(__DIR__ . "/../templates/$bTemplate") && is_dir(__DIR__ . "/../templates/$bTemplate")){
		// "подгружаем" темплейтор
		$page = new templater(__DIR__ . "/../templates/$bTemplate","{","}");
	} else {
		if(file_exists(__DIR__ . '/../templates/standart') && is_dir(__DIR__ . "/../templates/standart")){
			// "подгружаем" темплейтор
			$page = new templater(__DIR__ . "/.../templates/$bTemplate","{","}");
		} else {
			echo $lang['notemplates'];
			exit(0);
		}
	}

	$page->assign('lang',$lang);


	if(LOGGED==1){
		$raRes = mysql_query('SELECT `readaccess` FROM `'.$prefix.'users` WHERE `id`="'.$uId.'"');
		$row = mysql_fetch_row($raRes);
		mysql_free_result($raRes);
		$ReadAccess = $row[0];
	} else {
		$ReadAccess = 0;
	}
?>
