<?php
	$page->assign('pagetitle',$bTitle.': '.$pagetitle);
	$page->assign('blogtitle',$bTitle);
	$page->assign('blogdescription',$bDescription);
	$page->assign('htmlkeywords',$bKeywords);
	$page->assign('htmldescription',$bSearchDesc);
	$page->assign('content',$content);

	$page->get_parsed('global.tpl',true);
?>