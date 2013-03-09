#!/usr/local/bin/php
<?php
	
	require '../phpespresso.php';

	//генериим сайт
	$expresso = new phpespresso(__DIR__);
	$expresso->generation();
	$expresso->pageconfig('app/source/2013/1.md');


