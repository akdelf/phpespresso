#!/usr/local/bin/php
<?php
	
	require '../phpespresso.php';

	//указываем папки
	$blog = new phpespresso(__DIR__.'/source'); //content в markdown
	$blog->theme(__DIR__.'/themes/one/'); // тема для блога
	
	//генериим сайт в желаемую папку
	$blog->render(__DIR__.'/site/');

	

