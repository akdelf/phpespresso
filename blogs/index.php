#!/usr/local/bin/php
<?php
	
	require '../phpespresso.php';

	//генериим сайт
	$expresso = new phpespresso(__DIR__);
	$expresso->page(10, 1);
	


