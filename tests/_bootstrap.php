<?php
	// This is global bootstrap for autoloading
	use tad\FunctionMocker\FunctionMocker as Test;

	require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';

	Test::init();
