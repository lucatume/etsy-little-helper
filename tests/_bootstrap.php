<?php
	// This is global bootstrap for autoloading
	use tad\FunctionMocker\FunctionMocker;

	require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';

	FunctionMocker::init();
