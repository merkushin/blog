<?php
	if(!file_exists("./includes/config.inc.php")&&empty($_POST['action'])){
?>
<html>
<head>
	<title>Blog Engine</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<link href="templates/standart/styles/main.css" type="text/css" rel="stylesheet" />
</head>

<body>

<div id="Main">
	<div id="Header">
		<h1><a href="install.php">Install</a></h1>
		<h2>Installation process</h2>
	</div>
	<div class="break"></div>
	<div id="Content">
		<div id="C1">
			<div class="container">
				<div class="form">
					<h1>Install Blog</h1>
					<div class="blockr">
						<div class="formcontent">
							<form method="post" action="install.php">
							<input type="hidden" name="action" value="install" />
								<fieldset class="regularfieldset">
									<legend>Database connection</legend>
									<ul>
										<li><label>Database Host:</label> <input type="text" class="iText" name="hostname" /></li>
										<li><label>Database Name:</label> <input type="text" class="iText" name="dbname" /></li>
										<li><label>Database Username: </label> <input type="text" class="iText" name="dbuser" /></li>
										<li><label>Database Password:</label> <input type="password" class="iText" name="dbpass" /></li>
										<li><label>Table prefix:</label> <input type="text" class="iText" name="prefix" /></li>
									</ul>
								</fieldset>
								<fieldset class="regularfieldset">
									<legend>Administrator account</legend>
									<ul>
										<li><label>Username:</label> <input type="text" class="iText" name="name" /></li>
										<li><label>Password:</label> <input type="password" class="iText" name="password" />
									</ul>
								</fieldset>
								<fieldset class="regularfieldset">
									<ul>
										<li><input type="submit" class="iButton" value="Install" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="C2">&nbsp;</div>
	</div>
	<div class="break"></div>
	<div id="Footer"><p>&copy; 2007 Dmitry M. Merkushin</p></div>
</div>

</body>
</html>
<?php
	} else if(!empty($_POST['action'])){		function names($name) {
	        if (!preg_match('~^[a-z0-9_]+$~i', $name)) return false;
	        if (strlen($name) < 0) return false;
	        if (strlen($name) > 30) return false;
	        return true;
	    }		if(!empty($_POST['hostname'])){		 	if(names($_POST['hostname'])){		 		$dbhost = $_POST['hostname'];
		 	} else {		 		echo 'Hostname is not valid.';
		 		exit(0);
		 	}
		} else {			echo 'Hostname is not valid.';
			exit(0);
		}
		if(!empty($_POST['dbname'])){
		 	if(names($_POST['dbname'])){
		 		$dbname = $_POST['dbname'];
		 	} else {
		 		echo 'Database name is not valid.';
		 		exit(0);
		 	}
		} else {
	 		echo 'Database name is not valid.';
	 		exit(0);
	 	}
		if(!empty($_POST['dbuser'])){
		 	if(names($_POST['dbuser'])){
		 		$dbuser = $_POST['dbuser'];
		 	} else {
		 		echo 'Database username is not valid.';
		 		exit(0);
		 	}
		} else {
	 		echo 'Database username is not valid.';
	 		exit(0);
	 	}
		if(isset($_POST['dbpass'])){
		 	//if(names($_POST['dbpass'])){
		 		$dbpass = $_POST['dbpass'];
		 	//} else {
		 	//	echo 'Database user-password is not valid.';
		 	//	exit(0);
		 	//}
		} else {
	 		echo 'Database user-password is not valid.';
	 		exit(0);
	 	}
		if(!empty($_POST['prefix'])){
		 	if(names($_POST['prefix'])){
		 		$prefix = $_POST['prefix'];
		 	}
		} else {			$prefix = '';
		}
		if(!empty($_POST['name'])){
		 	if(names($_POST['name'])){
		 		$name = $_POST['name'];
		 	} else {
		 		echo 'Admin\'s username is not valid.';
		 		exit(0);
		 	}
		} else {
	 		echo 'Admin\'s username is not valid.';
	 		exit(0);
	 	}
		if(!empty($_POST['password'])){
		 	if(names($_POST['password'])){
		 		$password = sha1('blog' . $_POST['password']);
		 	} else {
		 		echo 'Admin\'s password is not valid.';
		 		exit(0);
		 	}
		} else {
	 		echo 'Admin\'s password is not valid.';
	 		exit(0);
	 	}
	 	// create ./includes/config.inc.php
   		//if((
   		$fp = fopen("./includes/config.inc.php","w");//) === true){   			$configinfo = '<?php $dbhost = "'.$dbhost.'"; $dbname = "'.$dbname.'"; $dbuser = "'.$dbuser.'"; $dbpass = "'.$dbpass.'"; $prefix = "'.$prefix.'"; $dbc = mysql_connect($dbhost,$dbuser,$dbpass); mysql_select_db($dbname); ?>';
			//if (
			fwrite($fp, $configinfo);// === true) {				fclose($fp);
		        echo "Config file created!<br />";
		        if(require_once('./includes/config.inc.php')){
					if(mysql_select_db($dbname)){						if(						mysql_query('CREATE TABLE `'.$prefix.'categories` (`id` int(11) NOT NULL auto_increment,`name` varchar(255) NOT NULL default "",PRIMARY KEY  (`id`))')&&
						mysql_query('CREATE TABLE `'.$prefix.'comments` (`id` int(10) unsigned NOT NULL auto_increment, `post` int(10) unsigned NOT NULL default "0", `author` int(10) unsigned NOT NULL default "0", `subject` varchar(255) NOT NULL default "", `text` text NOT NULL, `date` datetime NOT NULL default "0000-00-00 00:00:00", PRIMARY KEY  (`id`))')&&
						mysql_query('CREATE TABLE `'.$prefix.'config` (`title` varchar(255) NOT NULL default "", `description` varchar(255) NOT NULL default "", `keywords` varchar(255) NOT NULL default "", `searchdescription` varchar(255) NOT NULL default "", `language` varchar(255) NOT NULL default "", `template` varchar(255) NOT NULL default "", `listmode` varchar(255) NOT NULL default "")')&&
						mysql_query('CREATE TABLE `'.$prefix.'pc` (`post` int(10) unsigned NOT NULL default "0", `cat` int(10) unsigned NOT NULL default "0")')&&
						mysql_query('CREATE TABLE `'.$prefix.'posts` (`id` int(11) NOT NULL auto_increment, `author` int(11) NOT NULL default "0", `title` varchar(255) NOT NULL default "", `text` text NOT NULL, `date` datetime NOT NULL default "0000-00-00 00:00:00", `access` int(1) NOT NULL default "0", `status` enum("published","draft") NOT NULL default "published", `mode` enum("full","quick") NOT NULL default "full", PRIMARY KEY  (`id`))')&&
						mysql_query('CREATE TABLE `'.$prefix.'sessions` (`id` int(10) unsigned NOT NULL auto_increment, `userid` int(10) unsigned NOT NULL default "0", `time` int(10) unsigned NOT NULL default "0", PRIMARY KEY  (`id`), UNIQUE KEY `userid` (`userid`))')&&
						mysql_query('CREATE TABLE `'.$prefix.'users` (`id` int(10) unsigned NOT NULL auto_increment, `login` varchar(40) NOT NULL default "", `password` varchar(40) NOT NULL default "", `email` varchar(255) NOT NULL default "", `userpicture` varchar(14) NOT NULL default "", `realname` varchar(40) NOT NULL default "", `showrealname` enum("Yes","No") NOT NULL default "Yes", `usersex` enum("M","F") NOT NULL default "M", `birthdate` date NOT NULL default "0000-00-00", `showbirthdate` enum("Yes","No") NOT NULL default "Yes", `userabout` text NOT NULL, `viewby` varchar(15) NOT NULL default "", `listmode` enum("default","full","quick") NOT NULL default "default", `showcomments` enum("Yes","No") NOT NULL default "Yes", `readaccess` int(1) NOT NULL default "0", `verifycode` varchar(40) NOT NULL default "", PRIMARY KEY  (`id`))')
						){							echo "Tables created!<br />";
							if(
								mysql_query('INSERT INTO `'.$prefix.'config` VALUES ("My Blog", "Description", "key words", "description for searchbots", "english", "standart", "quick")')&&
								mysql_query('INSERT INTO `'.$prefix.'users`(`login`,`password`,`readaccess`) VALUES ("'.$name.'","'.$password.'","4")')
							){								echo 'Config data inserted!<br />';
								echo 'Please remove file install.php now and run index.php';
							} else {								echo "Can't insert config information";
							}
						} else {							echo "<b>Can't create tables!</b><br />";
						}
					} else {						echo "Can't select database.";
					}
				} else {					echo "Can't connect database.";
				}
		  /*  } else {		    	echo "Can't create file! <br />";
		    }
   		} else {   			echo 'Please check mode for /includes';
   		}*/


	} else {		echo 'Please remove file install.php';
	}
?>