<?php

	function bbcodes($text) {
		$pattern=array(); $replacement=array();

		$pattern[]="/\[url[=]?\](.+?)\[\/url\]/i";
		$replacement[]="<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\1</a>";

		$pattern[]="/\[url=((f|ht)tp[s]?:\/\/[^<> \n]+?)\](.+?)\[\/url\]/i";
		$replacement[]="<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\3</a>";

		$pattern[]="/\[img[=]?\](http:\/\/[^<> \n]+?)\[\/img\]/i";
		$replacement[]="<img src=\"\\1\" />";

		$pattern[]="/\[[bB]\](.+?)\[\/[bB]\]/s";
		$replacement[]='<b>\\1</b>';

		$pattern[]="/\[[iI]\](.+?)\[\/[iI]\]/s";
		$replacement[]='<i>\\1</i>';

		$pattern[]="/\[[uU]\](.+?)\[\/[uU]\]/s";
		$replacement[]='<u>\\1</u>';

		$pattern[]="/\[[pP]\](.+?)\[\/[pP]\]/s";
		$replacement[]='<p>\\1</p>';

		$pattern[]="/\[[qQ]\](.+?)\[\/[qQ]\]/s";
		$replacement[]='<blockquote><p>\\1</p></blockquote>';

		$text=preg_replace($pattern, $replacement, $text);
		return $text;
	}

?>