<?php
	require_once( __DIR__ . '/includes/header.inc.php');

	// Проверяем, как должны отображаться записи и "правый блок"
	// Корректируем запрос для выборки записей
	if(empty($_GET['viewby'])){
		if(LOGGED == 1) {
			$vbRes = mysql_query('SELECT `viewby` FROM `'.$prefix.'users` WHERE `id`="'.$uId.'"');
			$row = mysql_fetch_row($vbRes);
			$viewby = $row[0];
		} else {
			$viewby = 'date';
		}
	} else {
		switch ($_GET['viewby']) {
			case 'date':
				$viewby = 'date';
				break;
			case 'calendar':
				$viewby = 'calendar';
				break;
			case 'categories':
				$viewby = 'categories';
				break;
			default:
    			$viewby = 'date';
  		}
	}
	$ViewByPanel = '';$pcond = '';
	$months = array('January','February','March','April','May','June','July','August','September','October','November','December');
	if(!empty($viewby)&&($viewby=='date')){
    	$dRes = mysql_query('SELECT DISTINCT MONTH(`date`) AS `pmonth`,YEAR(`date`) AS `pyear` FROM `'.$prefix.'posts` ORDER BY `date` DESC') or die(mysql_error());
    	$MonthsList = '';
    	if (mysql_num_rows($dRes) > 0) {
    		while ($dRow = mysql_fetch_assoc($dRes)) {
    			$month = $dRow['pmonth'];
    			$year = $dRow['pyear'];
    			$MonthsList = $MonthsList.'<li><a href="index.php?viewby=date&amp;month='.$month.'&amp;year='.$year.'" class="navilink">'.$lang[strtolower($months[($month-1)])].', '.$year.'</a></li>';
    		}

    	}
    	$page->assign('monthslist',$MonthsList);
   		$ViewByPanel = $page->get_parsed('months.tpl');
    	mysql_free_result($dRes);
    } else if(!empty($viewby)&&($viewby=='calendar')){
    	if(empty($_GET['month'])) {
    		$month = date("n"); // Если не задан месяц, то текущий (число 1..12)
    	} else {
    		$month = (intval($_GET['month'])&&(intval($_GET['month'])>=1)&&(intval($_GET['month'])<=12))?intval($_GET['month']):date("n"); // Если месяц задан, это целое число от 1 до 12, то его и передаем, иначе текущий месяц
    	}
    	if(empty($_GET['day'])) {
    		$day = date("j"); // Если не задан день, то текущий (число 1..31)
    	} else {
    		$day = (intval($_GET['day'])&&(intval($_GET['day'])>=1)&&(intval($_GET['day'])<=31))?intval($_GET['day']):date("j");
    	}
    	if(empty($_GET['year'])) {
    		$year = date("Y"); // Если не задан год, то текущий (число 1970..2038)
    	} else {
    		$year = (intval($_GET['year'])&&(intval($_GET['year'])>=1970)&&(intval($_GET['year'])<=2038))?intval($_GET['year']):date("Y");
    	}
    	$pcond = 'viewby=calendar&amp;year='.$year.'&amp;month='.$month.'&amp;day='.$day;
    	$ttime = strtotime($day.' '.$months[$month-1].' '.$year); // получаем сформированную дату, но в секундах
		$dsRes = mysql_query('SELECT DAYOFMONTH(`date`) AS `days` FROM `'.$prefix.'posts` WHERE MONTH(`date`)="'.$month.'" AND YEAR(`date`)="'.$year.'"') or die(mysql_error());
		$days = array();
		if(mysql_num_rows($dsRes)>0){
			while ($row = mysql_fetch_assoc($dsRes)) {
				if(!in_array($row['days'],$days)){
					$days[] = $row['days'];
				}
			}
		}
		include_once('./includes/calendar.inc.php');
		$prevmonth = ($month-1)%12;
		$prevmonth = ($prevmonth==0)?12:$prevmonth;
		$prevyear = ($prevmonth>$month)?($year-1):$year;
		$nextmonth = ($month+1)%12;
		$nextmonth = ($nextmonth==0)?12:$nextmonth;
		$nextyear = ($nextmonth<$month)?($year+1):$year;
   		$page->assign('month',$lang[strtolower($months[$month-1])]);
   		$page->assign('year',$year);
   		$page->assign('prevmonth',$prevmonth);
   		$page->assign('prevyear',$prevyear);
   		$page->assign('nextmonth',$nextmonth);
   		$page->assign('nextyear',$nextyear);
   		$page->assign('calendar',$calendar);
   		$ViewByPanel = $page->get_parsed('calendar.tpl');

   	} else if(!empty($viewby)&&($viewby=='categories')){

    	$cRes = mysql_query('SELECT `id`,`name` FROM `'.$prefix.'categories`');
        $categories = '';
        while ($row = mysql_fetch_assoc($cRes)) {
        	$cId = $row['id'];
        	$cName = $row['name'];
        	$ncRes = mysql_query('SELECT COUNT(`'.$prefix.'pc`.`post`) FROM `'.$prefix.'pc` WHERE `'.$prefix.'pc`.`cat`="'.$cId.'"');
        	$nrow = mysql_fetch_row($ncRes);
        	$pNumber = $nrow[0];
        	mysql_free_result($ncRes);
        	$categories = $categories . '<dt><a href="index.php?viewby=categories&amp;category=' . $cId . '" class="navilink">' . $cName . '</a> (' . $pNumber . ')</dt>';
        }
    	mysql_free_result($cRes);
    	$page->assign('categories',$categories);
   		$ViewByPanel = $page->get_parsed('catpanel.tpl');
   	}

    if(empty($_GET['id'])){
        // Выбираем настройки пользователя для просмотра списка записей
        if(LOGGED==1){
            $lsRes = mysql_query('SELECT `listmode` FROM `'.$prefix.'users` WHERE `id`="'.$uId.'"');
			$row = mysql_fetch_assoc($lsRes);
			mysql_free_result($lsRes);
            $ListMode = $row['listmode'];

        } else {
			$lsRes = mysql_query('SELECT `listmode` FROM `'.$prefix.'config` LIMIT 1');
			$row = mysql_fetch_assoc($lsRes);
			mysql_free_result($lsRes);
            $ListMode = $row['listmode'];

        }

        $PostsPerPage = 20;

    	// Проверяем, задана ли страница и корректируем выборку
    	if(!empty($_GET['page'])){
    		$ipage = intval($_GET['page'])&&($_GET['page']>=1)?intval($_GET['page']):1;
    		$ipage--;
    	} else {
    		$ipage = 0;
    	}

		$LimitFrom = $ipage * $PostsPerPage;

		// Проверяем, задана ли категория,
    	// и выбораем записи с учетом прав
		if(!empty($_GET['category'])){
   			$pcond = 'viewby=categories&amp;category='.$_GET['category'];
			$byCatRes = mysql_query('SELECT `'.$prefix.'pc`.`post` FROM `'.$prefix.'pc` INNER JOIN `'.$prefix.'categories` ON `'.$prefix.'pc`.`cat` = `'.$prefix.'categories`.`id` WHERE `'.$prefix.'categories`.`id` = "'.mysql_escape_string($_GET['category']).'"');
			$pIds = array();  $pIds[] = 0;
			while ($row = mysql_fetch_row($byCatRes)) $pIds[] = $row[0];
			mysql_free_result($byCatRes);
			$pIds_string = implode(",",$pIds);

			$sql = 'SELECT `id`,`author`,`title`,`text`,`mode`,UNIX_TIMESTAMP(`date`) AS `pdate` FROM `'.$prefix.'posts` WHERE `id` IN ('.$pIds_string.') AND `access`<="'.$ReadAccess.'" AND `status`="published" ORDER BY `date` DESC LIMIT '.$LimitFrom.','.$PostsPerPage;
			$psql = 'SELECT COUNT(`id`) FROM `'.$prefix.'posts` WHERE `id` IN ('.$pIds_string.') AND `access`<="'.$ReadAccess.'" AND `status`="published"';
		// Или задана дата... Выборка с учетом прав
		} else if(!empty($_GET['year'])&&!empty($_GET['month'])) {
			if(!empty($_GET['day'])){
				$sDay = (intval($_GET['day'])&&((intval($_GET['day'])>=1)&&(intval($_GET['day'])<=31)))?intval($_GET['day']):date("j");
				$day_cond = 'AND DAYOFMONTH(`date`)="'.$sDay.'" ';
			} else {
				$day_cond = '';
			}

			$sMonth = (intval($_GET['month'])&&((intval($_GET['month'])>=1)&&(intval($_GET['month'])<=12)))?intval($_GET['month']):date("n");
			$sYear = (intval($_GET['year'])&&((intval($_GET['year'])>=1970)&&(intval($_GET['year'])<=2038)))?intval($_GET['year']):date("Y");
			$pcond = 'viewby=calendar&amp;year='.$year.'&amp;month='.$month;
			if(!empty($sDay)) {
				$pcond = $pcond.'&amp;day='.$sDay;
			}

			$sql = 'SELECT `id`,`author`,`title`,`text`,`mode`,UNIX_TIMESTAMP(`date`) AS `pdate` FROM `'.$prefix.'posts` WHERE YEAR(`date`)="'.$sYear.'" AND MONTH(`date`)="'.$sMonth.'" '.$day_cond.'AND `status`="published" ORDER BY `date` DESC LIMIT '.$LimitFrom.','.$PostsPerPage;
			$psql = 'SELECT COUNT(`id`) FROM `'.$prefix.'posts` WHERE YEAR(`date`)="'.$sYear.'" AND MONTH(`date`)="'.$sMonth.'" '.$day_cond.'AND `status`="published"';
		} else {
			$sql = 'SELECT `id`,`author`,`title`,`text`,`mode`,UNIX_TIMESTAMP(`date`) AS `pdate` FROM `'.$prefix.'posts` WHERE `access`<="'.$ReadAccess.'" AND `status`="published" ORDER BY `date` DESC LIMIT '.$LimitFrom.','.$PostsPerPage;
			$psql = 'SELECT COUNT(`id`) FROM `'.$prefix.'posts` WHERE `access`<="'.$ReadAccess.'" AND `status`="published"';
		}

		$selRes = mysql_query($sql) or die(mysql_error());

		$pageRes = mysql_query($psql) or die(mysql_error());

        $PostList = '';
		if (mysql_num_rows($selRes) > 0) {
			// начинаем выводить
			while ($selpost = mysql_fetch_assoc($selRes)) {
				// 1. проверяем, какой задан стиль вывода (полный (full), сокращенный (quick), по_умолчанию (default))
				if ($ListMode == 'default') {
					$plm = $selpost['mode'];
				} else {
					$plm = $ListMode;
				}
                // какие есть различия между коротким  и полным режимами...
                $pId = $selpost['id'];
                $authorid = $selpost['author'];
                $authorname = id2name($authorid,$prefix);
                $date = date("d.m.Y H:i",$selpost['pdate']);
                $authorimage = '';
                $aimgres = mysql_query('SELECT `userpicture` FROM `'.$prefix.'users` WHERE `id`="'.$authorid.'"');
                $aimgrow = mysql_fetch_row($aimgres);
                if($aimgrow[0]!=''){
                	$page->assign('imgname',$aimgrow[0]);
                	$authorimage = $page->get_parsed('imgpost.tpl');
                }
                $title = $selpost['title'];
                $text = bbcodes($selpost['text']);
                $page->assign('postid',$pId);
                $page->assign('authorid',$authorid);
                $page->assign('date',$date);
                $page->assign('authorname',$authorname);
                $page->assign('userpicture',$authorimage);
                $page->assign('posttitle',$title);
				if ($plm == 'full') {
					$text = nl2br($text);
					$page->assign('posttext',$text);
					$posttpl = 'fullpost.tpl';
				} else if ($plm == 'quick') {
					$text = substr(strip_tags($text),0,255);
					$page->assign('posttext',$text);
					$posttpl = 'quickpost.tpl';
				}
				$comsql = mysql_query('SELECT COUNT(`id`) FROM `'.$prefix.'comments` WHERE `post`="'.$pId.'"');
				$comrow = mysql_fetch_row($comsql);
				$page->assign('commentsnum',$comrow[0]);

				$PostList .= $page->get_parsed($posttpl);
			}



			$Navigation = '';

			$row = mysql_fetch_row($pageRes);
			$allPages = ceil($row[0]/20);
			$ipage = $ipage+1;
			if($allPages>1){
				if($ipage>1){
					$Nav[] = '<a href="index.php?page='.($ipage - 1).'&amp;'.$pcond.'" class="navilink">&lt;&lt;&lt; '.$lang['prev20'].'</a>';
				}
				if($ipage<$allPages){
					$Nav[] = '<a href="index.php?page='.($ipage + 1).'&amp;'.$pcond.'" class="navilink">'.$lang['next20'].' &gt;&gt;&gt;</a>';
				}
				$NavLinks = implode(" | ",$Nav);
				$page->assign('navlinks',$NavLinks);
				$Navigation = $page->get_parsed('pages.tpl');
			}

			$PostList = $PostList;
            $page->assign('listitems',$PostList);
            $page->assign('navigation',$Navigation);
			$LeftColumn = $page->get_parsed('postlist.tpl');
		} else {
			$page->assign('message',$lang['noposts']);
			$LeftColumn = $page->get_parsed('systemmessage.tpl');
		}
		mysql_free_result($selRes);


    } else if(!empty($_GET['id'])&&intval($_GET['id'])){
		// вывод записи [id]
		$pId = intval($_GET['id'])&&($_GET['id']>0)?intval($_GET['id']):0;
		$LeftColumn = '';
		$postRes = mysql_query('SELECT `author`,`title`,`text`,UNIX_TIMESTAMP(`date`) AS `pdate` FROM `'.$prefix.'posts` WHERE `id`="'.$pId.'" AND `access`<="'.$ReadAccess.'" AND `status`="published" LIMIT 1');
		if (1 == mysql_num_rows($postRes)){
            // отображение поста
            $postRow = mysql_fetch_assoc($postRes);
            $authorid = $postRow['author'];
            $authorname = id2name($authorid,$prefix);
            $authorimage = '';
            $aimgres = mysql_query('SELECT `userpicture` FROM `'.$prefix.'users` WHERE `id`="'.$authorid.'"');
            $aimgrow = mysql_fetch_row($aimgres);
            if($aimgrow[0]!=''){
            	$page->assign('imgname',$aimgrow[0]);
				$authorimage = $page->get_parsed('imgpost.tpl');
			}
            $title = $postRow['title'];
            $text = nl2br(bbcodes($postRow['text']));
            $date = date("d.m.Y h:i", $postRow['pdate']);
            $page->assign('authorid',$authorid);
            $page->assign('userpicture',$authorimage);
            $page->assign('authorname',$authorname);
            $page->assign('posttitle',$title);
            $page->assign('posttext',$text);
            $page->assign('date',$date);
            $page->assign('postid',$pId);
            $comRes = mysql_query('SELECT COUNT(`id`) FROM `'.$prefix.'comments` WHERE `post`="'.$pId.'"');
            $comRow = mysql_fetch_row($comRes);
            $page->assign('commentsnum',$comRow[0]);
            mysql_free_result($comRes);
            $LeftColumn .= $page->get_parsed('fullpost.tpl');

			// проверяем, надо ли выводить комментарии
			$ShowComments = 0;
			if(LOGGED==1){
				$uComRes = mysql_query('SELECT `showcomments` FROM `'.$prefix.'users` WHERE `id`="'.$uId.'"');
				$row = mysql_fetch_row($uComRes);
				if($row[0]=='Yes'){
					$ShowComments = 1;
				}
				mysql_free_result($uComRes);
			}
			$Comments = ''; $CommentsList = '';
			if(!empty($_GET['showcomments']) || ($ShowComments == 1)){
				// выводим комментарии
				$comRes = mysql_query('SELECT `id`,`author`,UNIX_TIMESTAMP(`date`) AS `cdate`,`subject`,`text` FROM `'.$prefix.'comments` WHERE `post`="'.$pId.'"') or die(mysql_error());
				if (mysql_num_rows($comRes)>0) {
					while ($row = mysql_fetch_assoc($comRes)) {
						$cid = $row['id'];
						$cauthorid = $row['author'];
						$cauthorname = id2name($cauthorid,$prefix);
						$cdate = date("d.m.Y H:i", $row['cdate']);
						$csubject = $row['subject'];
						$ctext = nl2br(strip_tags($row['text']));
						if((LOGGED==1)&&(($uId==$cauthorid)||($ReadAccess==4))){
							$delete_link = '<a href="post.php?mode=deletecomment&amp;id='.$cid.'" class="actionlink">'.$lang['delete'].'</a>';
						} else $delete_link = '';
						$page->assign('cauthorid',$cauthorid);
						$page->assign('cauthorname',$cauthorname);
						$page->assign('cdate',$cdate);
						$page->assign('csubject',$csubject);
						$page->assign('ctext',$ctext);
						$page->assign('delete_link',$delete_link);

						$CommentsList .= $page->get_parsed('comment.tpl');
					}

					$page->assign('comments',$CommentsList);
					$Comments = $page->get_parsed('commentlist.tpl');
				} else {
					$page->assign('message',$lang['nocomments']);
					$Comments =  $page->get_parsed('systemmessage.tpl');
				}
				mysql_free_result($comRes);
   			}
   			$LeftColumn .= $Comments;

   			if(!empty($_GET['showform'])&&($_GET['showform']==1)&&(LOGGED==1)){
   				$page->assign('postid',$pId);
   				$CommentForm = $page->get_parsed('commentform.tpl');
   				$LeftColumn .= $CommentForm;
   			}

		} else {
			// говорим, что либо такой записи нет, либо доступ запрещен
		}
		mysql_free_result($postRes);
    }

    // Формирование и вывод правого столбца
    // 1. Проверяем авторизован или нет. В зависимости от этого
    //    отображаем или панель управления, или форму входа
    require_once(__DIR__ . '/includes/userpanel.inc.php');
    $RightColumn = $UserPanel.$ViewByPanel;

    $page->assign('leftcolumn',$LeftColumn);
    $page->assign('rightcolumn',$RightColumn);
	$content = $page->get_parsed('content.tpl');
	$pagetitle = $lang['blogindex'];

	require_once(__DIR__ . '/includes/footer.inc.php');
?>
