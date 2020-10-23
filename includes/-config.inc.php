<?php
	$dbhost = 'mysql';
	$dbname = 'blog';
	$dbuser = 'root';
	$dbpass = '';

	$prefix = '';

	$dbc = mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname);
?>
